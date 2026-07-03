/**
 * resources/js/modules/core/user-guide.js
 *
 * Driver.js User Guides for authenticated views
 */

export function initUserGuide() {
    const guideBtn = document.getElementById('userGuideButton');
    const guideBtnMobile = document.getElementById('userGuideButtonMobile');
    
    if (!guideBtn && !guideBtnMobile) return;
    if (!window.driver || !window.driver.js || !window.driver.js.driver) {
        return;
    }

    const driver = window.driver.js.driver;

    // Detect page context
    let pageKey = 'generic';
    const projectFormContainer = document.getElementById('projectForm');
    const isProjectFormPage = Boolean(projectFormContainer);

    if (document.querySelector('[data-dashboard-type="me"]')) {
        // Double check if admin/dosen or student
        if (document.body.innerHTML.includes('Dashboard Admin') || window.location.pathname.includes('/admin/')) {
            pageKey = 'admin_dashboard';
        } else if (document.body.innerHTML.includes('Dashboard Dosen') || window.location.pathname.includes('/dosen/')) {
            pageKey = 'dosen_dashboard';
        } else {
            pageKey = 'student_dashboard';
        }
    } else if (document.getElementById('project-detail-container')) {
        pageKey = 'project_detail';
    } else if (document.getElementById('project-user-container') || document.getElementById('all-projects-container')) {
        pageKey = 'projects_list';
    } else if (isProjectFormPage) {
        pageKey = 'project_form';
    } else if (document.querySelector('[data-page-info*="user_create_learning_corner"]')) {
        pageKey = 'learning_corner_create';
    } else if (document.querySelector('[data-page-info*="user_edit_learning_corner"]')) {
        pageKey = 'learning_corner_edit';
    } else if (document.querySelector('[data-page-info*="learning_corner"]')) {
        pageKey = 'learning_corner_list';
    } else if (document.getElementById('sertifikat-user-container') || document.getElementById('sertifikat-list-container')) {
        pageKey = 'certificates_list';
    } else if (document.getElementById('sertifikat-create-container') || document.getElementById('sertifikat-edit-container')) {
        pageKey = 'certificate_form';
    } else if (document.querySelector('[data-page-info*="popup.create_postingan"]')) {
        pageKey = 'postingan_create';
    } else if (document.querySelector('[data-page-info*="popup.edit_postingan"]')) {
        pageKey = 'postingan_edit';
    } else if (document.querySelector('[data-page-info*="popup.semua_postingan"]')) {
        pageKey = 'postingan_list';
    } else if (document.getElementById('postingan-detail-container')) {
        pageKey = 'postingan_detail';
    } else if (document.getElementById('form-profile')) {
        pageKey = 'profile_page';
    } else if (window.location.pathname.includes('/search')) {
        pageKey = 'search_results';
    }

    // Get current language
    const lang = localStorage.getItem('lang') === 'en' ? 'en' : 'id';

    // Steps translations mapping
    const translations = {
        id: {
            nextBtnText: 'Lanjut',
            prevBtnText: 'Kembali',
            doneBtnText: 'Selesai',
            sidebar: {
                title: 'Sidebar Navigasi',
                desc: 'Akses menu utama platform seperti Profil, Project, Sertifikat, dan Postingan.'
            },
            search: {
                title: 'Pencarian Utama',
                desc: 'Cari postingan, nama mahasiswa, prodi, atau project secara instan.'
            },
            chatbot: {
                title: 'Asisten Virtual POLMIND',
                desc: 'Butuh bantuan cepat akademik atau fitur? Asisten virtual kami siap melayani Anda 24/7.'
            },
            student_dashboard: {
                header: {
                    title: 'Header Dashboard',
                    desc: 'Ini adalah header utama halaman Anda yang berisi navigasi global dan kontrol tampilan.',
                    complete_desc: 'Data profil Anda lengkap. Selamat, semua informasi penting sudah terisi.',
                    incomplete_desc: 'Data profil Anda belum lengkap. Lengkapi informasi penting agar fitur user guide berjalan optimal.'
                },
                settings: {
                    title: 'Menu Pengaturan',
                    desc: 'Buka pengaturan bahasa dan mode gelap dari sidebar ini.'
                },
                profile_menu: {
                    title: 'Menu Profil',
                    desc: 'Buka menu profil untuk melihat akun, profil, atau verifikasi dua langkah.'
                },
                stats_lrn: {
                    title: 'Statistik Belajar',
                    desc: 'Pantau jumlah modul atau topik Learning Corner yang telah Anda ikuti.'
                },
                stats_pjt: {
                    title: 'Statistik Project',
                    desc: 'Lihat jumlah proyek portofolio yang telah Anda tambahkan.'
                },
                stats_stk: {
                    title: 'Statistik Sertifikat',
                    desc: 'Pantau total sertifikasi kompetensi Anda yang terdaftar.'
                },
                create_post: {
                    title: 'Bagikan Perihal Baru',
                    desc: 'Tulis artikel atau bagikan foto kegiatan seru Anda di sini.'
                },
                posts: {
                    title: 'Daftar Postingan',
                    desc: 'Daftar artikel dan postingan yang telah Anda publikasikan.'
                }
            },
            dosen_dashboard: {
                stats: {
                    title: 'Statistik Bimbingan Dosen',
                    desc: 'Ringkasan jumlah mahasiswa bimbingan serta verifikasi yang tertunda.'
                }
            },
            admin_dashboard: {
                stats: {
                    title: 'Aksi Cepat Admin',
                    desc: 'Kelola data pengguna, data angkatan, prodi, keahlian, dan postingan.'
                }
            },
            projects_list: {
                container: {
                    title: 'Portofolio Project',
                    desc: 'Semua project yang sudah Anda unggah akan ditampilkan di halaman ini.'
                },
                add_btn: {
                    title: 'Tambah Project Baru',
                    desc: 'Klik di sini atau gunakan menu sidebar untuk mendaftarkan project baru.'
                },
                cards: {
                    title: 'Daftar Project',
                    desc: 'Jelajahi kartu project untuk melihat detail, status, dan tautan pendukung.'
                },
                edit_btn: {
                    title: 'Kelola Project',
                    desc: 'Gunakan tombol edit atau hapus jika Anda adalah pemilik atau leader project.'
                }
            },
            project_detail: {
                status: {
                    title: 'Status Project',
                    desc: 'Lihat status proyek dan progress saat ini.'
                },
                description: {
                    title: 'Deskripsi Proyek',
                    desc: 'Pelajari inti project, tujuan, dan penjelasan singkatnya.'
                },
                team: {
                    title: 'Tim Project',
                    desc: 'Cek owner, leader, dan anggota yang terlibat dalam proyek.'
                },
                learning_corner: {
                    title: 'Learning Corner',
                    desc: 'Pantau catatan dan dokumentasi proyek di bagian ini.'
                },
                add_learning_corner: {
                    title: 'Tambah Catatan',
                    desc: 'Buat dokumentasi atau catatan perkembangan proyek baru.'
                }
            },
            learning_corner_list: {
                header: {
                    title: 'Learning Corner Saya',
                    desc: 'Kelola semua catatan belajar yang telah Anda buat.'
                },
                create_btn: {
                    title: 'Buat Catatan Baru',
                    desc: 'Klik untuk menambahkan entri learning corner baru.'
                },
                entries: {
                    title: 'Daftar Catatan',
                    desc: 'Lihat semua entri yang sudah Anda publikasikan di learning corner.'
                }
            },
            learning_corner_create: {
                title_input: {
                    title: 'Judul Catatan',
                    desc: 'Beri judul yang jelas untuk catatan Anda.'
                },
                add_item: {
                    title: 'Tambah Item Konten',
                    desc: 'Tambahkan teks, gambar, atau tautan pendukung pada catatan Anda.'
                },
                save: {
                    title: 'Simpan Catatan',
                    desc: 'Klik untuk menyimpan seluruh konten learning corner.'
                }
            },
            learning_corner_edit: {
                title_input: {
                    title: 'Ubah Judul Catatan',
                    desc: 'Perbarui judul catatan sesuai kebutuhan Anda.'
                },
                add_item: {
                    title: 'Tambah atau Ubah Konten',
                    desc: 'Perluas catatan dengan item baru atau ubah yang sudah ada.'
                },
                save: {
                    title: 'Simpan Perubahan',
                    desc: 'Terapkan perubahan pada catatan learning corner.'
                }
            },
            sertifikat_list: {
                header: {
                    title: 'Sertifikat Saya',
                    desc: 'Lihat semua sertifikat yang telah Anda tambahkan beserta statusnya.'
                },
                create_btn: {
                    title: 'Tambah Sertifikat Baru',
                    desc: 'Klik untuk menambahkan sertifikat kompetensi baru ke portofolio Anda.'
                },
                edit_btn: {
                    title: 'Aksi Sertifikat',
                    desc: 'Gunakan tombol aksi di kartu sertifikat untuk mengedit atau menghapus entri.'
                },
                cards: {
                    title: 'Daftar Sertifikat',
                    desc: 'Kelola sertifikat berdasarkan status dan detail yang tersimpan.'
                }
            },
            sertifikat_form: {
                name: {
                    title: 'Nama Sertifikat',
                    desc: 'Masukkan nama sertifikat atau kompetensi Anda.'
                },
                publisher: {
                    title: 'Lembaga Penerbit',
                    desc: 'Masukkan nama lembaga yang menerbitkan sertifikat ini.'
                },
                date: {
                    title: 'Tanggal Terbit',
                    desc: 'Pilih tanggal sertifikat diterbitkan.'
                },
                upload: {
                    title: 'Unggah Sertifikat',
                    desc: 'Unggah file sertifikat dalam format gambar.'
                },
                save: {
                    title: 'Simpan Sertifikat',
                    desc: 'Klik untuk menyimpan sertifikat baru atau perubahan Anda.'
                }
            },
            postingan_create: {
                title_input: {
                    title: 'Judul Postingan',
                    desc: 'Tuliskan judul menarik untuk postingan Anda.'
                },
                description_input: {
                    title: 'Deskripsi Postingan',
                    desc: 'Jelaskan isi postingan secara singkat namun jelas.'
                },
                add_item: {
                    title: 'Tambah Media atau Link',
                    desc: 'Tambahkan gambar, video, atau tautan pendukung untuk postingan Anda.'
                },
                submit: {
                    title: 'Publikasikan Postingan',
                    desc: 'Klik untuk menerbitkan postingan baru Anda.'
                }
            },
            postingan_edit: {
                title_input: {
                    title: 'Edit Judul Postingan',
                    desc: 'Perbarui judul agar lebih tepat dan informatif.'
                },
                description_input: {
                    title: 'Edit Isi Postingan',
                    desc: 'Ubah teks atau detail postingan sesuai kebutuhan.'
                },
                add_item: {
                    title: 'Tambah atau Ubah Media',
                    desc: 'Sisipkan media baru atau perbarui item yang sudah ada.'
                },
                submit: {
                    title: 'Simpan Perubahan',
                    desc: 'Klik untuk menyimpan perubahan postingan Anda.'
                }
            },
            postingan_list: {
                header: {
                    title: 'Postingan Saya',
                    desc: 'Kelola postingan Anda dan tambahkan entri baru kapan saja.'
                },
                create_btn: {
                    title: 'Buat Postingan Baru',
                    desc: 'Klik untuk membuat postingan terbaru dan membagikannya ke komunitas.'
                },
                actions: {
                    title: 'Aksi Postingan',
                    desc: 'Gunakan tombol edit/hapus pada postingan yang sudah ada.'
                }
            },
            postingan_detail: {
                container: {
                    title: 'Detail Postingan',
                    desc: 'Lihat informasi lengkap postingan dan aksi yang tersedia.'
                },
                actions: {
                    title: 'Aksi Postingan',
                    desc: 'Gunakan tombol menu untuk mengedit atau menghapus postingan jika Anda pemiliknya.'
                }
            },
            project_form: {
                title: {
                    title: 'Judul Project',
                    desc: 'Tulis judul project Anda agar mudah dikenali.'
                },
                desc: {
                    title: 'Deskripsi Project',
                    desc: 'Jelaskan inti project Anda secara singkat.'
                },
                collaborative: {
                    title: 'Aktifkan Mode Kolaboratif',
                    desc: 'Nyalakan toggle ini jika project dikerjakan bersama tim.'
                },
                member_add: {
                    title: 'Tambah Anggota',
                    desc: 'Klik tombol ini untuk menambahkan anggota tim.'
                },
                member_role: {
                    title: 'Pilih Peran Anggota',
                    desc: 'Tentukan apakah user ini menjadi leader atau member.'
                },
                member_manage: {
                    title: 'Edit atau Hapus Member',
                    desc: 'Kelola anggota yang sudah ditambahkan sebelum melanjutkan.'
                },
                dates: {
                    title: 'Atur Tanggal Project',
                    desc: 'Isi tanggal mulai dan selesai agar timeline project jelas.'
                },
                project_link: {
                    title: 'Link Project',
                    desc: 'Tambahkan tautan demo atau halaman project Anda.'
                },
                github_link: {
                    title: 'Link GitHub',
                    desc: 'Masukkan tautan repository GitHub jika ada.'
                },
                video_link: {
                    title: 'Link Video',
                    desc: 'Sisipkan tautan video presentasi atau demo project.'
                },
                task_section: {
                    title: 'Tambah Tugas',
                    desc: 'Buat daftar tugas yang harus dikerjakan tim.'
                },
                task_responsible: {
                    title: 'Pilih Penanggung Jawab',
                    desc: 'Tentukan siapa yang bertanggung jawab atas tugas ini.'
                },
                task_name: {
                    title: 'Tulis Nama Tugas',
                    desc: 'Jelaskan tugas yang harus diselesaikan.'
                },
                task_done: {
                    title: 'Tandai Tugas Selesai',
                    desc: 'Centang opsi ini jika tugas sudah selesai.'
                },
                submit: {
                    title: 'Kirim Form',
                    desc: 'Klik tombol ini untuk menyimpan project.'
                },
                name: {
                    title: 'Nama Project',
                    desc: 'Beri judul proyek Anda dengan jelas dan menarik.'
                },
                links: {
                    title: 'Tautan Pendukung',
                    desc: 'Sediakan tautan demo web atau repositori GitHub agar mempermudah peninjauan.'
                }
            },
            certificates_list: {
                container: {
                    title: 'Sertifikat Kompetensi',
                    desc: 'Daftar sertifikat keahlian Anda beserta status validitasnya.'
                }
            },
            certificate_form: {
                name: {
                    title: 'Nama Sertifikat',
                    desc: 'Tuliskan nama atau bidang sertifikasi kompetensi Anda.'
                },
                publisher: {
                    title: 'Lembaga Penerbit',
                    desc: 'Tuliskan organisasi atau instansi yang menerbitkan sertifikat.'
                },
                file: {
                    title: 'Unggah Berkas',
                    desc: 'Unggah salinan sertifikat Anda (dalam format gambar atau PDF).'
                }
            },
            profile_page: {
                cover: {
                    title: 'Sampul Profil',
                    desc: 'Ubah gambar cover profil Anda agar halaman portofolio tampil menawan.'
                },
                avatar: {
                    title: 'Foto Profil',
                    desc: 'Ganti dan sesuaikan foto profil Anda secara interaktif.'
                },
                education_section: {
                    title: 'Bagian Pendidikan Kosong',
                    desc: 'Tambahkan data pendidikan agar profil Anda terlihat lebih lengkap.'
                },
                experience_section: {
                    title: 'Bagian Pengalaman Kosong',
                    desc: 'Tambahkan pengalaman kerja agar profil portofolio Anda semakin kuat.'
                },
                project_section: {
                    title: 'Bagian Project Kosong',
                    desc: 'Tambahkan project agar portofolio Anda terlihat lebih lengkap.'
                },
                certificate_section: {
                    title: 'Bagian Sertifikat Kosong',
                    desc: 'Tambahkan sertifikat untuk memperkuat kemampuan yang ditampilkan.'
                },
                education_modal: {
                    title: 'Form Tambah Pendidikan',
                    desc: 'Isi data pendidikan Anda agar profil pendidikan lengkap dan valid.',
                    start_year: {
                        title: 'Tahun Masuk Pendidikan',
                        desc: 'Pilih tahun masuk untuk menandai riwayat pendidikan Anda.'
                    }
                },
                education_edit: {
                    title: 'Edit Pendidikan',
                    desc: 'Masuk ke mode edit untuk memperbarui data pendidikan yang sudah tersimpan.',
                    save: {
                        title: 'Simpan Perubahan Pendidikan',
                        desc: 'Klik untuk menyimpan perubahan pada data pendidikan.'
                    }
                },
                experience_modal: {
                    title: 'Form Tambah Pengalaman',
                    desc: 'Isi pengalaman kerja baru untuk melengkapi profil Anda.',
                    start_year: {
                        title: 'Tahun Mulai Kerja',
                        desc: 'Pilih tahun mulai untuk pengalaman kerja ini.'
                    }
                },
                experience_edit: {
                    title: 'Edit Pengalaman',
                    desc: 'Masuk ke mode edit untuk memperbarui pengalaman kerja yang tersimpan.',
                    save: {
                        title: 'Simpan Perubahan Pengalaman',
                        desc: 'Klik untuk menyimpan perubahan pada pengalaman kerja Anda.'
                    }
                },
                incomplete: {
                    title: 'Lengkapi Profil',
                    desc: 'Bagian ini menunjukkan informasi profil yang belum lengkap dan perlu diperbarui.'
                },
                main_layout: {
                    title: 'Halaman Profil',
                    desc: 'Ini adalah tampilan utama profil Anda, tempat semua informasi dasar ditampilkan.'
                },
                share: {
                    title: 'Bagikan Portofolio',
                    desc: 'Salin tautan portofolio online Anda untuk dibagikan ke rekruter atau media sosial.'
                },
                skills: {
                    title: 'Keahlian Tambahan',
                    desc: 'Ajukan keahlian tambahan yang Anda kuasai untuk divalidasi oleh dosen.'
                }
            }
        },
        en: {
            nextBtnText: 'Next',
            prevBtnText: 'Previous',
            doneBtnText: 'Finish',
            sidebar: {
                title: 'Navigation Sidebar',
                desc: 'Access major menus including Profile, Projects, Certificates, and Posts.'
            },
            search: {
                title: 'Unified Search',
                desc: 'Search for posts, student names, majors, or projects instantly.'
            },
            chatbot: {
                title: 'POLMIND Virtual Assistant',
                desc: 'Need help? Ask our virtual chatbot assistant anytime 24/7.'
            },
            student_dashboard: {
                stats_lrn: {
                    title: 'Learning Corner Stats',
                    desc: 'Monitor the total modules or subjects you have studied.'
                },
                stats_pjt: {
                    title: 'Project Stats',
                    desc: 'View the total portfolio projects you have registered.'
                },
                stats_stk: {
                    title: 'Certificate Stats',
                    desc: 'Monitor your total registered competency certificates.'
                },
                create_post: {
                    title: 'Create New Post',
                    desc: 'Write an article or share photos of your activities here.'
                },
                posts: {
                    title: 'Your Posts Feed',
                    desc: 'List of articles and updates you have published.'
                }
            },
            dosen_dashboard: {
                stats: {
                    title: 'Bimbingan Stats',
                    desc: 'Overview of your student guidance and pending verifications.'
                }
            },
            admin_dashboard: {
                stats: {
                    title: 'Admin Quick Actions',
                    desc: 'Manage system users, cohorts, majors, skills, and activities.'
                }
            },
            projects_list: {
                container: {
                    title: 'Project Portfolio',
                    desc: 'All projects you have uploaded will be displayed on this page.'
                },
                add_btn: {
                    title: 'Add New Project',
                    desc: 'Click here or use the sidebar link to add a new project.'
                },
                cards: {
                    title: 'Project List',
                    desc: 'Browse each project card to view details, status, and supporting links.'
                },
                edit_btn: {
                    title: 'Manage Project',
                    desc: 'Use the edit or delete actions when you are the owner or leader.'
                }
            },
            project_detail: {
                status: {
                    title: 'Project Status',
                    desc: 'See the current project status and progress.'
                },
                description: {
                    title: 'Project Description',
                    desc: 'Review the project goals and summary.'
                },
                team: {
                    title: 'Project Team',
                    desc: 'Check the owner, leader, and members involved.'
                },
                learning_corner: {
                    title: 'Learning Corner',
                    desc: 'Browse project notes and documentation here.'
                },
                add_learning_corner: {
                    title: 'Add a Note',
                    desc: 'Create a new documentation entry for the project.'
                }
            },
            learning_corner_list: {
                header: {
                    title: 'My Learning Corner',
                    desc: 'Manage all learning notes you have created.'
                },
                create_btn: {
                    title: 'Create a New Note',
                    desc: 'Click to add a fresh learning corner entry.'
                },
                entries: {
                    title: 'Note List',
                    desc: 'View every learning corner entry you have published.'
                }
            },
            learning_corner_create: {
                title_input: {
                    title: 'Note Title',
                    desc: 'Give your note a clear title.'
                },
                add_item: {
                    title: 'Add Content Item',
                    desc: 'Add text, images, or supporting links to your note.'
                },
                save: {
                    title: 'Save Note',
                    desc: 'Click to save the entire learning corner entry.'
                }
            },
            learning_corner_edit: {
                title_input: {
                    title: 'Edit Note Title',
                    desc: 'Update the title to fit your current note.'
                },
                add_item: {
                    title: 'Add or Edit Content',
                    desc: 'Expand the note with new items or update existing ones.'
                },
                save: {
                    title: 'Save Changes',
                    desc: 'Apply your edits to the learning corner entry.'
                }
            },
            project_form: {
                title: {
                    title: 'Project Title',
                    desc: 'Write a clear title so the project is easy to recognize.'
                },
                desc: {
                    title: 'Project Description',
                    desc: 'Explain the main purpose of your project in a short summary.'
                },
                collaborative: {
                    title: 'Enable Collaborative Mode',
                    desc: 'Turn this on if the project is being worked on with a team.'
                },
                member_add: {
                    title: 'Add Team Members',
                    desc: 'Click here to add team members to the project.'
                },
                member_role: {
                    title: 'Choose Member Role',
                    desc: 'Set whether a user acts as leader or member.'
                },
                member_manage: {
                    title: 'Edit or Remove Members',
                    desc: 'Manage the users you already added before continuing.'
                },
                dates: {
                    title: 'Set Project Dates',
                    desc: 'Fill in the start and end dates so the timeline is clear.'
                },
                project_link: {
                    title: 'Project Link',
                    desc: 'Add a demo link or public page for the project.'
                },
                github_link: {
                    title: 'GitHub Link',
                    desc: 'Add your repository link if one is available.'
                },
                video_link: {
                    title: 'Video Link',
                    desc: 'Add a presentation or demo video link if available.'
                },
                task_section: {
                    title: 'Add Tasks',
                    desc: 'Create a list of tasks the team needs to complete.'
                },
                task_responsible: {
                    title: 'Choose Task Owner',
                    desc: 'Select who is responsible for this task.'
                },
                task_name: {
                    title: 'Write the Task Name',
                    desc: 'Describe what needs to be done.'
                },
                task_done: {
                    title: 'Mark Task as Done',
                    desc: 'Tick this when the task is completed.'
                },
                submit: {
                    title: 'Submit the Form',
                    desc: 'Click here to save the project.'
                },
                name: {
                    title: 'Project Name',
                    desc: 'Enter a clear and compelling title for your project.'
                },
                links: {
                    title: 'Supporting Links',
                    desc: 'Provide links to a live demo or GitHub repository to ease review.'
                }
            },
            certificates_list: {
                container: {
                    title: 'Competency Certificates',
                    desc: 'List of your certificates along with their validity status.'
                }
            },
            certificate_form: {
                name: {
                    title: 'Certificate Title',
                    desc: 'Enter the name of your competency certificate.'
                },
                publisher: {
                    title: 'Publishing Institution',
                    desc: 'Enter the organization or institution that issued the certificate.'
                },
                file: {
                    title: 'Upload File',
                    desc: 'Upload a copy of your certificate (supports image or PDF).'
                }
            },
            profile_page: {
                cover: {
                    title: 'Profile Cover',
                    desc: 'Change your background banner to make your portfolio look premium.'
                },
                avatar: {
                    title: 'Profile Avatar',
                    desc: 'Upload and crop your profile avatar interactively.'
                },
                education_section: {
                    title: 'Empty Education Section',
                    desc: 'Add education data so your profile is more complete.'
                },
                experience_section: {
                    title: 'Empty Experience Section',
                    desc: 'Add work experience to strengthen your portfolio profile.'
                },
                project_section: {
                    title: 'Empty Project Section',
                    desc: 'Add projects so your portfolio shows your best work.'
                },
                certificate_section: {
                    title: 'Empty Certificate Section',
                    desc: 'Add certificates to showcase your verified skills.'
                },
                education_modal: {
                    title: 'Add Education Form',
                    desc: 'Fill in education details to complete your profile.',
                    start_year: {
                        title: 'Education Start Year',
                        desc: 'Select the year you started this education entry.'
                    }
                },
                education_edit: {
                    title: 'Edit Education',
                    desc: 'Switch to edit mode to update saved education information.',
                    save: {
                        title: 'Save Education Changes',
                        desc: 'Click to save the changes to this education record.'
                    }
                },
                experience_modal: {
                    title: 'Add Experience Form',
                    desc: 'Fill in experience details to complete your profile.',
                    start_year: {
                        title: 'Experience Start Year',
                        desc: 'Select the year you started this job or role.'
                    }
                },
                experience_edit: {
                    title: 'Edit Experience',
                    desc: 'Switch to edit mode to update saved experience information.',
                    save: {
                        title: 'Save Experience Changes',
                        desc: 'Click to save the changes to this experience record.'
                    }
                },
                incomplete: {
                    title: 'Complete Profile',
                    desc: 'This highlights profile details that are missing and need to be updated.'
                },
                main_layout: {
                    title: 'Profile Page Layout',
                    desc: 'This is the main layout of your profile page.'
                },
                share: {
                    title: 'Share Portfolio',
                    desc: 'Copy your public portfolio link to share with recruiters or on social media.'
                },
                skills: {
                    title: 'Additional Skills',
                    desc: 'Submit additional skills you have mastered for lecturer validation.'
                }
            }
        }
    };

    const t = translations[lang];

    // Helper to build a step object. `extra` allows passing driver.js
    // step-level hooks such as onHighlightStarted / onDeselected.
    const step = (element, title, description, extra = {}) => ({
        element,
        popover: {
            title,
            description,
            side: "bottom",
            align: "start"
        },
        ...extra
    });

    // Builds the project_form steps from current DOM state. Extracted so it
    // can be reused both for the initial tour and when rebuilding the tour
    // after the collaborative toggle changes (avoids the two copies of this
    // logic drifting apart).
    const buildProjectFormSteps = () => {
        const formSteps = [];
        if (document.querySelector('input[name="nama_project"]')) {
            formSteps.push(step('input[name="nama_project"]', t.project_form.title.title, t.project_form.title.desc));
        }
        if (document.querySelector('textarea[name="deskripsi"]')) {
            formSteps.push(step('textarea[name="deskripsi"]', t.project_form.desc.title, t.project_form.desc.desc));
        }

        const collToggle = document.getElementById('project-collaborative-toggle');
        if (collToggle) {
            if (document.getElementById('project-collaborative-wrapper')) {
                formSteps.push(step('#project-collaborative-wrapper', t.project_form.collaborative.title, t.project_form.collaborative.desc));
            } else {
                formSteps.push(step('#project-collaborative-toggle', t.project_form.collaborative.title, t.project_form.collaborative.desc));
            }
        }

        if (collToggle?.checked) {
            const addMemberBtn = document.querySelector('#projectForm button[onclick*="openUserModal"]');
            if (addMemberBtn) {
                formSteps.push(step('#projectForm button[onclick*="openUserModal"]', t.project_form.member_add.title, t.project_form.member_add.desc));
            }
            // #userModal is hidden (display:none) until the user clicks "Tambah User".
            // Driver.js can't highlight a hidden element, so we open the modal
            // ourselves right before this step is shown, and close it again once
            // the tour moves past it.
            if (document.getElementById('userModal')) {
                formSteps.push(step('#userModal', t.project_form.member_role.title, t.project_form.member_role.desc, {
                    onHighlightStarted: () => {
                        const modal = document.getElementById('userModal');
                        if (modal && modal.classList.contains('hidden')) {
                            window.openUserModal?.();
                        }
                    },
                    onDeselected: () => {
                        const modal = document.getElementById('userModal');
                        if (modal && !modal.classList.contains('hidden')) {
                            modal.classList.add('hidden');
                        }
                    }
                }));
            }
            if (document.getElementById('selected-users-container')) {
                formSteps.push(step('#selected-users-container', t.project_form.member_manage.title, t.project_form.member_manage.desc));
            }
        }

        if (document.getElementById('tanggal_mulai') || document.getElementById('tanggal_akhir')) {
            formSteps.push(step('#tanggal_mulai', t.project_form.dates.title, t.project_form.dates.desc));
        }
        if (document.querySelector('input[name="link_project"]')) {
            formSteps.push(step('input[name="link_project"]', t.project_form.project_link.title, t.project_form.project_link.desc));
        }
        if (document.querySelector('input[name="link_github"]')) {
            formSteps.push(step('input[name="link_github"]', t.project_form.github_link.title, t.project_form.github_link.desc));
        }
        if (document.querySelector('input[name="link_video"]')) {
            formSteps.push(step('input[name="link_video"]', t.project_form.video_link.title, t.project_form.video_link.desc));
        }
        if (document.getElementById('task-section')) {
            formSteps.push(step('#task-section', t.project_form.task_section.title, t.project_form.task_section.desc));
        }
        if (document.querySelector('#projectForm .task-user-select')) {
            formSteps.push(step('#projectForm .task-user-select', t.project_form.task_responsible.title, t.project_form.task_responsible.desc));
        }
        if (document.querySelector('#projectForm .task-name-input')) {
            formSteps.push(step('#projectForm .task-name-input', t.project_form.task_name.title, t.project_form.task_name.desc));
        }
        if (document.querySelector('#projectForm .task-is-done')) {
            formSteps.push(step('#projectForm .task-is-done', t.project_form.task_done.title, t.project_form.task_done.desc));
        }
        const submitBtnEl = document.querySelector('#projectForm button[type="submit"]');
        if (submitBtnEl) {
            formSteps.push(step('#projectForm button[type="submit"]', t.project_form.submit.title, t.project_form.submit.desc));
        }
        return formSteps;
    };

    const isProjectFormFlow = pageKey === 'project_form';
    const isPageSpecificGuide = ['projects_list', 'project_detail', 'learning_corner_list', 'learning_corner_create', 'learning_corner_edit', 'certificates_list', 'certificate_form', 'postingan_list', 'postingan_create', 'postingan_edit', 'postingan_detail', 'profile_page'].includes(pageKey);
    // Distinguish create vs edit within the project_form flow — the create
    // blade renders #project-create-data, the edit blade renders
    // #project-edit-data. Used to skip the closing chatbot step in create mode.
    const isProjectCreateMode = isProjectFormFlow && Boolean(document.getElementById('project-create-data'));

    // Builds the complete step list for the current page from live DOM state.
    // Wrapped in a function (instead of a one-off const array) so it can be
    // recomputed every time the tour starts — otherwise re-opening the guide
    // after toggling collaborative mode would keep showing whatever state the
    // page was in when it first loaded, instead of the current toggle state.
    const buildAllSteps = () => {
        const pageSteps = [];

        // 1. Sidebar is common to all pages except the dedicated project form flow
        // and the newer page-specific guides that should only show their own steps.
        if (!isProjectFormFlow && !isPageSpecificGuide && document.getElementById('sidebar')) {
            pageSteps.push(step('#sidebar', t.sidebar.title, t.sidebar.desc));
        }

        // 2. Search is common on desktop/headers except the dedicated project form flow
        // and the newer page-specific guides that should only show their own steps.
        if (!isProjectFormFlow && !isPageSpecificGuide && document.getElementById('unified-search-input') && window.innerWidth >= 1024) {
            pageSteps.push(step('#unified-search-input', t.search.title, t.search.desc));
        }

        // 3. Page specific steps
        switch (pageKey) {
            case 'student_dashboard': {
                const dashboardRoot = document.querySelector('[data-dashboard-type="me"]');
                const profileComplete = dashboardRoot?.dataset.profileComplete === '1';
                const headerDesc = profileComplete ? t.student_dashboard.header.complete_desc : t.student_dashboard.header.incomplete_desc;

                if (document.getElementById('main-header')) {
                    pageSteps.push(step('#main-header', t.student_dashboard.header.title, headerDesc));
                }
                if (document.getElementById('sidebar-settings-button')) {
                    pageSteps.push(step('#sidebar-settings-button', t.student_dashboard.settings.title, t.student_dashboard.settings.desc));
                }
                if (document.getElementById('sidebar-profile-button')) {
                    pageSteps.push(step('#sidebar-profile-button', t.student_dashboard.profile_menu.title, t.student_dashboard.profile_menu.desc));
                }
                if (document.getElementById('total-lrn-wrapper')) {
                    pageSteps.push(step('#total-lrn-wrapper', t.student_dashboard.stats_lrn.title, t.student_dashboard.stats_lrn.desc));
                }
                if (document.getElementById('total-pjt-wrapper')) {
                    pageSteps.push(step('#total-pjt-wrapper', t.student_dashboard.stats_pjt.title, t.student_dashboard.stats_pjt.desc));
                }
                if (document.getElementById('total-stk-wrapper')) {
                    pageSteps.push(step('#total-stk-wrapper', t.student_dashboard.stats_stk.title, t.student_dashboard.stats_stk.desc));
                }
                if (document.querySelector('.create-post-box')) {
                    pageSteps.push(step('.create-post-box', t.student_dashboard.create_post.title, t.student_dashboard.create_post.desc));
                }
                if (document.getElementById('postingan-section')) {
                    pageSteps.push(step('#postingan-section', t.student_dashboard.posts.title, t.student_dashboard.posts.desc));
                }
                break;
            }

            case 'dosen_dashboard':
                if (document.getElementById('stats-wrapper')) {
                    pageSteps.push(step('#stats-wrapper', t.dosen_dashboard.stats.title, t.dosen_dashboard.stats.desc));
                }
                break;

            case 'admin_dashboard': {
                const quickAction = document.querySelector('a[href*="ViewCreate"]') || document.querySelector('a[href*="manageUser"]');
                if (quickAction) {
                    pageSteps.push(step(quickAction, t.admin_dashboard.stats.title, t.admin_dashboard.stats.desc));
                }
                break;
            }

            case 'projects_list': {
                if (document.getElementById('project-user-container') || document.getElementById('all-projects-container')) {
                    const containerSelector = document.getElementById('all-projects-container') ? '#all-projects-container' : '#project-user-container';
                    pageSteps.push(step(containerSelector, t.projects_list.container.title, t.projects_list.container.desc));
                }
                const addProjectBtn = document.getElementById('project-list-add-btn') || document.querySelector('a[href*="project/create"], .sb-item[href*="project/create"]');
                if (addProjectBtn) {
                    pageSteps.push(step('#project-list-add-btn', t.projects_list.add_btn.title, t.projects_list.add_btn.desc));
                }
                const projectGrid = document.getElementById('project-list-grid');
                if (projectGrid) {
                    pageSteps.push(step('#project-list-grid', t.projects_list.cards.title, t.projects_list.cards.desc));
                }
                const projectActionContainer = document.querySelector('#project-list-grid .project-action-buttons');
                if (projectActionContainer) {
                    pageSteps.push(step('#project-list-grid .project-action-buttons', t.projects_list.edit_btn.title, t.projects_list.edit_btn.desc));
                }
                break;
            }

            case 'project_detail': {
                if (document.getElementById('project-detail-status')) {
                    pageSteps.push(step('#project-detail-status', t.project_detail.status.title, t.project_detail.status.desc));
                }
                if (document.getElementById('project-detail-desc')) {
                    pageSteps.push(step('#project-detail-desc', t.project_detail.description.title, t.project_detail.description.desc));
                }
                if (document.getElementById('project-detail-team')) {
                    pageSteps.push(step('#project-detail-team', t.project_detail.team.title, t.project_detail.team.desc));
                }
                if (document.getElementById('project-detail-learning-corner')) {
                    pageSteps.push(step('#project-detail-learning-corner', t.project_detail.learning_corner.title, t.project_detail.learning_corner.desc));
                }
                if (document.getElementById('project-detail-add-learning-corner')) {
                    pageSteps.push(step('#project-detail-add-learning-corner', t.project_detail.add_learning_corner.title, t.project_detail.add_learning_corner.desc));
                }
                break;
            }

            case 'learning_corner_list': {
                if (document.getElementById('learning-corner-list-header')) {
                    pageSteps.push(step('#learning-corner-list-header', t.learning_corner_list.header.title, t.learning_corner_list.header.desc));
                }
                if (document.getElementById('learning-corner-list-create')) {
                    pageSteps.push(step('#learning-corner-list-create', t.learning_corner_list.create_btn.title, t.learning_corner_list.create_btn.desc));
                }
                if (document.getElementById('learning-corner-list-grid')) {
                    pageSteps.push(step('#learning-corner-list-grid', t.learning_corner_list.entries.title, t.learning_corner_list.entries.desc));
                }
                break;
            }

            case 'sertificates_list':
            case 'certificates_list': {
                if (document.getElementById('sertifikat-list-container')) {
                    pageSteps.push(step('#sertifikat-list-container', t.sertifikat_list.header.title, t.sertifikat_list.header.desc));
                } else if (document.getElementById('sertifikat-user-container')) {
                    pageSteps.push(step('#sertifikat-user-container', t.sertifikat_list.header.title, t.sertifikat_list.header.desc));
                }
                const createSertifikatBtn = document.getElementById('sertifikat-create-button') || document.querySelector('a[href*="sertifikat.create"], a[href*="sertifikat/create"]');
                if (createSertifikatBtn) {
                    pageSteps.push(step(createSertifikatBtn, t.sertifikat_list.create_btn.title, t.sertifikat_list.create_btn.desc));
                }
                const actionButtons = document.querySelector('.sertifikat-card .flex.gap-3.mt-4');
                if (actionButtons) {
                    pageSteps.push(step(actionButtons, t.sertifikat_list.edit_btn.title, t.sertifikat_list.edit_btn.desc));
                }
                const cardsSection = document.querySelector('#sertifikat-list-container .sertifikat-card, #sertifikat-user-container .sertifikat-card');
                if (cardsSection) {
                    pageSteps.push(step(cardsSection, t.sertifikat_list.cards.title, t.sertifikat_list.cards.desc));
                }
                break;
            }

            case 'certificate_form': {
                if (document.getElementById('nama_sertifikat')) {
                    pageSteps.push(step('#nama_sertifikat', t.sertifikat_form.name.title, t.sertifikat_form.name.desc));
                }
                if (document.getElementById('lembaga_penerbit')) {
                    pageSteps.push(step('#lembaga_penerbit', t.sertifikat_form.publisher.title, t.sertifikat_form.publisher.desc));
                }
                if (document.getElementById('tanggal_terbit')) {
                    pageSteps.push(step('#tanggal_terbit', t.sertifikat_form.date.title, t.sertifikat_form.date.desc));
                }
                const uploadSection = document.getElementById('file-upload-section');
                if (uploadSection && uploadSection.offsetParent !== null) {
                    pageSteps.push(step('#file-upload-section', t.sertifikat_form.upload.title, t.sertifikat_form.upload.desc));
                }
                if (document.getElementById('sertifikat-action-buttons')) {
                    pageSteps.push(step('#sertifikat-action-buttons', t.sertifikat_form.save.title, t.sertifikat_form.save.desc));
                } else {
                    const submitBtn = document.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        pageSteps.push(step(submitBtn, t.sertifikat_form.save.title, t.sertifikat_form.save.desc));
                    }
                }
                break;
            }

            case 'postingan_list': {
                if (document.getElementById('postingan-list-page')) {
                    pageSteps.push(step('#postingan-list-page', t.postingan_list.header.title, t.postingan_list.header.desc));
                }
                const createPostBtn = document.getElementById('postingan-add-btn') || document.querySelector('a[href*="postingan.create"]');
                if (createPostBtn) {
                    pageSteps.push(step(createPostBtn, t.postingan_list.create_btn.title, t.postingan_list.create_btn.desc));
                }
                const firstCardActions = document.querySelector('.post-card .post-card-actions');
                if (firstCardActions) {
                    pageSteps.push(step('.post-card .post-card-actions', t.postingan_list.actions.title, t.postingan_list.actions.desc));
                }
                break;
            }
            case 'postingan_create': {
                if (document.getElementById('judul')) {
                    pageSteps.push(step('#judul', t.postingan_create.title_input.title, t.postingan_create.title_input.desc));
                }
                if (document.getElementById('deskripsi')) {
                    pageSteps.push(step('#deskripsi', t.postingan_create.description_input.title, t.postingan_create.description_input.desc));
                }
                if (document.getElementById('add-item')) {
                    pageSteps.push(step('#add-item', t.postingan_create.add_item.title, t.postingan_create.add_item.desc));
                }
                const submitBtn = document.getElementById('postingan-submit-btn') || document.querySelector('button[type="submit"]');
                if (submitBtn) {
                    pageSteps.push(step(submitBtn, t.postingan_create.submit.title, t.postingan_create.submit.desc));
                }
                break;
            }

            case 'postingan_edit': {
                if (document.getElementById('judul')) {
                    pageSteps.push(step('#judul', t.postingan_edit.title_input.title, t.postingan_edit.title_input.desc));
                }
                if (document.getElementById('deskripsi')) {
                    pageSteps.push(step('#deskripsi', t.postingan_edit.description_input.title, t.postingan_edit.description_input.desc));
                }
                if (document.getElementById('add-item')) {
                    pageSteps.push(step('#add-item', t.postingan_edit.add_item.title, t.postingan_edit.add_item.desc));
                }
                const submitBtn = document.getElementById('postingan-update-btn') || document.querySelector('button[type="submit"]');
                if (submitBtn) {
                    pageSteps.push(step(submitBtn, t.postingan_edit.submit.title, t.postingan_edit.submit.desc));
                }
                break;
            }

            case 'postingan_detail': {
                if (document.getElementById('postingan-detail-container')) {
                    pageSteps.push(step('#postingan-detail-container', t.postingan_detail.container.title, t.postingan_detail.container.desc));
                }
                const actionButton = document.querySelector('#postingan-detail-container #postMenuButton');
                if (actionButton) {
                    pageSteps.push(step(actionButton, t.postingan_detail.actions.title, t.postingan_detail.actions.desc));
                }
                break;
            }

            case 'learning_corner_create': {
                if (document.getElementById('learning-corner-title-input')) {
                    pageSteps.push(step('#learning-corner-title-input', t.learning_corner_create.title_input.title, t.learning_corner_create.title_input.desc));
                }
                if (document.getElementById('learning-corner-add-item')) {
                    pageSteps.push(step('#learning-corner-add-item', t.learning_corner_create.add_item.title, t.learning_corner_create.add_item.desc));
                }
                if (document.getElementById('learning-corner-submit')) {
                    pageSteps.push(step('#learning-corner-submit', t.learning_corner_create.save.title, t.learning_corner_create.save.desc));
                }
                break;
            }

            case 'learning_corner_edit': {
                if (document.getElementById('learning-corner-title-input')) {
                    pageSteps.push(step('#learning-corner-title-input', t.learning_corner_edit.title_input.title, t.learning_corner_edit.title_input.desc));
                }
                if (document.getElementById('learning-corner-add-item')) {
                    pageSteps.push(step('#learning-corner-add-item', t.learning_corner_edit.add_item.title, t.learning_corner_edit.add_item.desc));
                }
                if (document.getElementById('learning-corner-submit')) {
                    pageSteps.push(step('#learning-corner-submit', t.learning_corner_edit.save.title, t.learning_corner_edit.save.desc));
                }
                break;
            }

            case 'project_form':
                pageSteps.push(...buildProjectFormSteps());
                break;

            case 'profile_page': {
                const profileFieldChecks = [
                    {
                        selector: '#profile-preview-placeholder',
                        missing: () => true,
                        highlight: '#profile-preview-placeholder'
                    },
                    {
                        selector: '#nama-display',
                        missing: (text) => !text || text === 'Mahasiswa',
                        highlight: '#nama-container'
                    },
                    {
                        selector: '#username-display',
                        missing: (text) => text.toLowerCase().includes('belum ada username'),
                        highlight: '#username-container'
                    },
                    {
                        selector: '#deskripsi-display',
                        missing: (text) => text.toLowerCase().includes('belum ada deskripsi'),
                        highlight: '#deskripsi-card'
                    },
                    {
                        selector: '#nim-display',
                        missing: (text) => text === '-' || !text,
                        highlight: '#nim-card'
                    },
                    {
                        selector: '#tanggal_lahir-display',
                        missing: (text) => text.toLowerCase().includes('klik untuk menambahkan tanggal lahir'),
                        highlight: '#tanggal_lahir-card'
                    },
                    {
                        selector: '#email-display',
                        missing: (text) => text === '-' || !text,
                        highlight: '#email-card'
                    },
                    {
                        selector: '#jurusan-display',
                        missing: (text) => text === '-' || !text,
                        highlight: '#jurusan-card'
                    },
                    {
                        selector: '#keahlian-display',
                        missing: (text) => text === '-' || !text,
                        highlight: '#keahlian-card'
                    },
                    {
                        selector: '#video-display',
                        missing: (text) => text.toLowerCase().includes('klik untuk menambahkan video'),
                        highlight: '#video-card'
                    }
                ];

                const sectionChecks = [
                    {
                        selector: '#education-section',
                        empty: () => !document.querySelector('#education-section .space-y-4 > div'),
                        title: t.profile_page.education_section.title,
                        desc: t.profile_page.education_section.desc
                    },
                    {
                        selector: '#experience-section',
                        empty: () => !document.querySelector('#experience-section .space-y-4 > div'),
                        title: t.profile_page.experience_section.title,
                        desc: t.profile_page.experience_section.desc
                    },
                    {
                        selector: '#project-section',
                        empty: () => !document.querySelector('#project-section .grid > div'),
                        title: t.profile_page.project_section.title,
                        desc: t.profile_page.project_section.desc
                    },
                    {
                        selector: '#certificate-section',
                        empty: () => !document.querySelector('#certificate-section .grid > div'),
                        title: t.profile_page.certificate_section.title,
                        desc: t.profile_page.certificate_section.desc
                    }
                ];

                // 1) LENGKAPI PROFIL — selalu jadi step paling awal
                const incompleteProfileField = profileFieldChecks.find((check) => {
                    const element = document.querySelector(check.selector);
                    if (!element) return false;
                    const text = element.textContent.trim();
                    return check.missing(text);
                });

                if (incompleteProfileField) {
                    pageSteps.push(step(incompleteProfileField.highlight, t.profile_page.incomplete.title, t.profile_page.incomplete.desc));
                    if (document.getElementById('form-profile')) {
                        pageSteps.push(step('#form-profile', t.profile_page.main_layout.title, t.profile_page.main_layout.desc));
                    }
                } else if (document.getElementById('form-profile')) {
                    pageSteps.push(step('#form-profile', t.profile_page.main_layout.title, t.profile_page.main_layout.desc));
                }

                // 2) PENDIDIKAN — section kosong + (jika modal terkait sedang terbuka)
                const educationCheck = sectionChecks.find(s => s.selector === '#education-section');
                if (educationCheck && document.querySelector(educationCheck.selector) && educationCheck.empty()) {
                    pageSteps.push(step(educationCheck.selector, educationCheck.title, educationCheck.desc));
                }

                if (document.getElementById('modal-pendidikan') && document.getElementById('modal-pendidikan').offsetParent !== null) {
                    if (document.getElementById('sekolah-search-input')) {
                        pageSteps.push(step('#sekolah-search-input', t.profile_page.education_modal.title, t.profile_page.education_modal.desc));
                    }
                    if (document.getElementById('add-pend-tahun_masuk')) {
                        pageSteps.push(step('#add-pend-tahun_masuk', t.profile_page.education_modal.start_year.title, t.profile_page.education_modal.start_year.desc));
                    }
                }

                if (document.getElementById('modal-detail-pendidikan') && document.getElementById('modal-detail-pendidikan').offsetParent !== null) {
                    if (document.getElementById('btn-edit-pend')) {
                        pageSteps.push(step('#btn-edit-pend', t.profile_page.education_edit.title, t.profile_page.education_edit.desc));
                    }
                    if (document.getElementById('btn-save-pend-edit')) {
                        pageSteps.push(step('#btn-save-pend-edit', t.profile_page.education_edit.save.title, t.profile_page.education_edit.save.desc));
                    }
                }

                // 3) PENGALAMAN KERJA — section kosong + (jika modal terkait sedang terbuka)
                const experienceCheck = sectionChecks.find(s => s.selector === '#experience-section');
                if (experienceCheck && document.querySelector(experienceCheck.selector) && experienceCheck.empty()) {
                    pageSteps.push(step(experienceCheck.selector, experienceCheck.title, experienceCheck.desc));
                }

                if (document.getElementById('modal-pengalaman') && document.getElementById('modal-pengalaman').offsetParent !== null) {
                    if (document.getElementById('form-nama_pt')) {
                        pageSteps.push(step('#form-nama_pt', t.profile_page.experience_modal.title, t.profile_page.experience_modal.desc));
                    }
                    if (document.getElementById('form-tahun_mulai')) {
                        pageSteps.push(step('#form-tahun_mulai', t.profile_page.experience_modal.start_year.title, t.profile_page.experience_modal.start_year.desc));
                    }
                }

                if (document.getElementById('modal-detail-pengalaman') && document.getElementById('modal-detail-pengalaman').offsetParent !== null) {
                    if (document.getElementById('btn-edit-pkj')) {
                        pageSteps.push(step('#btn-edit-pkj', t.profile_page.experience_edit.title, t.profile_page.experience_edit.desc));
                    }
                    if (document.getElementById('btn-save-pkj-edit')) {
                        pageSteps.push(step('#btn-save-pkj-edit', t.profile_page.experience_edit.save.title, t.profile_page.experience_edit.save.desc));
                    }
                }

                // 4) PROJECTS — section kosong
                const projectCheck = sectionChecks.find(s => s.selector === '#project-section');
                if (projectCheck && document.querySelector(projectCheck.selector) && projectCheck.empty()) {
                    pageSteps.push(step(projectCheck.selector, projectCheck.title, projectCheck.desc));
                }

                // 5) SERTIFIKAT — section kosong
                const certificateCheck = sectionChecks.find(s => s.selector === '#certificate-section');
                if (certificateCheck && document.querySelector(certificateCheck.selector) && certificateCheck.empty()) {
                    pageSteps.push(step(certificateCheck.selector, certificateCheck.title, certificateCheck.desc));
                }
                break;
            }
        }

        // 4. Chatbot helper is common to all pages, except the project create
        // flow and the new page-specific guides that should stay focused on
        // their own steps.
        if (!isProjectCreateMode && !isPageSpecificGuide && document.getElementById('chatBotButton')) {
            pageSteps.push(step('#chatBotButton', t.chatbot.title, t.chatbot.desc));
        }

        return pageSteps;
    };

    const steps = buildAllSteps();

    // Return if no steps found
    if (steps.length === 0) return;

    // Configure tour options factory
    let currentDriverInstance = null;
    const makeTour = (stepsArray) => {
        // Captured so onDestroyed can tell whether it's firing for the tour
        // instance we're still tracking, or a stale one we've already
        // replaced (e.g. during a rebuild) — avoids a late/duplicate
        // callback stomping on state that belongs to a newer instance.
        let instanceRef = null;
        instanceRef = driver({
            showProgress: true,
            steps: stepsArray,
            nextBtnText: t.nextBtnText,
            prevBtnText: t.prevBtnText,
            doneBtnText: t.doneBtnText,
            // Fires whenever the tour actually ends — user closes it (X /
            // overlay click / Escape) or finishes the last step. Without
            // this, tourRunning stayed true forever after the very first
            // close, so toggling collaborative mode later kept reopening
            // the guide even though the user was no longer using it.
            onDestroyed: () => {
                if (currentDriverInstance === instanceRef) {
                    tourRunning = false;
                    currentDriverInstance = null;
                }
            },
        });
        return instanceRef;
    };

    // Start tour function (rebuildable)
    let tourRunning = false;
    let rebuildRequested = false;
    const startTour = (startSteps = null, startIndex = 0) => {
        // Recompute from live DOM state whenever no explicit steps are passed in,
        // so re-opening the guide always reflects the collaborative toggle's
        // current on/off state instead of the state at page load.
        const stepsToUse = startSteps || buildAllSteps();

        // Tear down any active tour via driver.js's own destroy() method
        // BEFORE creating the new instance. This guarantees only one popover
        // is ever on screen: destroy() removes the current popover/overlay
        // DOM synchronously, unlike setSteps() (which only resets internal
        // state and leaves the old popover element orphaned in the DOM —
        // that was causing two popovers to show at once).
        if (currentDriverInstance) {
            try { currentDriverInstance.destroy(); } catch (e) {}
            currentDriverInstance = null;
        }

        if (startIndex === 0 && window.innerWidth < 1024 && stepsToUse[0] && stepsToUse[0].element === '#sidebar') {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && sidebar.classList.contains('-translate-x-full')) {
                const toggle = document.getElementById('toggle-sidebar');
                if (toggle) toggle.click();
            }
        }

        currentDriverInstance = makeTour(stepsToUse);
        tourRunning = true;
        rebuildRequested = false;
        // Passing the index lets us resume mid-tour (e.g. after the
        // collaborative toggle changes) instead of always restarting at step 0.
        currentDriverInstance.drive(startIndex);
    };

    window.hideUserGuide = function () {
        if (currentDriverInstance) {
            try { currentDriverInstance.destroy(); } catch (e) {}
            currentDriverInstance = null;
        }
        tourRunning = false;
    };

    // Watch for collaborative toggle changes while tour runs — when user changes toggle,
    // rebuild the step list and resume right after the toggle step, instead of
    // restarting the whole tour from step 1.
    const collaborativeToggleEl = document.getElementById('project-collaborative-toggle');
    const rebuildAndRestart = () => {
        // Build fresh steps same as in the initial tour, reusing the shared
        // buildProjectFormSteps() so this never drifts out of sync with it.
        const freshSteps = [];
        if (!isProjectFormFlow && document.getElementById('sidebar')) freshSteps.push(step('#sidebar', t.sidebar.title, t.sidebar.desc));
        if (!isProjectFormFlow && document.getElementById('unified-search-input') && window.innerWidth >= 1024) freshSteps.push(step('#unified-search-input', t.search.title, t.search.desc));
        if (pageKey === 'project_form') freshSteps.push(...buildProjectFormSteps());
        if (!isProjectCreateMode && document.getElementById('chatBotButton')) freshSteps.push(step('#chatBotButton', t.chatbot.title, t.chatbot.desc));

        // Safety net: if the member-role modal happened to be open, close it
        // before tearing down the tour instance.
        const modalEl = document.getElementById('userModal');
        if (modalEl && !modalEl.classList.contains('hidden')) {
            modalEl.classList.add('hidden');
        }

        // Resume right after the collaborative toggle step (same spot the user
        // just interacted with), so switching the toggle behaves like clicking "Next".
        const toggleSelector = document.getElementById('project-collaborative-wrapper')
            ? '#project-collaborative-wrapper'
            : '#project-collaborative-toggle';
        const toggleIndex = freshSteps.findIndex(s => s.element === toggleSelector);
        const resumeIndex = toggleIndex >= 0 ? Math.min(toggleIndex + 1, freshSteps.length - 1) : 0;

        startTour(freshSteps, resumeIndex);
    };

    if (collaborativeToggleEl) {
        collaborativeToggleEl.addEventListener('change', () => {
            if (!tourRunning) return;
            // Immediately rebuild and restart tour to reflect new branch
            rebuildAndRestart();
        });
    }

    // Note: we previously used a click-intercept heuristics to rebuild the tour when
    // the user clicked Next after toggling. That approach was fragile and caused
    // conflicts with driver.js DOM operations, so we now rebuild immediately on
    // the toggle change and avoid the click interceptor entirely.

    // Auto-run if not yet seen for this page
    const storageKey = `seen_user_guide_${pageKey}`;
    if (!localStorage.getItem(storageKey)) {
        setTimeout(() => {
            startTour();
            localStorage.setItem(storageKey, 'true');
        }, 1500); // 1.5 seconds delay for a smooth entrance
    }

    // Click handler to trigger manually.
    // Special case: if the user is currently on the project form AND has the
    // "pilih user" modal (#userModal) open when they click bantuan, jump
    // straight to that step (member_role / step 5) instead of restarting the
    // whole tour from step 1 — matches where the user already is in the flow.
    
    const handleGuideClick = (e) => {
        e.preventDefault();

        if (isProjectFormFlow) {
            const modalEl = document.getElementById('userModal');
            const modalIsOpen = modalEl && !modalEl.classList.contains('hidden');
            if (modalIsOpen) {
                const freshSteps = buildAllSteps();
                const memberRoleIndex = freshSteps.findIndex(s => s.element === '#userModal');
                if (memberRoleIndex >= 0) {
                    startTour(freshSteps, memberRoleIndex);
                    return;
                }
            }
        }

        if (pageKey === 'profile_page') {
            const freshSteps = buildAllSteps();
            const modalSteps = [
                '#sekolah-search-input',
                '#add-pend-tahun_masuk',
                '#btn-edit-pend',
                '#btn-save-pend-edit',
                '#form-nama_pt',
                '#form-tahun_mulai',
                '#btn-edit-pkj',
                '#btn-save-pkj-edit'
            ];
            const modalIndex = modalSteps
                .map(selector => freshSteps.findIndex(s => s.element === selector))
                .find(index => index >= 0);
            if (modalIndex >= 0) {
                startTour(freshSteps, modalIndex);
                return;
            }
        }

        startTour();
    };

    if (guideBtn) {
        guideBtn.addEventListener('click', handleGuideClick);
    }
    
    if (guideBtnMobile) {
        guideBtnMobile.addEventListener('click', handleGuideClick);
    }
}