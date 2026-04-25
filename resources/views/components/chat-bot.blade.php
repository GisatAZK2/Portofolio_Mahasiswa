<button id="chatBotButton"
    class="fixed bottom-40 sm:bottom-40 right-4 sm:right-6 bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 dark:from-blue-600 dark:to-indigo-700 dark:hover:from-blue-700 dark:hover:to-indigo-800 text-white p-3 sm:p-3.5 rounded-2xl shadow-lg transition-all duration-300 z-[100] group focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 hover:scale-105"
    style="cursor: grab; user-select: none;">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 sm:w-6 sm:h-6 group-[.chat-open]:hidden" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5 hidden group-[.chat-open]:block" fill="none"
        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
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
    <button id="closeBubbleBtn" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
        <svg class="w-5 h-5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

<div id="chatWidget"
    class="fixed bottom-40 sm:bottom-40 right-4 sm:right-6 w-[calc(100vw-2rem)] max-w-[360px] sm:max-w-[380px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden z-[99] hidden transition-all duration-200 ease-out"
    style="transform-origin: bottom right; max-height: min(600px, 85vh); display: flex; flex-direction: column;">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 px-3 sm:px-4 py-2.5 sm:py-3 flex items-center justify-between flex-shrink-0">
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
        <button id="closeChatWidget" class="text-white/80 hover:text-white transition p-1 hover:bg-white/10 rounded-full">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div id="chatMessages"
        class="chat-messages flex-1 min-h-0 overflow-y-auto p-2.5 sm:p-3 space-y-2.5 sm:space-y-3 bg-gray-50 dark:bg-gray-800/50">
        <div class="flex items-start space-x-2">
            <div class="flex-shrink-0 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <div
                class="flex-1 bg-white dark:bg-gray-800 rounded-2xl rounded-tl-none px-2.5 sm:px-3 py-1.5 sm:py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                <p class="text-xs sm:text-sm text-gray-800 dark:text-gray-100">👋 Halo! Saya asisten Help Center POLMIND. Ada yang bisa saya bantu?</p>
                <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mt-1">💡 Pilih pertanyaan atau ketik pesan Anda</p>
            </div>
        </div>
    </div>

    <div id="suggestionsContainer" class="px-2.5 sm:px-3 py-1.5 sm:py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 hidden flex-shrink-0">
        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mb-1">💭 Mungkin yang Anda maksud:</p>
        <div id="suggestionsList" class="flex flex-wrap gap-1 sm:gap-1.5"></div>
    </div>

    <div id="quickQuestions" class="px-2.5 sm:px-3 py-1.5 sm:py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 flex-shrink-0">
        <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mb-1.5 sm:mb-2">⚡ Pertanyaan Hari Ini:</p>
        <div class="flex flex-wrap gap-1.5 sm:gap-2" id="quickQuestionsContainer"></div>
    </div>

    <div id="typingIndicator" class="hidden px-2.5 sm:px-3 py-1.5 sm:py-2 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
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
</div>

<style>
    .chat-messages::-webkit-scrollbar {
        width: 4px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .dark .chat-messages::-webkit-scrollbar-track {
        background: #1f2937;
    }

    .dark .chat-messages::-webkit-scrollbar-thumb {
        background: #4b5563;
    }

    .typing-dot {
        animation: typing-bounce 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) { animation-delay: 0s; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing-bounce {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
        30% { transform: translateY(-6px); opacity: 1; }
    }

    .message-slide-in {
        animation: slideIn 0.3s ease-out forwards;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .suggestion-item, .quick-question {
        transition: all 0.15s ease;
    }
    
    #chatWidget {
        display: flex !important;
        flex-direction: column !important;
    }
    
    #chatWidget.hidden {
        display: none !important;
    }

    /* Bubble animation */
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
    
    .animate-bounce-subtle {
        animation: bounce-subtle 1.2s infinite ease-in-out;
    }

    /* Dragging state */
    .dragging {
        cursor: grabbing !important;
        opacity: 0.8;
        transition: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatButton = document.getElementById('chatBotButton');
        const chatWidget = document.getElementById('chatWidget');
        const closeChatBtn = document.getElementById('closeChatWidget');
        const chatMessagesContainer = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendMessageBtn');
        const typingIndicator = document.getElementById('typingIndicator');
        const quickQuestionsContainer = document.getElementById('quickQuestionsContainer');
        const suggestionsContainer = document.getElementById('suggestionsContainer');
        const suggestionsList = document.getElementById('suggestionsList');
        
        // Bubble elements
        const notificationBubble = document.getElementById('notificationBubble');
        const closeBubbleBtn = document.getElementById('closeBubbleBtn');

        let isChatOpen = false;
        let isTyping = false;
        let suggestionTimeout = null;

        // ============ DRAG TO MOVE CHAT BUTTON ============
        let isDragging = false;
        let dragStartX, dragStartY;
        let buttonStartLeft, buttonStartTop;
        let dragDistance = 0;
        
        // Get saved position from localStorage
        function loadButtonPosition() {
            const savedPos = localStorage.getItem('chatButtonPosition');
            if (savedPos) {
                try {
                    const pos = JSON.parse(savedPos);
                    chatButton.style.left = pos.left;
                    chatButton.style.top = pos.top;
                    chatButton.style.right = 'auto';
                    chatButton.style.bottom = 'auto';
                } catch(e) {}
            }
        }
        
        function saveButtonPosition(left, top) {
            localStorage.setItem('chatButtonPosition', JSON.stringify({ left, top }));
        }
        
        function onMouseDown(e) {
            // Only start drag if not clicking on child elements that might interfere
            if (e.target.closest('svg') && !isChatOpen) {
                // Let the normal click happen if it's just an icon click
                return;
            }
            
            isDragging = false;
            dragDistance = 0;
            dragStartX = e.clientX;
            dragStartY = e.clientY;
            
            const rect = chatButton.getBoundingClientRect();
            buttonStartLeft = rect.left;
            buttonStartTop = rect.top;
            
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
            
            chatButton.style.cursor = 'grabbing';
            e.preventDefault();
        }
        
        function onMouseMove(e) {
            const dx = e.clientX - dragStartX;
            const dy = e.clientY - dragStartY;
            dragDistance = Math.sqrt(dx * dx + dy * dy);
            
            if (dragDistance > 5) {
                isDragging = true;
                chatButton.classList.add('dragging');
                
                let newLeft = buttonStartLeft + dx;
                let newTop = buttonStartTop + dy;
                
                // Boundary constraints
                const maxX = window.innerWidth - chatButton.offsetWidth - 16;
                const maxY = window.innerHeight - chatButton.offsetHeight - 16;
                newLeft = Math.min(Math.max(8, newLeft), maxX);
                newTop = Math.min(Math.max(8, newTop), maxY);
                
                chatButton.style.left = newLeft + 'px';
                chatButton.style.top = newTop + 'px';
                chatButton.style.right = 'auto';
                chatButton.style.bottom = 'auto';
            }
        }
        
        function onMouseUp(e) {
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
            chatButton.style.cursor = 'grab';
            chatButton.classList.remove('dragging');
            
            if (isDragging && dragDistance > 5) {
                // Save the new position
                saveButtonPosition(chatButton.style.left, chatButton.style.top);
                // Prevent click event
                e.stopPropagation();
            }
            
            isDragging = false;
            dragDistance = 0;
        }
        
        // Initialize drag functionality
        function initDrag() {
            loadButtonPosition();
            chatButton.style.cursor = 'grab';
            chatButton.addEventListener('mousedown', onMouseDown);
            
            // Also handle touch events for mobile
            chatButton.addEventListener('touchstart', onTouchStart, { passive: false });
            chatButton.addEventListener('touchmove', onTouchMove, { passive: false });
            chatButton.addEventListener('touchend', onTouchEnd);
        }
        
        function onTouchStart(e) {
            if (e.target.closest('svg') && !isChatOpen) return;
            
            isDragging = false;
            dragDistance = 0;
            const touch = e.touches[0];
            dragStartX = touch.clientX;
            dragStartY = touch.clientY;
            
            const rect = chatButton.getBoundingClientRect();
            buttonStartLeft = rect.left;
            buttonStartTop = rect.top;
            
            e.preventDefault();
        }
        
        function onTouchMove(e) {
            const touch = e.touches[0];
            const dx = touch.clientX - dragStartX;
            const dy = touch.clientY - dragStartY;
            dragDistance = Math.sqrt(dx * dx + dy * dy);
            
            if (dragDistance > 5) {
                isDragging = true;
                chatButton.classList.add('dragging');
                
                let newLeft = buttonStartLeft + dx;
                let newTop = buttonStartTop + dy;
                
                const maxX = window.innerWidth - chatButton.offsetWidth - 16;
                const maxY = window.innerHeight - chatButton.offsetHeight - 16;
                newLeft = Math.min(Math.max(8, newLeft), maxX);
                newTop = Math.min(Math.max(8, newTop), maxY);
                
                chatButton.style.left = newLeft + 'px';
                chatButton.style.top = newTop + 'px';
                chatButton.style.right = 'auto';
                chatButton.style.bottom = 'auto';
                
                e.preventDefault();
            }
        }
        
        function onTouchEnd(e) {
            chatButton.classList.remove('dragging');
            
            if (isDragging && dragDistance > 5) {
                saveButtonPosition(chatButton.style.left, chatButton.style.top);
                e.preventDefault();
            }
            
            isDragging = false;
            dragDistance = 0;
        }
        
        // ============ BUBBLE LOGIC (show only once) ============
        // Check if user has permanently dismissed the bubble
        let bubblePermanentlyHidden = localStorage.getItem('bubblePermanentlyHidden') === 'true';
        
        function startBubbleAutoHide() {
            if (window.bubbleAutoHideTimeout) clearTimeout(window.bubbleAutoHideTimeout);
            window.bubbleAutoHideTimeout = setTimeout(() => {
                if (notificationBubble && notificationBubble.style.display !== 'none') {
                    hideBubble();
                }
            }, 8000);
        }
        
        function hideBubble() {
            if (notificationBubble) {
                notificationBubble.style.display = 'none';
                if (window.bubbleAutoHideTimeout) clearTimeout(window.bubbleAutoHideTimeout);
            }
        }
        
        function permanentHideBubble() {
            bubblePermanentlyHidden = true;
            localStorage.setItem('bubblePermanentlyHidden', 'true');
            hideBubble();
        }
        
        function showBubble() {
            if (notificationBubble && !bubblePermanentlyHidden) {
                notificationBubble.style.display = 'flex';
                startBubbleAutoHide();
            }
        }
        
        function hideBubbleOnChatOpen() {
            if (notificationBubble) {
                hideBubble();
            }
        }
        
        // Close bubble manually (permanent)
        if (closeBubbleBtn) {
            closeBubbleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                permanentHideBubble();
            });
        }
        
        // Click bubble to open chat (but not permanent hide)
        if (notificationBubble) {
            notificationBubble.addEventListener('click', (e) => {
                if (e.target === closeBubbleBtn || closeBubbleBtn.contains(e.target)) {
                    return;
                }
                hideBubbleOnChatOpen();
                openChat();
            });
        }

        // ============ FAQ DATABASE (unchanged) ============
        const faqDatabase = {
            'apa website ini': 'Website ini adalah platform portofolio digital untuk mahasiswa POLMIND (Politeknik Mitra Indonesia). Mahasiswa dapat menampilkan proyek, sertifikat, dan keterampilan mereka kepada publik dan calon employer.',
            'apa itu website ini': 'Website ini adalah platform portofolio mahasiswa POLMIND. Di sini mahasiswa bisa memamerkan karya, proyek, dan sertifikat mereka.',
            'fungsi website': 'Website ini berfungsi sebagai platform portofolio digital untuk mahasiswa POLMIND, membantu mereka menampilkan kompetensi dan pengalaman kepada dunia profesional.',
            'tentang polmind': 'POLMIND (Politeknik Mitra Indonesia) adalah institusi pendidikan vokasi yang berlokasi di Kawasan Industri MM2100, Cikarang Barat. POLMIND fokus pada pendidikan berbasis industri dengan program Teaching Factory.',
            'apa itu polmind': 'POLMIND adalah Politeknik Mitra Indonesia, kampus vokasi yang mengedepankan pembelajaran berbasis industri dan Teaching Factory (TeFa).',
            'visi misi polmind': 'POLMIND memiliki visi menjadi politeknik unggul berbasis industri manufaktur. Misi: menyelenggarakan pendidikan vokasi berkualitas, mengembangkan penelitian terapan, dan membangun kemitraan dengan industri.',
            'sejarah polmind': 'POLMIND didirikan untuk memenuhi kebutuhan tenaga kerja terampil di sektor manufaktur Indonesia. Kampus berlokasi strategis di Kawasan Industri MM2100, pusat industri manufaktur nasional.',
            'program studi polmind': 'POLMIND memiliki program studi unggulan: Teknik Manufaktur, Teknik Elektronika, Teknik Informatika, Teknik Mesin, dan Teknik Industri. Semua prodi mengedepankan praktik industri.',
            'prodi polmind': 'Program studi di POLMIND: D3 Teknik Manufaktur, D3 Teknik Elektronika, D3 Teknik Informatika, D3 Teknik Mesin, D3 Teknik Industri, dan program Sarjana Terapan.',
            'jurusan polmind': 'Jurusan di POLMIND meliputi Teknik Manufaktur, Teknik Elektro, Teknik Informatika, dan Teknik Mesin dengan fokus pada industri 4.0.',
            'fasilitas polmind': 'Fasilitas POLMIND: Teaching Factory, Lab CNC, Lab Robotik, Lab IoT, Lab Komputer, Perpustakaan Digital, Ruang Kelas Smart, Workshop, dan area parkir luas.',
            'fasilitas kampus': 'Kampus POLMIND dilengkapi Teaching Factory, laboratorium modern, workshop, perpustakaan, dan fasilitas penunjang pembelajaran berbasis industri.',
            'beasiswa polmind': 'POLMIND menyediakan beasiswa: Beasiswa Prestasi Akademik, Beasiswa Tidak Mampu, Beasiswa Mitra Industri, dan Beasiswa Pemerintah (KIP Kuliah).',
            'beasiswa': 'Tersedia beasiswa prestasi, beasiswa tidak mampu, beasiswa mitra industri, dan KIP Kuliah di POLMIND.',
            'kerjasama industri polmind': 'POLMIND bekerjasama dengan ratusan perusahaan manufaktur nasional dan multinasional di Kawasan MM2100 dan sekitarnya untuk program magang, TeFa, dan penempatan kerja.',
            'mitra polmind': 'POLMIND bermitra dengan perusahaan-perusahaan di Kawasan Industri MM2100 seperti Astra, Toyota, Panasonic, dan lainnya.',
            'prestasi polmind': 'POLMIND telah meraih berbagai prestasi di tingkat nasional dalam kompetisi robotik, inovasi manufaktur, dan karya ilmiah mahasiswa.',
            'akreditasi polmind': 'Program studi di POLMIND terakreditasi BAN-PT dengan nilai Baik Sekali. POLMIND terus meningkatkan kualitas untuk mencapai akreditasi unggul.',
            'cara mendaftar': 'Untuk mendaftar: 1) Klik "Daftar" di halaman utama, 2) Isi formulir dengan data lengkap (nama, email, password), 3) Pilih role (Mahasiswa/Dosen), 4) Verifikasi email melalui link yang dikirim, 5) Login dengan akun Anda.',
            'pendaftaran': 'Proses pendaftaran: Klik Daftar > Isi formulir > Verifikasi email > Login. Pastikan menggunakan email aktif.',
            'register': 'Anda dapat mendaftar dengan mengklik tombol "Daftar" di halaman utama. Isi data diri dan pilih role yang sesuai.',
            'buat akun': 'Buat akun dengan klik "Daftar", isi nama, email, password, dan pilih role (Mahasiswa/Dosen). Verifikasi email untuk aktivasi.',
            'syarat pendaftaran': 'Syarat pendaftaran: Warga Negara Indonesia, lulusan SMA/SMK sederajat, memiliki email aktif, dan mengisi formulir dengan data benar.',
            'lupa password': 'Jika lupa password: 1) Klik "Lupa Password" di halaman login, 2) Masukkan email terdaftar, 3) Cek email untuk link reset, 4) Klik link dan buat password baru.',
            'reset password': 'Reset password melalui fitur "Lupa Password" di halaman login. Link reset akan dikirim ke email Anda.',
            'ganti password': 'Anda dapat mengganti password melalui "Lupa Password" di halaman login, atau di menu Pengaturan setelah login.',
            'cara mengubah profil': 'Untuk mengubah profil: 1) Login ke akun, 2) Buka menu "Profil" atau "Pengaturan", 3) Klik "Edit Profil", 4) Ubah foto, bio, atau informasi kontak, 5) Simpan perubahan.',
            'edit profil': 'Edit profil dapat dilakukan melalui menu Profil > Edit Profil. Anda bisa mengubah foto, bio, dan informasi kontak.',
            'foto profil': 'Foto profil dapat diubah di halaman Edit Profil. Upload foto dengan format JPG, PNG, atau GIF (maks 2MB).',
            'menghubungi admin': 'Hubungi admin melalui:\n📧 Email: info@polmind.ac.id\n📱 WhatsApp: +62 821-1329-6897\n📍 Alamat: Kawasan Industri MM2100, Cikarang Barat\n🕐 Jam operasional: Senin-Jumat, 08:00-16:00 WIB',
            'kontak admin': '📧 info@polmind.ac.id | 📱 +62 821-1329-6897',
            'email admin': 'Email admin: info@polmind.ac.id',
            'wa admin': 'WhatsApp admin: +62 821-1329-6897',
            'alamat kampus': 'Kampus POLMIND berlokasi di Kawasan Industri MM2100, Cikarang Barat, Bekasi, Jawa Barat.',
            'lokasi polmind': 'POLMIND terletak di Jalan Irian, Kawasan Industri MM2100, Cikarang Barat, Kabupaten Bekasi, Jawa Barat.',
            'apa itu portofolio': 'Portofolio adalah kumpulan karya, proyek, dan pencapaian yang menunjukkan kemampuan dan pengalaman seseorang. Di website ini, mahasiswa dapat membangun portofolio digital profesional.',
            'portofolio': 'Portofolio digital adalah showcase karya dan proyek Anda. Ini membantu calon employer melihat kompetensi Anda secara nyata.',
            'pentingnya portofolio': 'Portofolio penting karena: 1) Bukti nyata kemampuan, 2) Meningkatkan peluang karir, 3) Personal branding, 4) Membedakan Anda dari kandidat lain.',
            'cara menambahkan portofolio': 'Menambah portofolio: 1) Login ke akun, 2) Buka menu "Portofolio", 3) Pilih "Tambah Proyek" atau "Tambah Sertifikat", 4) Isi judul, deskripsi, tanggal, 5) Upload file pendukung, 6) Simpan.',
            'tambah portofolio': '1) Login > Portofolio > Tambah Proyek, 2) Isi informasi proyek, 3) Upload file, 4) Simpan.',
            'cara upload proyek': 'Buka menu Portofolio > Tambah Proyek. Isi nama proyek, deskripsi, link (opsional), dan upload thumbnail.',
            'cara menambah sertifikat': 'Menambah sertifikat: 1) Buka menu "Sertifikat", 2) Klik "Tambah Sertifikat", 3) Upload file sertifikat (PDF/JPG), 4) Isi nama sertifikat dan penerbit, 5) Simpan.',
            'upload sertifikat': 'Upload sertifikat melalui menu Sertifikat > Tambah Sertifikat. File yang didukung: PDF, JPG, PNG.',
            'apa itu tefa': 'Teaching Factory (TeFa) adalah model pembelajaran di POLMIND di mana mahasiswa mengerjakan proyek nyata dari industri. Mahasiswa mendapatkan pengalaman kerja aktual sambil belajar.',
            'tefa': 'Teaching Factory - sistem pembelajaran berbasis proyek industri nyata. Mahasiswa POLMIND belajar sambil mengerjakan proyek dari mitra industri.',
            'teaching factory': 'Teaching Factory adalah program unggulan POLMIND yang mengintegrasikan pembelajaran dengan proyek industri nyata.',
            'sistem tefa': 'Dalam sistem TeFa, mahasiswa mengerjakan proyek dari perusahaan mitra, mendapatkan pengalaman kerja, dan hasilnya masuk dalam portofolio.',
            'manfaat tefa': 'Manfaat TeFa: pengalaman industri nyata, portofolio profesional, jaringan dengan perusahaan, dan kesiapan kerja lebih tinggi.',
            'apa itu learning corner': 'Learning Corner adalah fitur di mana mahasiswa dapat berbagi materi pembelajaran, tutorial, atau artikel yang bermanfaat untuk mahasiswa lain.',
            'learning corner': 'Learning Corner adalah ruang berbagi pengetahuan antar mahasiswa. Anda bisa memposting tutorial, tips, atau materi pembelajaran.',
            'magang polmind': 'POLMIND memfasilitasi magang di perusahaan mitra di Kawasan Industri MM2100. Mahasiswa dapat mengajukan magang melalui program TeFa atau kerjasama industri.',
            'info magang': 'Informasi magang dapat diperoleh dari bagian kemahasiswaan atau dosen pembimbing. POLMIND memiliki banyak mitra industri untuk program magang.',
            'karir alumni': 'Alumni POLMIND banyak bekerja di perusahaan manufaktur nasional dan multinasional. Tersedia pusat karir yang membantu penyaluran kerja lulusan.',
            'kerja setelah lulus': 'Lulusan POLMIND memiliki tingkat terserap kerja tinggi (>90%) karena kompetensi yang sesuai kebutuhan industri.',
            'jam operasional': 'Jam operasional admin: Senin-Jumat, pukul 08:00 - 16:00 WIB. Sabtu, Minggu, dan hari libur nasional tutup.',
            'jam kerja': 'Admin tersedia Senin-Jumat, 08:00-16:00 WIB.',
            'help': 'Saya bisa membantu dengan pertanyaan seputar: POLMIND, Pendaftaran, Portofolio, Sertifikat, TeFa, Magang, Beasiswa, dan Kontak. Ada yang bisa saya bantu?',
            'bantuan': 'Saya asisten Help Center POLMIND. Saya bisa menjawab pertanyaan tentang kampus, pendaftaran, portofolio, dan lainnya. Silakan bertanya!',
            'halo': 'Halo! 👋 Ada yang bisa saya bantu? Silakan pilih pertanyaan di atas atau ketik pertanyaan Anda.',
            'hai': 'Hai! Selamat datang di Help Center POLMIND. Ada yang ingin ditanyakan?',
            'terima kasih': 'Sama-sama! Senang bisa membantu Anda 😊 Jika ada pertanyaan lain, jangan ragu untuk bertanya lagi ya!',
            'makasih': 'Sama-sama! Senang bisa membantu 😊',
            'bye': 'Sampai jumpa! 👋 Kembali lagi jika ada pertanyaan lain. Semoga harimu menyenangkan!',
        };

        const quickQuestionsPool = [
            { question: 'Apa itu POLMIND?', keywords: ['apa', 'polmind', 'politeknik', 'kampus'] },
            { question: 'Program studi apa saja?', keywords: ['prodi', 'program', 'studi', 'jurusan'] },
            { question: 'Cara mendaftar?', keywords: ['daftar', 'register', 'pendaftaran'] },
            { question: 'Apa itu TeFa?', keywords: ['tefa', 'teaching', 'factory'] },
            { question: 'Beasiswa POLMIND?', keywords: ['beasiswa', 'biaya', 'kuliah'] },
            { question: 'Lokasi kampus?', keywords: ['lokasi', 'alamat', 'dimana'] },
            { question: 'Cara menambah portofolio?', keywords: ['portofolio', 'tambah', 'upload', 'proyek'] },
            { question: 'Fasilitas POLMIND?', keywords: ['fasilitas', 'lab', 'kampus'] },
            { question: 'Magang di POLMIND?', keywords: ['magang', 'internship', 'kerja'] },
            { question: 'Kontak admin?', keywords: ['admin', 'kontak', 'hubungi', 'email', 'wa'] },
        ];

        function getDailyQuestions() {
            const today = new Date();
            const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / (1000 * 60 * 60 * 24));
            const shuffled = [...quickQuestionsPool];
            
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = (dayOfYear + i) % shuffled.length;
                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
            }
            
            return shuffled.slice(0, 4);
        }

        function renderQuickQuestions() {
            const dailyQuestions = getDailyQuestions();
            quickQuestionsContainer.innerHTML = '';
            
            dailyQuestions.forEach(item => {
                const btn = document.createElement('button');
                btn.className = 'quick-question text-[10px] sm:text-xs bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900/50 hover:text-blue-600 dark:hover:text-blue-400 transition-all';
                btn.textContent = item.question;
                quickQuestionsContainer.appendChild(btn);
            });
        }

        function findBestMatch(input) {
            const lowerInput = input.toLowerCase().trim();
            let bestMatch = null;
            let bestScore = 0;
            
            for (const [key, answer] of Object.entries(faqDatabase)) {
                const keywords = key.split(' ');
                let score = 0;
                
                for (const word of keywords) {
                    if (lowerInput.includes(word)) score += word.length;
                }
                
                if (lowerInput.includes(key)) score += key.length * 2;
                
                if (score > bestScore) {
                    bestScore = score;
                    bestMatch = answer;
                }
            }
            
            return bestMatch;
        }

        function getSuggestions(input) {
            const lowerInput = input.toLowerCase().trim();
            if (lowerInput.length < 2) return [];
            
            const suggestions = [];
            
            for (const item of quickQuestionsPool) {
                for (const keyword of item.keywords) {
                    if (lowerInput.includes(keyword) || keyword.includes(lowerInput)) {
                        if (!suggestions.includes(item.question)) {
                            suggestions.push(item.question);
                        }
                        break;
                    }
                }
            }
            
            for (const key of Object.keys(faqDatabase)) {
                if (key.includes(lowerInput) && !suggestions.includes(key)) {
                    suggestions.push(key);
                }
            }
            
            return suggestions.slice(0, 5);
        }

        function showSuggestions(suggestions) {
            if (suggestions.length === 0) {
                suggestionsContainer.classList.add('hidden');
                return;
            }
            
            suggestionsContainer.classList.remove('hidden');
            suggestionsList.innerHTML = '';
            
            suggestions.forEach(suggestion => {
                const btn = document.createElement('button');
                btn.className = 'suggestion-item text-[10px] sm:text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900/50 hover:text-blue-600 dark:hover:text-blue-400 transition-all';
                btn.textContent = suggestion.length > 25 ? suggestion.substring(0, 25) + '...' : suggestion;
                btn.addEventListener('click', () => {
                    const question = suggestion;
                    chatInput.value = '';
                    suggestionsContainer.classList.add('hidden');
                    handleSendMessage(question);
                });
                suggestionsList.appendChild(btn);
            });
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        function addMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex items-start space-x-2 ${isUser ? 'flex-row-reverse space-x-reverse' : ''} message-slide-in`;
            messageDiv.style.opacity = '0';

            if (isUser) {
                messageDiv.innerHTML = `
                    <div class="flex-shrink-0 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="flex-1 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl rounded-tr-none px-2.5 sm:px-3 py-1.5 sm:py-2 shadow-md">
                        <p class="text-xs sm:text-sm whitespace-pre-line">${escapeHtml(content)}</p>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="flex-shrink-0 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div class="flex-1 bg-white dark:bg-gray-800 rounded-2xl rounded-tl-none px-2.5 sm:px-3 py-1.5 sm:py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                        <p class="text-xs sm:text-sm text-gray-800 dark:text-gray-100 whitespace-pre-line">${escapeHtml(content)}</p>
                    </div>
                `;
            }

            chatMessagesContainer.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.style.opacity = '1';
            }, 10);
            
            chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;
            return messageDiv;
        }

        function findAnswer(question) {
            const lowerQuestion = question.toLowerCase().trim();
            
            for (const [key, answer] of Object.entries(faqDatabase)) {
                if (lowerQuestion.includes(key) || key.includes(lowerQuestion)) {
                    return answer;
                }
            }
            
            const bestMatch = findBestMatch(question);
            if (bestMatch) {
                return bestMatch;
            }
            
            return "Maaf, saya belum mengerti pertanyaan Anda. 😅\n\nCoba tanyakan hal seperti:\n• Apa itu POLMIND?\n• Program studi apa saja?\n• Cara mendaftar?\n• Apa itu TeFa?\n• Beasiswa POLMIND?\n• Lokasi kampus?\n\nAtau pilih pertanyaan di atas! 👆";
        }

        function sendBotReply(userText) {
            if (isTyping) return;
            
            isTyping = true;
            typingIndicator.classList.remove('hidden');
            chatMessagesContainer.scrollTop = chatMessagesContainer.scrollHeight;

            setTimeout(() => {
                typingIndicator.classList.add('hidden');
                const replyText = findAnswer(userText);
                addMessage(replyText, false);
                isTyping = false;
            }, 500 + Math.random() * 400);
        }

        function handleSendMessage(messageText = null) {
            const message = messageText || chatInput.value.trim();
            if (!message) return;

            addMessage(message, true);
            
            if (!messageText) {
                chatInput.value = '';
                suggestionsContainer.classList.add('hidden');
                chatInput.focus();
            }

            sendBotReply(message);
        }

        if (sendBtn) {
            sendBtn.addEventListener('click', () => handleSendMessage());
        }

        if (chatInput) {
            chatInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    handleSendMessage();
                }
            });
            
            chatInput.addEventListener('input', (e) => {
                clearTimeout(suggestionTimeout);
                suggestionTimeout = setTimeout(() => {
                    const suggestions = getSuggestions(e.target.value);
                    showSuggestions(suggestions);
                }, 200);
            });
            
            chatInput.addEventListener('blur', () => {
                setTimeout(() => suggestionsContainer.classList.add('hidden'), 200);
            });
            
            chatInput.addEventListener('focus', () => {
                const suggestions = getSuggestions(chatInput.value);
                if (suggestions.length > 0) {
                    showSuggestions(suggestions);
                }
            });
        }

        if (quickQuestionsContainer) {
            quickQuestionsContainer.addEventListener('click', (e) => {
                const questionBtn = e.target.closest('.quick-question');
                if (questionBtn) {
                    const question = questionBtn.textContent.trim();
                    handleSendMessage(question);
                }
            });
        }

        function openChat() {
            if (!chatWidget) return;
            chatWidget.classList.remove('hidden');
            isChatOpen = true;
            chatButton.classList.add('chat-open');
            setTimeout(() => chatInput.focus(), 100);
        }

        function closeChat() {
            if (!chatWidget) return;
            chatWidget.classList.add('hidden');
            isChatOpen = false;
            chatButton.classList.remove('chat-open');
        }

        // Modified click handler to handle drag vs click
        let originalClickHandler = null;
        
        if (chatButton) {
            // Store original click handler
            originalClickHandler = (e) => {
                if (isDragging && dragDistance > 5) {
                    e.stopPropagation();
                    return;
                }
                if (isChatOpen) {
                    closeChat();
                } else {
                    hideBubbleOnChatOpen();
                    openChat();
                }
            };
            
            chatButton.addEventListener('click', (e) => {
                if (isDragging && dragDistance > 5) {
                    e.stopPropagation();
                    return;
                }
                if (isChatOpen) {
                    closeChat();
                } else {
                    hideBubbleOnChatOpen();
                    openChat();
                }
            });
        }

        if (closeChatBtn) {
            closeChatBtn.addEventListener('click', closeChat);
        }

        document.addEventListener('click', (e) => {
            if (isChatOpen && chatWidget && chatButton) {
                if (!chatWidget.contains(e.target) && !chatButton.contains(e.target)) {
                    closeChat();
                }
            }
        });

        if (chatWidget) {
            chatWidget.addEventListener('click', (e) => e.stopPropagation());
        }

        renderQuickQuestions();
        
        // Initialize drag functionality
        initDrag();
        
        if (!bubblePermanentlyHidden) {
            showBubble();
        }
    });
</script>