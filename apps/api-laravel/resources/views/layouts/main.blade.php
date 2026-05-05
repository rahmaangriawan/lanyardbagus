<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>@yield('title', $settings->site_title ?? 'Lanyardbagus')</title>
    <meta name="description" content="@yield('meta_description', $settings->homepage_meta_description ?? '')">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Site Verification Tags -->
    @php
        $gsc = $settings->google_site_verification ?? \App\Models\Setting::get('google_search_console') ?? null;
        $bing = $settings->bing_site_verification ?? \App\Models\Setting::get('bing_webmaster') ?? null;
    @endphp
    @if($gsc)
    <meta name="google-site-verification" content="{{ $gsc }}">
    @endif
    @if($bing)
    <meta name="msvalidate.01" content="{{ $bing }}">
    @endif
    
    @if(isset($settings) && $settings->faviconImage)
    <link rel="icon" type="image/x-icon" href="{{ $settings->faviconImage->url }}">
    @endif

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com">
    @stack('preload')
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap">
    </noscript>

    <!-- Tailwind CSS with Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html { scroll-behavior: smooth; }
        body { overflow-x: clip; }
        .display-mega { font-size: 80px; font-weight: 400; line-height: 1.0; letter-spacing: -2px; }
        .display-xl  { font-size: 64px; font-weight: 400; line-height: 1.0; letter-spacing: -1.6px; }
        .display-lg  { font-size: 52px; font-weight: 400; line-height: 1.0; letter-spacing: -1.3px; }
        .display-md  { font-size: 44px; font-weight: 400; line-height: 1.09; letter-spacing: -1px; }
        .display-sm  { font-size: 36px; font-weight: 400; line-height: 1.11; letter-spacing: -0.5px; }
        .title-lg    { font-size: 32px; font-weight: 400; line-height: 1.13; letter-spacing: -0.4px; }
        @media (max-width: 1024px) { .display-mega { font-size: 64px; letter-spacing: -1.6px; } }
        @media (max-width: 640px)  { .display-mega { font-size: 40px; letter-spacing: -1px; } .display-lg { font-size: 36px; } .display-md { font-size: 32px; } .display-sm { font-size: 28px; } }
        .nav-glass { background: rgba(255,255,255,0.92); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        details summary::-webkit-details-marker { display: none; }
    </style>
    @stack('styles')
    @stack('head')
</head>
<body class="bg-cb-canvas text-cb-ink font-sans antialiased">
    @php
        if (!isset($settings) || !$settings) {
            $settings = \App\Models\SiteSetting::with(['logoImage', 'faviconImage'])->first();
        }
        $homepage = optional($settings)->homepage_data ?? [];
        $navLinks = data_get($homepage, 'navigation_links', [
            ['label' => 'Beranda', 'href' => '/#beranda'],
            ['label' => 'Cerita Kami', 'href' => '/#cerita'],
            ['label' => 'Produk', 'href' => '/#produk'],
            ['label' => 'Harga', 'href' => '/#harga'],
            ['label' => 'FAQ', 'href' => '/faq'],
        ]);
        $headerCta = data_get($homepage, 'header_cta', [
            'label' => 'Pesan Sekarang',
            'href' => '/#hubungi',
        ]);
    @endphp

    <!-- Global Top Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 nav-glass border-b border-cb-hairline">
        <div class="max-w-[1200px] mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center">
                <a href="/">
                    @if(isset($settings) && $settings->logoImage)
                    <img src="{{ $settings->logoImage->url }}" alt="{{ $settings->site_title ?? 'Logo' }}" class="h-8 w-auto" width="284" height="56" decoding="async">
                    @else
                    <span class="text-lg font-semibold text-cb-ink tracking-tight">{{ $settings->site_title ?? 'Lanyardbagus' }}</span>
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-6">
                @foreach($navLinks as $link)
                    @php
                        $href = data_get($link, 'href', '#');
                        if (str_starts_with($href, '#')) {
                            $href = '/' . $href;
                        }
                    @endphp
                    <a href="{{ $href }}" class="text-sm font-medium text-cb-body hover:text-cb-ink transition-colors">
                        {{ data_get($link, 'label', 'Menu') }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3">
                @php
                    $ctaHref = data_get($headerCta, 'href', '/#hubungi');
                    if (str_starts_with($ctaHref, '#')) {
                        $ctaHref = '/' . $ctaHref;
                    }
                @endphp
                <!-- Desktop CTA -->
                <a href="{{ $ctaHref }}" class="!hidden md:!inline-flex bg-cb-blue text-white px-5 py-2.5 rounded-cb-pill text-sm font-semibold hover:bg-cb-blue-active transition-colors">
                    {{ data_get($headerCta, 'label', 'Pesan Sekarang') }}
                </a>

                <!-- Mobile Hamburger -->
                <button id="mobile-menu-btn" class="md:hidden flex p-2 text-cb-ink transition-transform duration-300" aria-label="Menu">
                    <svg id="menu-icon-hamburger" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Mobile Menu Backdrop -->
    <div id="mobile-menu-backdrop" class="fixed inset-0 z-[55] bg-cb-ink/20 backdrop-blur-sm hidden animate-in fade-in duration-300"></div>

    <!-- Mobile Menu Overlay (Dropdown Style) -->
    <div id="mobile-menu-overlay" class="fixed top-16 left-0 right-0 z-[60] bg-white hidden flex-col shadow-2xl border-b border-cb-hairline animate-in slide-in-from-top duration-300">
        <div class="overflow-y-auto px-6 py-8 flex flex-col gap-8">
            <nav class="flex flex-col gap-6">
                @foreach($navLinks as $link)
                    @php
                        $href = data_get($link, 'href', '#');
                        if (str_starts_with($href, '#')) {
                            $href = '/' . $href;
                        }
                    @endphp
                    <a href="{{ $href }}" class="text-xl font-normal text-cb-ink mobile-menu-link">
                        {{ data_get($link, 'label', 'Menu') }}
                    </a>
                @endforeach
            </nav>
            <div class="pt-8 border-t border-cb-hairline">
                <a href="{{ $ctaHref }}" class="w-full bg-cb-blue text-white py-4 rounded-cb-lg text-center font-normal block shadow-lg shadow-cb-blue/20 mobile-menu-link">
                    {{ data_get($headerCta, 'label', 'Pesan Sekarang') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    @include('partials.footer')

    <script>
        // Mobile Menu Logic
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const hamburgerIcon = document.getElementById('menu-icon-hamburger');
        const closeIcon = document.getElementById('menu-icon-close');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const mobileMenuBackdrop = document.getElementById('mobile-menu-backdrop');
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

        function toggleMenu() {
            const isOpen = !mobileMenuOverlay.classList.contains('hidden');
            
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        }

        function openMenu() {
            mobileMenuOverlay.classList.remove('hidden');
            mobileMenuOverlay.classList.add('flex');
            mobileMenuBackdrop.classList.remove('hidden');
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeMenu() {
            mobileMenuOverlay.classList.add('hidden');
            mobileMenuOverlay.classList.remove('flex');
            mobileMenuBackdrop.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleMenu);
        if (mobileMenuBackdrop) mobileMenuBackdrop.addEventListener('click', closeMenu);
        
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });
    </script>
    @stack('scripts')

    <!-- Lucide Icons (CDN) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        function initLucide() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
        document.addEventListener('DOMContentLoaded', initLucide);
        if (document.readyState !== 'loading') initLucide();
        // Global observer for dynamic content
        window.addEventListener('load', initLucide);
    </script>

    <!-- AI Live Chat Widget -->
    <div id="chat-widget-container">
        <!-- Chat Button (Floating) -->
        <button id="chat-toggle-btn" class="chat-toggle-btn" aria-label="Buka chat">
            <i data-lucide="message-circle" class="w-6 h-6"></i>
            <span class="chat-badge" id="chat-badge" style="display: none;">!</span>
        </button>

        <!-- Chat Box -->
        <div id="chat-box" class="chat-box" style="display: none;">
            <div class="chat-header">
                <div class="chat-header-info">
                    <div class="chat-avatar">
                        <i data-lucide="bot" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 id="chat-widget-title">Asisten Lanyard Bagus</h4>
                        <span class="chat-status">
                            <span class="status-dot"></span>
                            Online
                        </span>
                    </div>
                </div>
                <button id="chat-close-btn" class="chat-close-btn" aria-label="Tutup chat">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div id="chat-messages" class="chat-messages">
                <div class="chat-message bot-message">
                    <div class="message-content">
                        <p id="chat-welcome-message">Halo! 👋 Saya asisten Lanyard Bagus. Ada yang bisa saya bantu tentang lanyard custom?</p>
                    </div>
                </div>
            </div>

            <div class="chat-input-area">
                <form id="chat-form" class="chat-form">
                    <input
                        type="text"
                        id="chat-input"
                        class="chat-input"
                        placeholder="Ketik pesan Anda..."
                        maxlength="2000"
                        required
                    />
                    <button type="submit" class="chat-send-btn" aria-label="Kirim pesan">
                        <i data-lucide="send" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Chat Widget Styles - Match Site Design System */
        #chat-widget-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .chat-toggle-btn {
            width: 56px;
            height: 56px;
            border-radius: 9999px;
            background: #3b82f6;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transition: all 0.2s ease;
            position: relative;
        }

        .chat-toggle-btn:hover {
            background: #2563eb;
            transform: scale(1.05);
        }

        .chat-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            border-radius: 9999px;
            color: white;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .chat-box {
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 380px;
            max-width: calc(100vw - 48px);
            height: 520px;
            max-height: calc(100vh - 120px);
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.25s ease;
            border: 1px solid #e5e7eb;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-header {
            background: #3b82f6;
            color: white;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-header h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
        }

        .chat-status {
            font-size: 13px;
            opacity: 0.95;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 9999px;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .chat-close-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: background 0.15s;
        }

        .chat-close-btn:hover {
            background: rgba(255,255,255,0.15);
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #f9fafb;
        }

        /* Custom Scrollbar */
        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .chat-message {
            display: flex;
            gap: 8px;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message-content {
            max-width: 85%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.6;
        }

        .bot-message .message-content {
            background: white;
            color: #1f2937;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }

        .user-message {
            flex-direction: row-reverse;
        }

        .user-message .message-content {
            background: #3b82f6;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-input-area {
            padding: 16px;
            background: white;
            border-top: 1px solid #e5e7eb;
        }

        .chat-form {
            display: flex;
            gap: 10px;
        }

        .chat-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 9999px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            background: #f9fafb;
        }

        .chat-input:focus {
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .chat-send-btn {
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            background: #3b82f6;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }

        .chat-send-btn:hover {
            background: #2563eb;
            transform: scale(1.05);
        }

        .chat-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .chat-typing {
            display: flex;
            gap: 4px;
            padding: 12px 16px;
            align-items: center;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: #9ca3af;
            border-radius: 9999px;
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-8px); }
        }

        /* Mobile Responsive */
        @media (max-width: 480px) {
            .chat-box {
                width: 100vw;
                max-width: 100vw;
                height: 100vh;
                max-height: 100vh;
                bottom: 0;
                right: 0;
                border-radius: 0;
                border: none;
                position: fixed;
            }

            #chat-widget-container {
                bottom: 16px;
                right: 16px;
            }
        }
    </style>

    <script>
        // AI Chat Widget JavaScript
        (function() {
            const API_URL = '{{ url('/') }}';
            let sessionId = localStorage.getItem('chat_session_id');
            if (!sessionId) {
                sessionId = 'chat_' + Math.random().toString(36).substring(2, 15);
                localStorage.setItem('chat_session_id', sessionId);
            }

            const chatToggle = document.getElementById('chat-toggle-btn');
            const chatBox = document.getElementById('chat-box');
            const chatClose = document.getElementById('chat-close-btn');
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const chatMessages = document.getElementById('chat-messages');

            let isOpen = false;
            let isTyping = false;

            // Load chat settings
            async function loadChatSettings() {
                try {
                    const response = await fetch(`${API_URL}/api/chat/settings`);
                    const settings = await response.json();

                    if (settings.enabled === false) {
                        document.getElementById('chat-widget-container').style.display = 'none';
                        return;
                    }

                    if (settings.widget_title) {
                        document.getElementById('chat-widget-title').textContent = settings.widget_title;
                    }
                    if (settings.welcome_message) {
                        document.getElementById('chat-welcome-message').textContent = settings.welcome_message;
                    }
                } catch (e) {
                    console.error('Failed to load chat settings:', e);
                }
            }

            // Load chat history
            async function loadChatHistory() {
                try {
                    const response = await fetch(`${API_URL}/api/chat/history?session_id=${sessionId}`);
                    const data = await response.json();

                    if (data.messages && data.messages.length > 0) {
                        // Clear welcome message
                        chatMessages.innerHTML = '';

                        data.messages.forEach(msg => {
                            addMessage(msg.message, 'user');
                            if (msg.response) {
                                addMessage(msg.response, 'bot');
                            }
                        });

                        scrollToBottom();
                    }
                } catch (e) {
                    console.error('Failed to load chat history:', e);
                }
            }

            function toggleChat() {
                isOpen = !isOpen;
                chatBox.style.display = isOpen ? 'flex' : 'none';

                if (isOpen) {
                    chatInput.focus();
                    scrollToBottom();
                }
            }

            function addMessage(text, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chat-message ${sender}-message`;
                messageDiv.innerHTML = `<div class="message-content"><p>${escapeHtml(text)}</p></div>`;
                chatMessages.appendChild(messageDiv);
                scrollToBottom();

                // Re-init lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            function addTypingIndicator() {
                const typingDiv = document.createElement('div');
                typingDiv.className = 'chat-message bot-message typing-indicator';
                typingDiv.innerHTML = `
                    <div class="message-content chat-typing">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                `;
                chatMessages.appendChild(typingDiv);
                scrollToBottom();
                return typingDiv;
            }

            function removeTypingIndicator() {
                const indicator = chatMessages.querySelector('.typing-indicator');
                if (indicator) {
                    indicator.remove();
                }
            }

            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            async function sendMessage(message) {
                if (isTyping) return;

                addMessage(message, 'user');
                chatInput.value = '';
                isTyping = true;

                const typingIndicator = addTypingIndicator();

                try {
                    const response = await fetch(`${API_URL}/api/chat/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Session-ID': sessionId,
                        },
                        body: JSON.stringify({
                            message: message,
                            session_id: sessionId,
                        }),
                    });

                    removeTypingIndicator();

                    if (response.ok) {
                        const data = await response.json();
                        addMessage(data.message, 'bot');
                    } else {
                        addMessage('Maaf, terjadi kesalahan. Silakan coba lagi nanti.', 'bot');
                    }
                } catch (e) {
                    removeTypingIndicator();
                    addMessage('Maaf, terjadi kesalahan koneksi. Silakan periksa internet Anda.', 'bot');
                }

                isTyping = false;
            }

            // Event listeners
            chatToggle.addEventListener('click', toggleChat);
            chatClose.addEventListener('click', toggleChat);

            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const message = chatInput.value.trim();
                if (message) {
                    sendMessage(message);
                }
            });

            // Close chat when clicking outside
            document.addEventListener('click', (e) => {
                if (isOpen && !chatBox.contains(e.target) && !chatToggle.contains(e.target)) {
                    toggleChat();
                }
            });

            // Initialize
            loadChatSettings();
            loadChatHistory();
        })();
    </script>
</body>
</html>
