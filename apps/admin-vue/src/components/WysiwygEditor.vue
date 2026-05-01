<script setup>
import { ref, watch, onMounted } from 'vue'
import { Button } from './ui'
import MediaPickerModal from './MediaPickerModal.vue'
import { 
  Bold, Italic, Underline, Strikethrough, 
  Heading1, Heading2, Heading3,
  List, ListOrdered, Quote, Link, Image, Code, Undo, Redo,
  Table, Table2, Pilcrow
} from 'lucide-vue-next'

const props = defineProps({
  modelValue: { type: String, default: '' },
  placeholder: { type: String, default: 'Tulis konten di sini...' }
})

const emit = defineEmits(['update:modelValue', 'insertImage'])

const editorRef = ref(null)

watch(() => props.modelValue, (newVal) => {
  if (editorRef.value && editorRef.value.innerHTML !== newVal) {
    editorRef.value.innerHTML = newVal || ''
  }
})

onMounted(() => {
  if (editorRef.value && props.modelValue) {
    editorRef.value.innerHTML = props.modelValue
  }
  // Enforce <p> tags for new lines instead of <div> or <br>
  document.execCommand('defaultParagraphSeparator', false, 'p')
})

function execCmd(command, value = null) {
  document.execCommand(command, false, value)
  updateContent()
}

function updateContent() {
  const content = editorRef.value?.innerHTML || ''
  emit('update:modelValue', content)
  sourceCode.value = content
}

const isSourceMode = ref(false)
const sourceCode = ref(props.modelValue)

function toggleSourceMode() {
  if (isSourceMode.value) {
    // Switching back to visual mode
    if (editorRef.value) {
      editorRef.value.innerHTML = sourceCode.value
    }
    isSourceMode.value = false
  } else {
    // Switching to source mode
    sourceCode.value = editorRef.value?.innerHTML || ''
    isSourceMode.value = true
  }
}

function onSourceCodeInput() {
  emit('update:modelValue', sourceCode.value)
}

function insertLink() {
  const url = prompt('Masukkan URL:', 'https://')
  if (url) execCmd('createLink', url)
}

const showMediaPicker = ref(false)

function insertImage() {
  showMediaPicker.value = true
}

function onMediaSelected(media) {
  if (media?.url) {
    const alt = media.alt_text || media.file_name || ''
    const imgHTML = `<img src="${media.url}" alt="${alt}" class="max-w-full h-auto rounded-lg my-4">`
    execCmd('insertHTML', imgHTML + '<p><br></p>')
  }
  showMediaPicker.value = false
}

function insertTable() {
  const rows = parseInt(prompt('Jumlah baris:', '3')) || 3
  const cols = parseInt(prompt('Jumlah kolom:', '3')) || 3
  
  let tableHTML = '<table class="w-full border-collapse border border-border my-4"><tbody>'
  for (let i = 0; i < rows; i++) {
    tableHTML += '<tr>'
    for (let j = 0; j < cols; j++) {
      tableHTML += '<td class="border border-border p-2">Konten</td>'
    }
    tableHTML += '</tr>'
  }
  tableHTML += '</tbody></table><p><br></p>'
  
  execCmd('insertHTML', tableHTML)
}

const tools = [
  { icon: Undo, action: () => execCmd('undo'), title: 'Undo' },
  { icon: Redo, action: () => execCmd('redo'), title: 'Redo' },
  { type: 'divider' },
  { icon: Bold, action: () => execCmd('bold'), title: 'Bold' },
  { icon: Italic, action: () => execCmd('italic'), title: 'Italic' },
  { icon: Underline, action: () => execCmd('underline'), title: 'Underline' },
  { icon: Strikethrough, action: () => execCmd('strikeThrough'), title: 'Strikethrough' },
  { type: 'divider' },
  { icon: Heading1, action: () => execCmd('formatBlock', 'h1'), title: 'Heading 1' },
  { icon: Heading2, action: () => execCmd('formatBlock', 'h2'), title: 'Heading 2' },
  { icon: Heading3, action: () => execCmd('formatBlock', 'h3'), title: 'Heading 3' },
  { icon: Pilcrow, action: () => execCmd('formatBlock', 'p'), title: 'Paragraph' },
  { type: 'divider' },
  { icon: List, action: () => execCmd('insertUnorderedList'), title: 'Bullet List' },
  { icon: ListOrdered, action: () => execCmd('insertOrderedList'), title: 'Numbered List' },
  { icon: Quote, action: () => execCmd('formatBlock', 'blockquote'), title: 'Quote' },
  { type: 'divider' },
  { icon: Link, action: insertLink, title: 'Insert Link' },
  { icon: Image, action: insertImage, title: 'Insert Image' },
  { icon: Table2, action: insertTable, title: 'Insert Table' },
  { icon: Code, action: toggleSourceMode, title: 'View HTML Source' },
]
</script>

<template>
  <div class="border border-input rounded-lg overflow-hidden bg-background">
    <!-- Toolbar -->
    <div class="flex flex-wrap items-center gap-1 p-2 border-b border-input bg-muted/30">
      <template v-for="(tool, i) in tools" :key="i">
        <div v-if="tool.type === 'divider'" class="w-px h-5 bg-border mx-1"></div>
        <Button
          v-else
          variant="ghost"
          size="icon"
          class="h-8 w-8"
          :class="{ 'bg-primary/20 text-primary': isSourceMode && tool.title === 'View HTML Source' }"
          @click="tool.action"
          :title="tool.title"
          :disabled="isSourceMode && tool.title !== 'View HTML Source' && tool.title !== 'Undo' && tool.title !== 'Redo'"
        >
          <component :is="tool.icon" class="w-4 h-4" />
        </Button>
      </template>
    </div>
    
    <!-- Editor -->
    <div
      v-show="!isSourceMode"
      ref="editorRef"
      contenteditable="true"
      class="min-h-[300px] max-h-[600px] overflow-y-auto p-4 outline-none prose prose-sm max-w-none editor-scroll"
      @input="updateContent"
    ></div>
    <textarea
      v-if="isSourceMode"
      v-model="sourceCode"
      class="w-full min-h-[300px] max-h-[600px] p-4 font-mono text-sm bg-muted/50 border-none outline-none resize-none editor-scroll"
      @input="onSourceCodeInput"
    ></textarea>

    <!-- Media Picker Modal -->
    <MediaPickerModal 
      v-if="showMediaPicker"
      @select="onMediaSelected"
      @close="showMediaPicker = false"
      :show="showMediaPicker"
    />
  </div>
</template>

<style scoped>
/* Custom Scrollbar for Editor */
.editor-scroll::-webkit-scrollbar {
  width: 8px;
}
.editor-scroll::-webkit-scrollbar-track {
  background: transparent;
}
.editor-scroll::-webkit-scrollbar-thumb {
  background: hsl(var(--border));
  border-radius: 4px;
}
.editor-scroll::-webkit-scrollbar-thumb:hover {
  background: hsl(var(--muted-foreground) / 0.5);
}

:deep(.prose) {
  @apply text-foreground;
}
:deep(.prose p) {
  @apply mb-4 leading-relaxed;
}
:deep(.prose h1) {
  @apply text-3xl font-bold mt-8 mb-4;
}
:deep(.prose h2) {
  @apply text-2xl font-bold mt-6 mb-3;
}
:deep(.prose h3) {
  @apply text-xl font-bold mt-4 mb-2;
}
:deep(.prose ul) {
  @apply list-disc list-inside my-2;
}
:deep(.prose ol) {
  @apply list-decimal list-inside my-2;
}
:deep(.prose blockquote) {
  @apply border-l-4 border-border pl-4 italic my-2;
}
:deep(.prose a) {
  @apply text-primary underline;
}
:deep(.prose img) {
  @apply max-w-full h-auto rounded-lg my-2;
}
:deep(.prose table) {
  @apply w-full border-collapse border border-border my-4;
}
:deep(.prose td), :deep(.prose th) {
  @apply border border-border p-2;
}
</style>
