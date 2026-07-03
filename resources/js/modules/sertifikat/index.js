/**
 * modules/sertifikat/index.js
 * Sertifikat pages: list, create, edit, filter/search.
 */

/* ==========================================
   COMPONENT: CHAT BOT
   ========================================== */
document.addEventListener('DOMContentLoaded', function () {
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

    if (!chatButton) return; // Only run if chatbot elements exist on current page

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
            } catch (e) { }
        }
    }

    function saveButtonPosition(left, top) {
        localStorage.setItem('chatButtonPosition', JSON.stringify({ left, top }));
    }

    function onMouseDown(e) {
        if (e.target.closest('svg') && !isChatOpen) {
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
            saveButtonPosition(chatButton.style.left, chatButton.style.top);
            e.stopPropagation();
        }

        isDragging = false;
        dragDistance = 0;
    }

    function initDrag() {
        loadButtonPosition();
        chatButton.style.cursor = 'grab';
        chatButton.addEventListener('mousedown', onMouseDown);
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
        } else {
            if (isChatOpen) {
                closeChat();
            } else {
                if (notificationBubble) {
                    notificationBubble.style.display = 'none';
                }
                openChat();
            }
        }

        isDragging = false;
        dragDistance = 0;
    }

    // ============ BUBBLE LOGIC - PERMANENT HIDE ============
    let bubblePermanentlyClosed = localStorage.getItem('bubblePermanentlyClosed') === 'true';

    function permanentlyCloseBubble() {
        localStorage.setItem('bubblePermanentlyClosed', 'true');
        bubblePermanentlyClosed = true;

        if (notificationBubble) {
            notificationBubble.style.display = 'none';
            notificationBubble.style.visibility = 'hidden';
        }
    }

    function dismissChatPermanently() {
        permanentlyCloseBubble();
        localStorage.setItem('chatWidgetDismissed', 'true');
    }

    function showBubble() {
        const chatWidgetDismissed = localStorage.getItem('chatWidgetDismissed') === 'true';
        if (bubblePermanentlyClosed || chatWidgetDismissed) {
            if (notificationBubble) {
                notificationBubble.style.display = 'none';
                notificationBubble.style.visibility = 'hidden';
            }
            return;
        }

        if (notificationBubble) {
            notificationBubble.style.display = 'flex';
            notificationBubble.style.visibility = 'visible';

            setTimeout(() => {
                if (notificationBubble && notificationBubble.style.display === 'flex') {
                    notificationBubble.style.display = 'none';
                }
            }, 8000);
        }
    }

    if (closeBubbleBtn) {
        closeBubbleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            e.preventDefault();
            dismissChatPermanently();
        });
    }

    if (notificationBubble) {
        notificationBubble.addEventListener('click', function (e) {
            if (e.target === closeBubbleBtn || closeBubbleBtn.contains(e.target)) {
                return;
            }

            if (notificationBubble) {
                notificationBubble.style.display = 'none';
            }

            openChat();
        });
    }

    // ============ FAQ DATABASE ============
    const faqDatabase = {
        'apa website ini': 'Website ini adalah platform portofolio digital untuk mahasiswa POLMIND (Politeknik Mitra Indonesia). Mahasiswa dapat menampilkan proyek, sertifikat, dan keterampilan mereka kepada publik dan calon employer.',
        'apa itu website ini': 'Website ini adalah platform portofolio mahasiswa POLMIND. Di sini mahasiswa bisa memamerkan karya, proyek, dan sertifikat mereka.',
        'fungsi website': 'Website ini berfungsi sebagai platform portofolio digital untuk mahasiswa POLMIND, helping them tampilkan kompetensi dan pengalaman kepada dunia profesional.',
        'tentang polmind': 'POLMIND (Politeknik Mitra Indonesia) adalah institusi pendidikan vokasi yang berlokasi di Kawasan Industri MM2100, Cikarang Barat. POLMIND fokus pada pendidikan berbasis industri dengan program Teaching Factory.',
        'apa itu polmind': 'POLMIND adalah Politeknik Mitra Indonesia, kampus vokasi yang mengedepankan pembelajaran berbasis industri dan Teaching Factory (TeFa).',
        'visi misi polmind': 'POLMIND memiliki visi menjadi politeknik unggul berbasis industri manufaktur. Misi: menyelenggarakan pendidikan vokasi berkualitas, mengembangkan penelitian terapan, dan membangun kemitraan dengan industri.',
        'sejarah polmind': 'POLMIND didirikan untuk memenuhi kebutuhan tenaga kerja terampil di sektor manufaktur Indonesia. Kampus berlokasi strategis di Kawasan Industri MM2100, pusat industri manufaktur nasional.',
        'program studi polmind': 'POLMIND memiliki program studi unggulan: Teknologi Rekayasa Perangkat Lunak,Teknologi Rekayasa Manufaktur, Bisnis Digital.',
        'prodi polmind': 'Program studi di POLMIND: D4 Teknologi Rekayasa Perangkat Lunak, D4 Teknologi Rekayasa Manufaktur, D4 Bisnis Digital.',
        'fasilitas polmind': 'Fasilitas POLMIND: Teaching Factory, Lab Komputer, Perpustakaan Ruang Kelas dan area parkir luas.',
        'fasilitas kampus': 'Kampus POLMIND dilengkapi Teaching Factory, Lab Komputer, Perpustakaan Ruang Kelas dan area parkir luas.',
        'beasiswa polmind': 'POLMIND menyediakan beasiswa: Beasiswa Prestasi Akademik, Beasiswa Tidak Mampu, Beasiswa Mitra Industri, dan Beasiswa Pemerintah (KIP Kuliah).',
        'beasiswa': 'Tersedia beasiswa prestasi, beasiswa tidak mampu, beasiswa mitra industri, dan KIP Kuliah di POLMIND.',
        'kerjasama industri polmind': 'POLMIND bekerjasama dengan ratusan perusahaan manufaktur nasional dan multinasional di Kawasan MM2100 dan sekitarnya untuk program magang, TeFa, dan penempatan kerja.',
        'mitra polmind': 'POLMIND bermitra dengan perusahaan-perusahaan di Kawasan Industri MM2100 seperti Daihatsu, Epson, Denso dan lainnya',
        'prestasi polmind': 'POLMIND telah meraih berbagai prestasi di tingkat nasional dalam kompetisi robotik, inovasi manufaktur, dan karya ilmiah mahasiswa.',
        'cara mendaftar': 'Untuk mendaftar: 1) Klik "Login" di halaman utama, 2) Klik Ajukan Akun di halaman Login), 3) Isi formulir dengan data lengkap (nim, nama, email, password), 4). Mengajukan Akun Ke Admin, Tunggu Admin Menerima Pengajuan Akun 5) Login dengan akun Anda yang telah di setujui oleh Admin.',
        'pendaftaran': 'Proses pendaftaran: Klik Daftar > Isi formulir > Ajukan akun ke Admin > Login.',
        'register': 'Anda dapat mendaftar dengan mengklik tombol "Daftar" di halaman Login. Isi data diri anda.',
        'buat akun': 'Buat akun dengan klik "Daftar", isi nim, nama, email, password, dan ajukan akun ke admin dengan klik mendaftar.',
        'syarat pendaftaran': 'Syarat pendaftaran: Warga Negara Indonesia, lulusan SMA/SMK sederajat, memiliki email aktif, dan mengisi formulir dengan data benar.',
        'lupa password': 'Jika lupa password: 1) Klik "Lupa Password" di halaman login, 2) Masukkan email terdaftar, 3) Cek email untuk melihat kode OTP, 4) Masukkan kode OTP dan buat password baru.',
        'reset password': 'Reset password melalui fitur "Lupa Password" di halaman login. Kode OTP akan dikirim ke email Anda.',
        'ganti password': 'Anda dapat mengganti password melalui "Lupa Password" di halaman login',
        'cara mengubah profil': 'Untuk mengubah profil: 1) Login ke akun, 2) Buka menu "Profil" , 3) Akan Ada Sebuah Halaman Profil, Klik salah satu Yang ingin Di Ubah, 4) Ubah foto, bio, atau informasi kontak, 5) Simpan perubahan.',
        'edit profil': 'Edit profil dapat dilakukan melalui menu Profil > Edit Profil. Anda bisa mengubah foto, bio, dan informasi kontak.',
        'foto profil': 'Foto profil dapat diubah di halaman Edit Profil. Upload foto dengan format JPG, PNG, atau GIF (maks 5MB).',
        'menghubungi admin': 'Hubungi admin melalui:\n📧 Email: info@polmind.ac.id\n📱 WhatsApp: +62 821-1329-6897\n📍 Alamat: Kawasan Industri MM2100, Cikarang Barat\n🕐 Jam operasional: Senin-Jumat, 08:00-16:00 WIB',
        'kontak admin': '📧 info@polmind.ac.id | 📱 +62 821-1329-6897',
        'email admin': 'Email admin: info@polmind.ac.id',
        'wa admin': 'WhatsApp admin: +62 821-1329-6897',
        'alamat kampus': 'Kampus POLMIND berlokasi di Kawasan Industri MM2100, Cikarang Barat, Bekasi, Jawa Barat.',
        'lokasi polmind': 'POLMIND terletak di Jalan Kalimantan Blok CB-2, Kawasan Industri MM2100, Cikarang Barat, Kabupaten Bekasi, Jawa Barat.',
        'apa itu portofolio': 'Portofolio adalah kumpulan karya, proyek, dan pencapaian yang menunjukkan kemampuan dan pengalaman seseorang. Di website ini, mahasiswa dapat membangun portofolio digital profesional.',
        'portofolio': 'Portofolio digital adalah showcase karya dan proyek Anda. Ini membantu calon employer melihat kompetensi Anda secara nyata.',
        'pentingnya portofolio': 'Portofolio penting karena: 1) Bukti nyata kemampuan, 2) Meningkatkan peluang karir, 3) Personal branding, 4) Membedakan Anda dari kandidat lain.',
        'cara menambahkan portofolio': 'Menambah portofolio: 1) Login ke akun, 2) Buka menu "Portofolio", 3) Pilih "Tambah Proyek" atau "Tambah Sertifikat", 4) Isi judul, deskripsi, tanggal, 5) Upload file pendukung, 6) Simpan.',
        'tambah portofolio': '1) Login > Portofolio > Tambah Proyek, 2) Isi informasi proyek, 3) Upload file, 4) Simpan.',
        'cara upload proyek': 'Buka menu Portofolio > Tambah Proyek. Isi nama proyek, deskripsi, link (opsional), dan Mahasiswa Lain Yang ikut terlibat, Kemudian Upload Proyek',
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
        'jam operasional': 'Jam operasional admin: Senin-Jumat, pukul 08:00 - 17:00 WIB. Minggu, dan hari libur nasional tutup.',
        'jam kerja': 'Admin tersedia Senin-Sabtu, 08:00-17:00 WIB.',
        'help': 'Saya bisa membantu dengan pertanyaan seputar: POLMIND, Pendaftaran, Portofolio, Sertifikat, TeFa, Magang, Beasiswa, dan Kontak. Ada yang bisa saya bantu?',
        'bantuan': 'Saya asisten Help Center POLMIND. Saya bisa menjawab pertanyaan tentang kampus, pendaftaran, portofolio, dan lainnya. Silakan bertanya!',
        'halo': 'Halo! 👋 Ada yang bisa saya bantu? Silakan pilih pertanyaan di atas atau ketik pertanyaan Anda.',
        'hai': 'Hai! Selamat datang di Help Center POLMIND. Ada yang ingin ditanyakan?',
        'terima kasih': 'Sama-sama! Senang bisa membantu Anda 😊 Jika ada pertanyaan lain, jangan ragu untuk bertanya lagi ya!',
        'makasih': 'Sama-sama! Senang bisa membantu 😊',
        'bye': 'Sampai jumpa! 👋 Kembali lagi jika ada pertanyaan lain. Semoga harimu menyenangkan!',
        'dimana': 'Polmind Berada Di MM2100, No. S85, Vasanta Innopark'
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
        { question: 'Dimana Lokasinya?', keywords: ['lokasi', 'letak', 'dimana'] },
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
        return str.replace(/[&<>]/g, function (m) {
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
        dismissChatPermanently();
    }

    if (chatButton) {
        chatButton.addEventListener('click', (e) => {
            if ('ontouchstart' in window) return;
            if (isDragging && dragDistance > 5) {
                e.stopPropagation();
                return;
            }
            if (isChatOpen) {
                closeChat();
            } else {
                if (notificationBubble) {
                    notificationBubble.style.display = 'none';
                }
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
    initDrag();

    setTimeout(() => {
        showBubble();
    }, 1000);
});

/* ==========================================
   COMPONENT: LAYOUT
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-sidebar');
    const hamburger = document.getElementById('sidebar-hamburger');
    const closeIcon = document.getElementById('sidebar-close');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const closeSidebarBtn = document.getElementById('close-sidebar');
    const toggleSearch = document.getElementById('toggle-search-mobile');
    const searchDrop = document.getElementById('mobile-search-dropdown');
    const toggleDesktopBtn = document.getElementById('toggle-desktop-sidebar');
    const toggleIcon = document.getElementById('toggleCollapseIcon');

    if (toggleDesktopBtn) {
        toggleDesktopBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.contains('lg:w-20');
            if (isCollapsed) {
                sidebar.classList.remove('lg:w-20');
                sidebar.classList.add('lg:w-62');
                toggleIcon.classList.remove('rotate-180');
                fetch('/toggle-sidebar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({ collapsed: false })
                });
            } else {
                sidebar.classList.remove('lg:w-62');
                sidebar.classList.add('lg:w-20');
                toggleIcon.classList.add('rotate-180');
                document.querySelectorAll('[x-data]').forEach(el => {
                    if (el.__x) el.__x.$data.open = false;
                });
                fetch('/toggle-sidebar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({ collapsed: true })
                });
            }
        });
    }

    if (toggleSearch && searchDrop) {
        toggleSearch.addEventListener('click', () => {
            const isHidden = searchDrop.classList.contains('hidden');
            if (isHidden) {
                searchDrop.classList.remove('hidden');
                searchDrop.style.maxHeight = '0px';
                searchDrop.style.opacity = '0';
                requestAnimationFrame(() => {
                    searchDrop.style.transition = 'max-height 0.3s ease, opacity 0.25s ease';
                    searchDrop.style.maxHeight = searchDrop.scrollHeight + 'px';
                    searchDrop.style.opacity = '1';
                });
            } else {
                searchDrop.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                searchDrop.style.maxHeight = '0px';
                searchDrop.style.opacity = '0';
                setTimeout(() => searchDrop.classList.add('hidden'), 250);
            }
        });
    }

    document.addEventListener('click', (e) => {
        if (!searchDrop || !toggleSearch) return;
        if (!searchDrop.contains(e.target) && !toggleSearch.contains(e.target)) {
            if (!searchDrop.classList.contains('hidden')) {
                searchDrop.style.transition = 'max-height 0.25s ease, opacity 0.2s ease';
                searchDrop.style.maxHeight = '0px';
                searchDrop.style.opacity = '0';
                setTimeout(() => searchDrop.classList.add('hidden'), 250);
            }
        }
    });

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.classList.add('block');
        }
        document.body.style.overflow = 'hidden';
        if (hamburger) hamburger.classList.add('hidden');
        if (closeIcon) closeIcon.classList.remove('hidden');
    }

    function closeSidebar() {
        if (!sidebar) return;
        sidebar.classList.add('-translate-x-full');
        if (overlay) {
            overlay.classList.remove('block');
            overlay.classList.add('hidden');
        }
        document.body.style.overflow = '';
        if (hamburger) hamburger.classList.remove('hidden');
        if (closeIcon) closeIcon.classList.add('hidden');
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            if (sidebar?.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
    }
    if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    window.toggleDropdown = function (menuId) {
        const menu = document.getElementById(menuId + 'Menu');
        const arrow = document.getElementById(menuId + 'Arrow');
        if (menu && arrow) {
            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }
    };

    window.toggleTheme = function () {
        const html = document.documentElement;
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    };
});

/* ==========================================
   COMPONENT: LEARNING CORNER
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    // Page Info initialization
    const isLearningCornerPage = document.querySelector('[data-page-info="popup.user_create_learning_corner"]') ||
        document.querySelector('[data-page-info="popup.user_edit_learning_corner"]');
    if (!isLearningCornerPage) return;

    // Dynamic Items (Create & Edit)
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('learning-corner-add-item') || document.getElementById('add-item');

    if (itemsContainer && addItemBtn) {
        let itemIndex = parseInt(itemsContainer.getAttribute('data-item-count') || '0', 10);
        const isCreatePage = itemsContainer.getAttribute('data-is-create') === 'true';

        function addItem() {
            const newItem = document.createElement('div');
            newItem.className = 'item bg-gray-50 border border-gray-200 dark:bg-gray-900 rounded-xl p-6 relative';
            newItem.dataset.index = itemIndex;

            if (isCreatePage) {
                newItem.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <select name="items[${itemIndex}][type]" class="type-select border dark:text-white border-gray-300 dark:bg-gray-400 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                            <option class="dark:text-white" value="text" data-translate="add_text" data-translate-page="msh_lrn_add">Teks tambahan</option>
                            <option class="dark:text-white" value="image" data-translate="add_img" data-translate-page="msh_lrn_add">Gambar</option>
                            <option class="dark:text-white" value="link" data-translate="add_link" data-translate-page="msh_lrn_add">Link / Referensi</option>
                        </select>
                        <button data-translate="del" data-translate-page="msh_lrn_add" type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                            Hapus
                        </button>
                    </div>

                    <div class="content-area">
                        <input type="text" name="items[${itemIndex}][content]" class="text-input dark:placeholder:text-white w-full px-4 py-3 border border-gray-300 dark:bg-gray-400 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="Masukkan teks di sini...">

                        <div class="file-input hidden mt-2">
                            <input type="file" name="items[${itemIndex}][file]" accept="image/*"
                                   class="block w-full text-sm text-gray-500 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="mt-1 text-xs text-gray-500">Maks 5MB • jpg, png, gif</p>
                        </div>

                        <input type="url" name="items[${itemIndex}][content]" class="link-input hidden w-full px-4 py-3 dark:placeholder:text-white dark:bg-gray-400 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="https://example.com">
                    </div>
                `;
                itemsContainer.appendChild(newItem);
                attachTypeListenerCreate(newItem);
            } else {
                newItem.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <select name="items[${itemIndex}][type]" class="type-select border border-gray-300 rounded px-3 py-2 text-sm focus:border-indigo-500 outline-none w-44">
                            <option value="text">Teks tambahan</option>
                            <option value="image">Gambar</option>
                            <option value="link">Link / Referensi</option>
                        </select>
                        <button type="button" class="remove-item text-red-500 hover:text-red-700 text-sm font-medium">
                            Hapus
                        </button>
                    </div>
                    <div class="content-area mt-3">
                        <input type="text" name="items[${itemIndex}][content]"
                               class="text-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="Masukkan teks di sini...">
                    </div>
                `;
                itemsContainer.appendChild(newItem);
                attachTypeChangeListenerEdit(newItem);
                const firstInput = newItem.querySelector('input');
                if (firstInput) firstInput.focus();
            }

            itemIndex++;

            if (typeof window.refreshTranslations === 'function') {
                window.refreshTranslations();
            }
        }

        function attachTypeListenerCreate(itemElement) {
            const select = itemElement.querySelector('.type-select');
            const textInput = itemElement.querySelector('.text-input');
            const fileDiv = itemElement.querySelector('.file-input');
            const linkInput = itemElement.querySelector('.link-input');
            if (!select || !textInput || !fileDiv || !linkInput) return;

            function toggleFields() {
                const type = select.value;
                textInput.classList.toggle('hidden', type !== 'text');
                fileDiv.classList.toggle('hidden', type !== 'image');
                linkInput.classList.toggle('hidden', type !== 'link');

                textInput.disabled = type !== 'text';
                linkInput.disabled = type !== 'link';
                if (type === 'image') {
                    textInput.name = `items[${itemElement.dataset.index}][dummy]`;
                } else {
                    textInput.name = `items[${itemElement.dataset.index}][content]`;
                }
            }

            select.addEventListener('change', toggleFields);
            toggleFields();
        }

        function attachTypeChangeListenerEdit(itemElement) {
            const select = itemElement.querySelector('.type-select');
            if (!select) return;

            select.addEventListener('change', function () {
                const currentType = this.value;
                const contentArea = itemElement.querySelector('.content-area');
                const index = itemElement.dataset.index;
                if (!contentArea) return;

                if (currentType === 'image') {
                    contentArea.innerHTML = `
                        <label class="block text-sm text-gray-600 mb-1">Upload gambar baru (opsional):</label>
                        <input type="file" name="items[${index}][image_file]" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <input type="hidden" name="items[${index}][content]" value="">
                    `;
                } else {
                    contentArea.innerHTML = `
                        <input type="text" name="items[${index}][content]"
                               class="text-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:border-indigo-500 outline-none transition"
                               placeholder="${currentType === 'link' ? 'https://...' : 'Masukkan teks di sini...'}">
                    `;
                }
            });
        }

        // Initialize existing items for Edit page
        if (!isCreatePage) {
            document.querySelectorAll('.item').forEach(item => {
                attachTypeChangeListenerEdit(item);
            });
        }

        // Add first item on Create page if empty
        if (isCreatePage && itemIndex === 0) {
            addItem();
        }

        // Add Item event listener
        addItemBtn.addEventListener('click', addItem);
    }

    // Global listener for remove items
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-item')) {
            const item = e.target.closest('.item');
            if (item) item.remove();
        }
    });

    // Delete confirmation (Index Page)
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            if (typeof window.showConfirmAlert === 'function') {
                const confirmed = await window.showConfirmAlert({
                    title: 'Hapus Entri Learning Corner?',
                    text: 'Catatan ini akan dihapus permanen dan tidak bisa dikembalikan.',
                    icon: 'warning',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                });

                if (confirmed) {
                    if (typeof window.showLoading === 'function') {
                        window.showLoading('Menghapus catatan...');
                    }
                    this.closest('form').submit();
                }
            } else {
                if (confirm('Hapus Entri Learning Corner? Catatan ini akan dihapus permanen.')) {
                    this.closest('form').submit();
                }
            }
        });
    });
});

/* ==========================================
   COMPONENT: POSTINGAN
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {
    const itemsContainer = document.getElementById('items-container');
    const addItemBtn = document.getElementById('add-item');

    const isPostinganPage = document.querySelector('[data-page-info="popup.semua_postingan"]') ||
        document.querySelector('[data-page-info="popup.create_postingan"]') ||
        document.querySelector('[data-page-info="popup.edit_postingan"]');

    if (isPostinganPage && itemsContainer && addItemBtn) {
        let itemIndex = parseInt(itemsContainer.getAttribute('data-item-count') || '0', 10);
        const isCreatePage = itemsContainer.getAttribute('data-is-create') === 'true';

        window.addItem = function () {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item p-5 bg-white dark:bg-gray-800 relative group';
            newItem.dataset.index = itemIndex;

            newItem.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center" id="icon-container-${itemIndex}">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <select name="items[${itemIndex}][type]" class="type-select px-3 py-1.5 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="image">Gambar</option>
                            <option value="link">Link</option>
                        </select>
                    </div>
                    <button type="button" class="remove-item w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 dark:hover:text-red-400 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-all opacity-0 group-hover:opacity-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="content-area pl-11">
                    <div class="file-input">
                        <div class="relative border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:border-indigo-300 dark:hover:border-indigo-500 transition-colors" id="drop-area-${itemIndex}">
                            <input type="file" name="items[${itemIndex}][file]" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 file-input-trigger"
                                data-index="${itemIndex}"
                                onchange="previewImage(this)">
                            <div class="text-center" id="upload-placeholder-${itemIndex}">
                                <svg class="mx-auto w-8 h-8 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Klik atau drag & drop gambar</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Maks 5MB • jpg, png, gif, webp</p>
                            </div>
                            <div id="image-preview-${itemIndex}" class="hidden mt-2 flex justify-center"></div>
                        </div>
                    </div>

                    <div class="link-input hidden mt-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 000-5.656l-4-4a4 4 0 00-5.656 5.656L6.343 9.17"></path>
                                </svg>
                            </div>
                            <input type="url" name="items[${itemIndex}][content]"
                                class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="https://example.com">
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(newItem);
            attachTypeListener(newItem);
            itemIndex++;
        };

        window.previewImage = function (input) {
            const index = input.dataset.index;
            const previewContainer = document.getElementById(`image-preview-${index}`);
            const uploadPlaceholder = document.getElementById(`upload-placeholder-${index}`);

            if (!previewContainer) return;

            const file = input.files[0];

            if (file) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB');
                    input.value = '';
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan jpg, jpeg, png, gif, atau webp');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewContainer.innerHTML = `
                        <div class="image-preview-container">
                            <img src="${e.target.result}" alt="Preview" class="max-h-48 rounded-lg shadow-md">
                            <span class="remove-preview" onclick="removePreview(this, ${index})" title="Hapus gambar">×</span>
                        </div>
                    `;
                    previewContainer.classList.remove('hidden');
                    if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
                if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
            }
        };

        window.removePreview = function (btn, index) {
            const previewContainer = document.getElementById(`image-preview-${index}`);
            const uploadPlaceholder = document.getElementById(`upload-placeholder-${index}`);
            const fileInput = document.querySelector(`input[data-index="${index}"]`);
            const existingContentInput = btn.closest('.item')?.querySelector('.existing-content-value');

            if (fileInput) fileInput.value = '';
            if (existingContentInput) existingContentInput.value = '';
            if (previewContainer) {
                previewContainer.innerHTML = '';
                previewContainer.classList.add('hidden');
            }
            if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
        };

        window.attachTypeListener = function (itemElement) {
            const select = itemElement.querySelector('.type-select');
            const fileDiv = itemElement.querySelector('.file-input');
            const linkInput = itemElement.querySelector('.link-input');
            const iconContainer = itemElement.querySelector('[id^="icon-container"]');

            function toggleFields() {
                const type = select.value;

                if (iconContainer) {
                    if (type === 'image') {
                        iconContainer.innerHTML = `
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        `;
                    } else {
                        iconContainer.innerHTML = `
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 000-5.656l-4-4a4 4 0 00-5.656 5.656L6.343 9.17"></path>
                            </svg>
                        `;
                    }
                }

                if (fileDiv) fileDiv.classList.toggle('hidden', type !== 'image');
                if (linkInput) linkInput.classList.toggle('hidden', type !== 'link');

                if (type === 'image') {
                    const fileInput = fileDiv?.querySelector('input[type="file"]');
                    if (fileInput) fileInput.name = `items[${itemElement.dataset.index}][file]`;
                    const linkUrlInput = linkInput?.querySelector('input[type="url"]');
                    if (linkUrlInput) linkUrlInput.name = `items[${itemElement.dataset.index}][dummy]`;
                } else if (type === 'link') {
                    const fileInput = fileDiv?.querySelector('input[type="file"]');
                    if (fileInput) fileInput.name = `items[${itemElement.dataset.index}][dummy_file]`;
                    const linkUrlInput = linkInput?.querySelector('input[type="url"]');
                    if (linkUrlInput) linkUrlInput.name = `items[${itemElement.dataset.index}][content]`;
                }
            }

            select.addEventListener('change', toggleFields);
            toggleFields();
        };

        // Attach to existing server-rendered items
        document.querySelectorAll('.item').forEach(item => {
            attachTypeListener(item);
        });

        // Add first item on Create page
        if (isCreatePage && itemIndex === 0) {
            window.addItem();
        }

        addItemBtn.addEventListener('click', window.addItem);

        // ===== INISIALISASI TAMBAHAN UNTUK EDIT POSTINGAN =====
        // Auto-resize textarea
        const judul = document.getElementById('judul');
        const deskripsi = document.getElementById('deskripsi');

        if (judul) {
            judul.style.height = '';
            judul.style.height = judul.scrollHeight + 'px';
            judul.addEventListener('input', function () {
                this.style.height = '';
                this.style.height = this.scrollHeight + 'px';
            });
        }

        if (deskripsi) {
            deskripsi.style.height = '';
            deskripsi.style.height = deskripsi.scrollHeight + 'px';
            deskripsi.addEventListener('input', function () {
                this.style.height = '';
                this.style.height = this.scrollHeight + 'px';
            });
        }

        // Game toggle
        const gameEnabledEl = document.getElementById('game_enabled');
        if (gameEnabledEl) {
            const gameNameEl = document.getElementById('game_name');
            const gameThumbEl = document.getElementById('game_thumbnail');
            const thumbnailContainer = document.getElementById('thumbnail_container');
            const removeThumbBtn = document.getElementById('remove_thumbnail_btn');
            const removeThumbInput = document.getElementById('remove_thumbnail');

            function updateGameState() {
                const checked = gameEnabledEl.checked;
                if (gameNameEl) gameNameEl.disabled = !checked;
                if (gameThumbEl) gameThumbEl.disabled = !checked;
                if (thumbnailContainer) thumbnailContainer.style.display = checked ? 'block' : 'none';
                if (!checked && removeThumbInput) removeThumbInput.value = '1';
            }

            gameEnabledEl.addEventListener('change', updateGameState);

            if (removeThumbBtn) {
                removeThumbBtn.addEventListener('click', function () {
                    const container = document.getElementById('current_thumbnail_container');
                    if (container) container.remove();
                    if (removeThumbInput) removeThumbInput.value = '1';
                    if (gameThumbEl) gameThumbEl.value = '';
                });
            }
        }
    }

    // Textarea auto-resize on load
    const judul = document.getElementById('judul');
    const deskripsi = document.getElementById('deskripsi');

    if (judul) {
        judul.style.height = '';
        judul.style.height = judul.scrollHeight + 'px';
        judul.addEventListener('input', function () {
            this.style.height = '';
            this.style.height = this.scrollHeight + 'px';
        });
    }

    if (deskripsi) {
        deskripsi.style.height = '';
        deskripsi.style.height = deskripsi.scrollHeight + 'px';
        deskripsi.addEventListener('input', function () {
            this.style.height = '';
            this.style.height = this.scrollHeight + 'px';
        });
    }

    // Game toggle
    const gameEnabledEl = document.getElementById('game_enabled');
    if (gameEnabledEl) {
        const gameNameEl = document.getElementById('game_name');
        const gameThumbEl = document.getElementById('game_thumbnail');
        const thumbnailContainer = document.getElementById('thumbnail_container');
        const removeThumbBtn = document.getElementById('remove_thumbnail_btn');
        const removeThumbInput = document.getElementById('remove_thumbnail');

        gameEnabledEl.addEventListener('change', function () {
            const checked = this.checked;
            if (gameNameEl) gameNameEl.disabled = !checked;
            if (gameThumbEl) gameThumbEl.disabled = !checked;
            if (thumbnailContainer) thumbnailContainer.style.display = checked ? 'block' : 'none';
            if (!checked && removeThumbInput) removeThumbInput.value = '1';
        });

        if (removeThumbBtn) {
            removeThumbBtn.addEventListener('click', function () {
                const container = document.getElementById('current_thumbnail_container');
                if (container) container.remove();
                if (removeThumbInput) removeThumbInput.value = '1';
                if (gameThumbEl) gameThumbEl.value = '';
            });
        }
    }

    // Remove postingan items globally
    document.addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
            const item = e.target.closest('.item');
            if (item) {
                item.style.opacity = '0';
                item.style.transform = 'translateY(-10px)';
                setTimeout(() => item.remove(), 150);
            }
        }
    });
});

