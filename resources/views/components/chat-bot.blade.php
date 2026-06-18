<button id="chatBotButton"
    class="fixed bottom-50 sm:bottom-50 right-4 sm:right-6 bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 dark:from-blue-600 dark:to-indigo-700 dark:hover:from-blue-700 dark:hover:to-indigo-800 text-white p-1 sm:p-1 rounded-2xl shadow-lg transition-all duration-300 z-[100] group focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 hover:scale-105"
    style="cursor: grab; user-select: none;">
    <img src="{{ asset('assets/Logo.svg') }}" class="w-10 h-10 sm:w-8 sm:h-8 group-[.chat-open]:hidden rounded-3xl">
    <img src="{{ asset('assets/Logo.svg') }}" class="w-9 h-9 sm:w-7 sm:h-7 hidden group-[.chat-open]:block rounded-3xl">
</button>

<!-- BUBBLE NOTIFICATION -->
<div id="notificationBubble"
    class="fixed bottom-55 sm:bottom-55 right-4 sm:right-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-2.5 sm:p-3 z-[101] flex items-center gap-2 cursor-pointer transition-all duration-300 animate-bounce-subtle"
    style="max-width: 280px; transform-origin: bottom right;">
    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center">
        <img src="{{ asset('assets/Logo.svg') }}" alt="POLMIND Logo" class="w-6 h-6 sm:w-5 sm:h-5 rounded-full">
    </div>
    <div class="flex-1">
        <p class="text-sm font-semibold text-gray-800 dark:text-white">Ada yang ingin ditanyakan?</p>
    </div>
    <button id="closeBubbleBtn"
        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
        <svg class="w-5 h-5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<div id="chatWidget"
    class="fixed bottom-40 sm:bottom-40 right-4 sm:right-6 w-[calc(100vw-2rem)] max-w-[360px] sm:max-w-[380px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden z-[99] hidden transition-all duration-200 ease-out"
    style="transform-origin: bottom right; max-height: min(600px, 85vh); display: flex; flex-direction: column;">

    <div
        class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 px-3 sm:px-4 py-2.5 sm:py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-2">
            <div class="bg-white/20 p-1 sm:p-1.5 rounded-full">
                <img src="{{ asset('assets/Logo.svg') }}" alt="POLMIND Logo" class="w-5 h-5 sm:w-6 sm:h-6 rounded-full">
            </div>
            <div>
                <h3 class="text-white font-semibold text-xs sm:text-sm">POLMIND</h3>
                <p class="text-white/70 text-[10px] sm:text-xs flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                    Online
                </p>
            </div>
        </div>
        <button id="closeChatWidget"
            class="text-white/80 hover:text-white transition p-1 hover:bg-white/10 rounded-full">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div id="chatMessages"
        class="chat-messages flex-1 min-h-0 overflow-y-auto p-2.5 sm:p-3 space-y-2.5 sm:space-y-3 bg-gray-50 dark:bg-gray-800/50">
        <div class="flex items-start space-x-2">
            <div
                class="flex-shrink-0 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600 dark:text-blue-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div
                class="flex-1 bg-white dark:bg-gray-800 rounded-2xl rounded-tl-none px-2.5 sm:px-3 py-1.5 sm:py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                <p class="text-xs sm:text-sm text-gray-800 dark:text-gray-100">👋 Halo! Saya asisten Help Center
                    POLMIND. Ada yang bisa saya bantu?</p>
                <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">Pilih pertanyaan atau ketik
                    pesan Anda</p>
            </div>
        </div>
    </div>

    <div id="suggestionsContainer"
        class="px-2.5 sm:px-3 py-1.5 sm:py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 hidden flex-shrink-0">
        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mb-1">💭 Mungkin yang Anda maksud:</p>
        <div id="suggestionsList" class="flex flex-wrap gap-1 sm:gap-1.5"></div>
    </div>

    <div id="quickQuestions"
        class="px-2.5 sm:px-3 py-1.5 sm:py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 flex-shrink-0">
        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mb-1.5 sm:mb-2">Pertanyaan Hari Ini:</p>
        <div class="flex flex-wrap gap-1.5 sm:gap-2" id="quickQuestionsContainer"></div>
    </div>

    <div id="typingIndicator"
        class="hidden px-2.5 sm:px-3 py-1.5 sm:py-2 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
        <div class="flex items-center space-x-1.5">
            <div class="flex space-x-1">
                <span class="typing-dot w-1.5 h-1.5 sm:w-2 sm:h-2 bg-blue-500 rounded-full"></span>
                <span class="typing-dot w-1.5 h-1.5 sm:w-2 sm:h-2 bg-blue-500 rounded-full"></span>
                <span class="typing-dot w-1.5 h-1.5 sm:w-2 sm:h-2 bg-blue-500 rounded-full"></span>
            </div>
            <span class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">Bot sedang mengetik...</span>
        </div>
    </div>

    <div class="border-t border-gray-200 dark:border-gray-700 p-2.5 sm:p-3 bg-white dark:bg-gray-900 flex-shrink-0">
        <div class="flex items-center space-x-2">
            <input type="text" id="chatInput" placeholder="Ketik pesan Anda..."
                class="flex-1 px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm bg-gray-100 dark:bg-gray-800 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none text-gray-800 dark:text-white placeholder:text-gray-400"
                autocomplete="off">
            <button id="sendMessageBtn"
                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white p-1.5 sm:p-2 rounded-full transition-colors flex-shrink-0">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </div>
    </div>
        showBubble();
        }, 1000);
    });
</script>