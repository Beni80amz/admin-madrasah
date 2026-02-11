<div>
    {{-- Floating Button --}}
    {{-- Floating Button Area --}}
    {{-- Floating Button Area --}}
    <div class="!fixed !z-[999999] flex items-center gap-3"
        style="bottom: 2rem !important; right: 6rem !important; left: auto !important; position: fixed !important; direction: ltr !important;">
        {{-- Label --}}
        <div class="hidden md:flex">
            <span
                class="bg-white dark:bg-slate-800 text-teal-600 dark:text-teal-400 text-[12px] px-4 py-2 rounded-2xl font-bold shadow-2xl border-2 border-teal-600/20 dark:border-teal-400/20 whitespace-nowrap">
                AI Madrasah
            </span>
        </div>

        <button wire:click="toggleChat"
            class="flex h-12 w-12 items-center justify-center rounded-full bg-teal-600 text-white shadow-2xl hover:bg-teal-700 hover:scale-110 active:scale-95 transition-all focus:outline-none ring-4 ring-white dark:ring-slate-800"
            aria-label="Tanya AI">
            @if($isOpen)
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.456-2.454L18 2.25l.259 1.035a3.375 3.375 0 002.454 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.454zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                </svg>
            @endif
        </button>
    </div>

    {{-- Chat Panel --}}
    <div x-data="{ isOpen: @entangle('isOpen') }" x-show="isOpen"
        x-transition:enter="transition ease-out duration-300 origin-bottom-right"
        x-transition:enter-start="opacity-0 translate-y-full scale-50"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 origin-bottom-right"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-full scale-50"
        class="!fixed !z-[999999] bg-white dark:bg-slate-900 shadow-[0_20px_60px_rgba(0,0,0,0.5)] rounded-[2rem] flex flex-col overflow-hidden border border-gray-100 dark:border-slate-800"
        style="display: none; bottom: 6.5rem !important; right: 2rem !important; left: auto !important; position: fixed !important; width: 92vw; max-width: 440px; height: 75vh; max-height: 650px;">
        {{-- Header --}}
        <div class="bg-teal-600 p-4 text-white flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-sm">AI Madrasah</h3>
                    <div class="flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-white animate-pulse"></span>
                        <span class="text-[10px] opacity-90">Online & Siap Membantu</span>
                    </div>
                </div>
            </div>
            <button wire:click="clearChat" class="p-2 hover:bg-white/10 rounded-full transition-colors"
                title="Bersihkan Chat">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </button>
        </div>

        {{-- Messages Area --}}
        <div id="chat-messages"
            class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-slate-900/50 scroll-smooth" x-init="
                $el.scrollTop = $el.scrollHeight;
                window.addEventListener('scroll-chat', () => {
                    setTimeout(() => { $el.scrollTop = $el.scrollHeight; }, 100);
                });
            ">
            @foreach ($messages as $index => $message)
                <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }} animate-fadeIn">
                    <div class="max-w-[85%] flex flex-col {{ $message['role'] === 'user' ? 'items-end' : 'items-start' }}">
                        <div
                            class="p-3 rounded-2xl text-sm shadow-sm {{ $message['role'] === 'user' ? 'bg-teal-600 text-white rounded-tr-none' : 'bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-200 border border-gray-100 dark:border-slate-700 rounded-tl-none' }}">
                            <div class="markdown-content text-gray-800 dark:text-gray-200">
                                {!! Str::markdown($message['content']) !!}
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400 mt-1 px-1">
                            {{ $message['role'] === 'user' ? 'Anda' : 'AI Madrasah' }}
                        </span>
                    </div>
                </div>
            @endforeach

            @if ($isLoading)
                <div class="flex justify-start">
                    <div
                        class="bg-white dark:bg-slate-800 p-3 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 rounded-tl-none">
                        <div class="flex gap-1">
                            <span class="size-1.5 bg-gray-400 rounded-full animate-bounce"></span>
                            <span class="size-1.5 bg-gray-400 rounded-full animate-bounce"
                                style="animation-delay: 0.2s"></span>
                            <span class="size-1.5 bg-gray-400 rounded-full animate-bounce"
                                style="animation-delay: 0.4s"></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Input Area --}}
        <div class="p-4 bg-white dark:bg-slate-900 border-t border-gray-100 dark:border-slate-800">
            <form wire:submit.prevent="sendMessage" class="relative">
                <textarea wire:model="userInput" placeholder="Tanya sesuatu..."
                    class="w-full pl-4 pr-12 py-3 bg-gray-100 dark:bg-slate-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary focus:bg-white dark:focus:bg-slate-700 transition-all resize-none dark:text-gray-200"
                    rows="1" x-on:keydown.enter.prevent="$wire.sendMessage()"></textarea>
                <button type="submit"
                    class="absolute right-2 top-1.5 p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>
            <p class="text-[9px] text-center text-gray-400 mt-2">
                Asisten AI dapat membuat kesalahan. Harap periksa kembali informasi penting.
            </p>
        </div>
    </div>

    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [x-cloak] {
            display: none !important;
        }

        /* Markdown Styling */
        .markdown-content h1,
        .markdown-content h2,
        .markdown-content h3 {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .markdown-content ul {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .markdown-content ol {
            list-style-type: decimal;
            padding-left: 1.25rem;
            margin-bottom: 0.5rem;
        }

        .markdown-content table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 0.5rem;
            font-size: 0.75rem;
        }

        .markdown-content th,
        .markdown-content td {
            border: 1px solid #e5e7eb;
            padding: 0.25rem 0.5rem;
        }

        .dark .markdown-content th,
        .dark .markdown-content td {
            border-color: #334155;
        }

        .markdown-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin: 0.5rem 0;
        }

        .markdown-content a {
            color: #10b981;
            text-decoration: underline;
        }

        .markdown-content p {
            margin-bottom: 0.5rem;
        }

        .markdown-content p:last-child {
            margin-bottom: 0;
        }
    </style>
</div>