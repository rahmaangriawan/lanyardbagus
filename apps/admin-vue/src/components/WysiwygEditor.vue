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
  // Enforce <p> tags for new lines
  document.execCommand('defaultParagraphSeparator', false, 'p')
})

function execCmd(command, value = null) {
  if (command === 'formatBlock' && value === 'p') {
    document.execCommand('formatBlock', false, '<p>')
  } else {
    document.execCommand(command, false, value)
  }
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
    if (editorRef.value) {
      editorRef.value.innerHTML = sourceCode.value
    }
    isSourceMode.value = false
  } else {
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
      class="min-h-[300px] max-h-[600px] overflow-y-auto p-6 outline-none editor-content editor-scroll"
      @input="updateContent"
    ></div>
    <textarea
      v-if="isSourceMode"
      v-model="sourceCode"
      class="w-full min-h-[300px] p-4 font-mono text-sm bg-zinc-950 text-zinc-100 outline-none"
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

.editor-content {
  color: hsl(var(--foreground));
  font-family: inherit;
  line-height: 1.6;
}
.editor-content p, .editor-content div {
  margin-bottom: 1.25rem;
}
.editor-content p:last-child, .editor-content div:last-child {
  margin-bottom: 0;
}
.editor-content h1 {
  font-size: 1.875rem;
  font-weight: 700;
  margin-top: 2rem;
  margin-bottom: 1rem;
}
.editor-content h2 {
  font-size: 1.5rem;
  font-weight: 700;
  margin-top: 1.75rem;
  margin-bottom: 0.875rem;
}
.editor-content h3 {
  font-size: 1.25rem;
  font-weight: 700;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
}
.editor-content ul {
  list-style-type: disc;
  padding-left: 1.25rem;
  margin-bottom: 1.25rem;
}
.editor-content ol {
  list-style-type: decimal;
  padding-left: 1.25rem;
  margin-bottom: 1.25rem;
}
.editor-content blockquote {
  border-left: 3px solid hsl(var(--border));
  padding-left: 1rem;
  font-style: italic;
  margin-bottom: 1.25rem;
  color: hsl(var(--muted-foreground));
}
.editor-content a {
  color: hsl(var(--primary));
  text-decoration: underline;
}
.editor-content img {
  max-width: 100%;
  height: auto;
  border-radius: 0.5rem;
  margin: 1.25rem 0;
}
</style>
