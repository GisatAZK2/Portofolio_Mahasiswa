<?php

use App\Http\Controllers\v1\NotificationController;
use App\Http\Controllers\v1\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\v1\UserController;
use App\Http\Controllers\v1\ProjekController;
use App\Http\Controllers\v1\DashboardController;
use App\Http\Controllers\v1\LearningCornerController;
use App\Http\Controllers\v1\SertifikatController;
use App\Http\Controllers\v1\DosenController;
use App\Http\Controllers\v1\PostinganController;
use App\Http\Controllers\v1\LikedPostinganController;

use App\Http\Controllers\v1\KomentarController;
use App\Http\Controllers\GameController;



Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ========== LOCALE PREFIX ROUTES ==========
// Routes dengan locale prefix untuk SEO dan proper multilingual support
Route::prefix('{locale}')
    ->where(['locale' => 'id|en'])        
    ->middleware(['web', 'setlocale'])      
    ->group(function () {


        // Halaman guest
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/search', [DashboardController::class, 'search'])->name('search');
        Route::get('/search-suggestions', [DashboardController::class, 'searchSuggestions'])->name('search.suggestions');
        Route::get('/pagination-fragment', [DashboardController::class, 'paginationFragment'])->name('pagination.fragment');
        Route::resource('komentar', KomentarController::class)->only([
            'index',
            'create',
            'store',
        ]);

        
        // Semua route yang butuh login
        Route::middleware(['auth', 'role:mahasiswa'])->group(function () {

            Route::get('/MyDashboard', [DashboardController::class, 'myDashboard'])->name('dashboard.me');

            Route::get('/profile-page', function () {
                return view('views_profile_page');
            })->name('profile-page');

            // CRUD Project
            Route::resource('project', ProjekController::class)->only([
                'index',
                'create',
                'store'
            ]);

            Route::get('/projectUser/edit', [ProjekController::class, 'edit'])->name('project.edit');
            Route::put('/projectUser/update', [ProjekController::class, 'update'])->name('project.update');
            Route::delete('/projectUser/delete', [ProjekController::class, 'destroy'])->name('project.destroy');

            
            //CRUD Sertifikat
            Route::resource('sertifikat', SertifikatController::class)->only([
                'index',
                'create',
                'store',
            ]);
            // CRUD Sertifikat - Ganti dengan route manual yang support query parameter
            Route::get('/sertifikatUser/edit', [SertifikatController::class, 'edit'])->name('sertifikat.edit');
            Route::put('/sertifikatUser/update', [SertifikatController::class, 'update'])->name('sertifikat.update');
            Route::delete('/sertifikat/delete', [SertifikatController::class, 'destroy'])->name('sertifikat.destroy');


            Route::get('/profile', [UserController::class, 'profile'])->name('profile');
            Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

            Route::prefix('keahlian-tambahan')->name('keahlian-tambahan.')->group(function () {
                Route::get('/', [UserController::class, 'keahliantambahanlist'])->name('index');
                Route::post('/', [UserController::class, 'storeKeahlianTambahan'])->name('store');
            });
            // Keahlian Tambahan routes - handles /id/keahlian-tambahan?keahlian_tambahan=id or /en/keahlian-tambahan?keahlian_tambahan=id
            Route::delete('/keahlian-tambahan/destroy', [UserController::class, 'destroyKeahlianTambahan'])->name('destroy');

        });


        Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

            Route::get('/dashboard', [AdminController::class, 'index'])->name('index');

            Route::prefix('manageUser')->name('users.')->group(function () {
                Route::get('/', [AdminController::class, 'ListUser'])->name('index');
                Route::get('/AddUser', [AdminController::class, 'ViewAddUser'])->name('ViewCreate');
                Route::post('/StoreUser', [AdminController::class, 'AddUser'])->name('StoreUser');
                Route::get('/Details', [AdminController::class, 'DetailsUser'])->name('details');
                Route::patch('/edit', [AdminController::class, 'UpdateUser'])->name('edit');
                Route::delete('/DeleteUser', [AdminController::class, 'destroyUser'])->name('destroy');
                Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyUsers'])->name('bulkDestroy');
                Route::patch('/update-status', [UserController::class, 'updateStatus'])->name('update-status');
            });

            Route::prefix('manageUserKeahlianTambahan')->name('users.keahlian-tambahan.')->group(function () {
                Route::get('/', [AdminController::class, 'ListUserKeahlianTambahan'])->name('index');
            });

            Route::prefix('manageSertifikat')->name('sertifikat.')->group(function () {
                Route::get('/', [AdminController::class, 'sertifikat'])->name('index');
                Route::get('/AddSertifikat', [AdminController::class, 'TambahSertifikat'])->name('create');
                Route::post('/store', [AdminController::class, 'StoreSertifikat'])->name('store');
                Route::get('/Details', [AdminController::class, 'DetailsSertifikat'])->name('details');
                Route::patch('/edit', [AdminController::class, 'UpdateSertifikat'])->name('update');
                Route::delete('/DeleteSertifikat', [AdminController::class, 'DestroySertifikat'])->name('destroy');
                Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroy'])->name('bulk-destroy');
            });

            Route::patch('/sertifikat/approve', [AdminController::class, 'approve'])->name('sertifikat.approve');
            Route::patch('/sertifikat/reject', [AdminController::class, 'reject'])->name('sertifikat.reject');

            Route::prefix('manageAngkatan')->name('angkatan.')->group(function () {
                Route::get('/', [AdminController::class, 'ListAngkatan'])->name('index');
                Route::get('/AddAngkatan', [AdminController::class, 'TambahAngkatan'])->name('create');
                Route::post('/store', [AdminController::class, 'StoreAngkatan'])->name('store');
                Route::get('/Details', [AdminController::class, 'DetailsAngkatan'])->name('details');
                Route::patch('/edit', [AdminController::class, 'UpdateAngkatan'])->name('update');
                Route::delete('/DeleteAngkatan', [AdminController::class, 'DestroyAngkatan'])->name('destroy');
                Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyAngkatan'])->name('bulk-destroy');
            });

            Route::prefix('manageProdi')->name('prodi.')->group(function () {
                Route::get('/', [AdminController::class, 'ListProdi'])->name('index');
                Route::get('/AddProdi', [AdminController::class, 'TambahProdi'])->name('create');
                Route::post('/store', [AdminController::class, 'StoreProdi'])->name('store');
                Route::get('/Details', [AdminController::class, 'DetailsProdi'])->name('details');
                Route::patch('/edit', [AdminController::class, 'UpdateProdi'])->name('update');
                Route::delete('/DeleteProdi', [AdminController::class, 'DestroyProdi'])->name('destroy');
                Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyProdi'])->name('bulk-destroy');
            });

            Route::prefix('manageKeahlian')->name('keahlian.')->group(function () {
                Route::get('/', [AdminController::class, 'ListKeahlian'])->name('index');
                Route::get('/AddKeahlian', [AdminController::class, 'TambahKeahlian'])->name('create');
                Route::post('/store', [AdminController::class, 'StoreKeahlian'])->name('store');
                Route::get('/Details', [AdminController::class, 'DetailsKeahlian'])->name('details');
                Route::patch('/edit', [AdminController::class, 'UpdateKeahlian'])->name('update');
                Route::delete('/DeleteKeahlian', [AdminController::class, 'DestroyKeahlian'])->name('destroy');
                Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyKeahlian'])->name('bulk-destroy');
            });

            Route::prefix('manageProject')->name('projects.')->group(function () {
                Route::get('/', [AdminController::class, 'projects'])->name('index');
                Route::get('/create', [AdminController::class, 'TambahProjects'])->name('create');
                Route::post('/store', [AdminController::class, 'StoreProject'])->name('store');
                Route::get('/Details', [AdminController::class, 'EditProjects'])->name('details');
                Route::put('/EditProject', [AdminController::class, 'UpdateProject'])->name('update');
                Route::delete('/DeleteProject', [AdminController::class, 'DestroyProject'])->name('delete');
                Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyProject'])->name('bulk-delete');
            });

            Route::prefix('manageNotifications')->name('notifications.')->group(function () {
                Route::get('/', [AdminController::class, 'notifications'])->name('index');
                Route::get('/create', [AdminController::class, 'ViewAddNotification'])->name('create');
                Route::post('/store', [AdminController::class, 'StoreNotification'])->name('store');
                Route::get('/edit', [AdminController::class, 'EditNotification'])->name('edit');
                Route::patch('/update', [AdminController::class, 'UpdateNotification'])->name('update');
                Route::delete('/delete', [AdminController::class, 'DestroyNotification'])->name('destroy');
                Route::delete('/bulk-destroy', [AdminController::class, 'bulkDestroyNotification'])->name('bulk-destroy');
                Route::get('/load-users', [AdminController::class, 'loadUsersForNotification'])->name('load-users');
            });

        });

        Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
            Route::get('/dashboard', [DosenController::class, 'index'])->name('dashboard');

            Route::prefix('manageUser')->name('users.')->group(function () {
                Route::get('/', [DosenController::class, 'ListUser'])->name('index');
                Route::get('/AddUser', [DosenController::class, 'ViewAddUser'])->name('ViewCreate');
                Route::post('/StoreUser', [DosenController::class, 'AddUser'])->name('StoreUser');
                Route::get('/Details', [DosenController::class, 'DetailsUser'])->name('details');
                Route::patch('/edit', [DosenController::class, 'UpdateUser'])->name('edit');
                Route::delete('/DeleteUser', [DosenController::class, 'destroyUser'])->name('destroy');
                Route::patch('/update-status', [DosenController::class, 'updateStatus'])->name('update-status');
            });

            Route::prefix('manageSertifikat')->name('sertifikat.')->group(function () {
                Route::get('/', [DosenController::class, 'sertifikat'])->name('index');
                Route::get('/AddSertifikat', [DosenController::class, 'TambahSertifikat'])->name('create');
                Route::post('/store', [DosenController::class, 'StoreSertifikat'])->name('store');
                Route::get('/Details', [DosenController::class, 'DetailsSertifikat'])->name('details');
                Route::patch('/edit', [DosenController::class, 'UpdateSertifikat'])->name('update');
                Route::delete('/DeleteSertifikat', [DosenController::class, 'DestroySertifikat'])->name('destroy');
                Route::delete('/bulk-destroy', [DosenController::class, 'bulkDestroy'])->name('bulk-destroy');
            });

            Route::patch('/sertifikat/approve', [DosenController::class, 'approve'])->name('sertifikat.approve');
            Route::patch('/sertifikat/reject', [DosenController::class, 'reject'])->name('sertifikat.reject');

            Route::prefix('manageProject')->name('projects.')->group(function () {
                Route::get('/', [DosenController::class, 'projects'])->name('index');
                Route::get('/create', [DosenController::class, 'TambahProjects'])->name('create');
                Route::post('/store', [DosenController::class, 'StoreProject'])->name('store');
                Route::get('/Details', [DosenController::class, 'EditProjects'])->name('details');
                Route::put('/EditProject', [DosenController::class, 'UpdateProject'])->name('update');
                Route::delete('/DeleteProject', [DosenController::class, 'DestroyProject'])->name('delete');
                Route::delete('/bulk-destroy', [DosenController::class, 'bulkDestroyProject'])->name('bulk-delete');
            });
        });

        Route::resource('postingan', PostinganController::class)->only([
                'index',
                'create',
                'store',
            ]);

            Route::get('/postinganUser/edit', [PostinganController::class, 'edit'])->name('postingan.edit');
            Route::put('/postinganUser/update', [PostinganController::class, 'update'])->name('postingan.update');
            Route::delete('/postinganUser/delete', [PostinganController::class, 'destroy'])->name('postingan.destroy');


        // Portfolio route - handles /id/portofolio?user=username or /en/portofolio?user=username
        Route::get('/portofolio', [DashboardController::class, 'show'])->name('portfolio.show');

        // Project routes - handles /id/project?project=id or /en/project?project=id
        Route::get('/projectUser', [ProjekController::class, 'show'])->name('project.show');

        Route::get('/ProjectMahasiswa', [ProjekController::class, 'project_user'])->name('project.project_user');

        // Postingan routes - handles /id/postingan?postingan=id or /en/postingan?postingan=id
        Route::get('/postinganUser', [PostinganController::class, 'show'])->name('postingan.show');

        // Learning Corner routes - handles /id/learning-corner?learning_corner=id or /en/learning-corner?learning_corner=id
        // CRUD Learning Corner - Ubah ke query parameter untuk support locale
        Route::get('/learning-corner/create', [LearningCornerController::class, 'create'])
            ->name('learning-corner.create');
        Route::post('/learning-corner/store', [LearningCornerController::class, 'store'])
            ->name('learning-corner.store');

        Route::get('/learning-corner/edit', [LearningCornerController::class, 'edit'])
            ->name('learning-corner.edit');
        Route::put('/learning-corner/update', [LearningCornerController::class, 'update'])
            ->name('learning-corner.update');
        Route::delete('/learning-corner/delete', [LearningCornerController::class, 'destroy'])
            ->name('learning-corner.destroy');



        // Mass destroy tetap menggunakan POST dengan query parameter
        Route::post('/learning-corner/mass-destroy', [LearningCornerController::class, 'massDestroy'])
            ->name('learning-corner.mass-destroy');



        // Komentar routes - handles /id/komentar?komentar=id or /en/komentar?komentar=id
        Route::get('/komentar/edit', [KomentarController::class, 'edit'])->name('komentar.edit');
        Route::put('/komentar/update', [KomentarController::class, 'update'])->name('komentar.update');
        Route::delete('/komentar/delete', [KomentarController::class, 'destroy'])->name('komentar.destroy');

        // Like/unlike postingan - handles /id/postingan/{postingan}/toggle-like or /en/postingan/{postingan
        Route::post('/postingan/toggle-like', [LikedPostinganController::class, 'toggle'])->name('postingan.toggle-like');

        // Game routes
        Route::get('/game-matematika', [GameController::class, 'mtk'])->name('game.matematika');
        Route::get('/game/puzzle', [GameController::class, 'puzzle'])->name('game.puzzle');
        Route::get('/game/tts', [GameController::class, 'tts'])->name('game.tts');

        Route::post('/game/save-score', [GameController::class, 'saveScore'])->name('game.saveScore')->middleware('auth');
        Route::get('/game/leaderboard', [GameController::class, 'leaderboard'])->name('game.leaderboard');
        Route::post('/game/get-highest-score', [GameController::class, 'getHighestScore'])->name('game.getHighestScore');

        // Footer Routes
        Route::get('/help', function () {
            return view('components.help');
        })->name('help');

        Route::get('/get-app', function () {
            return view('components.get-app');
        })->name('get-app');
        
        // Offline page route
        Route::get('/offline', function () {
            return view('components.offline');
        })->name('offline');

        Route::prefix('api/notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/clear-all', [NotificationController::class, 'clearAll']);
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
});
      
    });

// Outside of locale prefix group

  // Offline page route
        Route::get('/offline', function () {
            return view('offline');
        })->name('offline');

            Route::get('/help', function () {
            return view('components.help');
        })->name('help');

        Route::get('/get-app', function () {
            return view('components.get-app');
        })->name('get-app');
        
        // Offline page route
        Route::get('/offline', function () {
            return view('components.offline');
        })->name('offline');


Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Forgot Password Routes
        Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.forgot');
        Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendOtp'])->name('password.sendOtp');
        Route::get('/verify-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showVerifyOtpForm'])->name('password.verify.form');
        Route::post('/verify-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyOtp'])->name('password.verify');
        Route::post('/resend-otp', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resendOtp'])->name('password.resendOtp');
        Route::get('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset.form');
        Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

Route::get('/pengajuan-akun', [UserController::class, 'showRegister'])->name('pengajuan-akun');
Route::post('/pengajuan-akun', [UserController::class, 'register'])->name('register');


Route::post('/toggle-sidebar', function (Request $request) {
    Session::put('sidebar_collapsed', $request->collapsed);
    return response()->json(['success' => true]);
})->middleware('web');

// Auth routes (bisa di luar middleware auth)
Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register']);


Route::get('/learning-corner-mahasiswa', [LearningCornerController::class, 'learning_corner_user'])->name('learning-corner-mahasiswa');
Route::get('/sertifikat-mahasiswa', [SertifikatController::class, 'sertifikat_user'])->name('sertifikat-mahasiswa');


Route::middleware(['auth'])->group(function () {

    Route::post(
        '/project/{project}/tasks',
        [ProjekController::class, 'storeTask']
    )->name('project.tasks.store');
    Route::patch(
        '/project/{project}/tasks/{task}',
        [ProjekController::class, 'updateTask']
    )->name('project.tasks.update');
    Route::patch(
        '/project/{project}/tasks/{task}/complete',
        [ProjekController::class, 'completeTask']
    )->name('project.tasks.complete');
    Route::delete(
        '/project/{project}/tasks/{task}',
        [ProjekController::class, 'destroyTask']
    )->name('project.tasks.destroy');

    Route::post(
        '/learning-corner-mass/mass-destroy',
        [LearningCornerController::class, 'massDestroy']
    )->name('learning-corner.mass-destroy');


    Route::delete('/learning-corner/{learning_corner}', [LearningCornerController::class, 'destroy'])
        ->name('learning-corner.destroy')
        ->middleware('role:admin,dosen,mahasiswa');

});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::prefix('manageUserKeahlianTambahan')->name('users.keahlian-tambahan.')->group(function () {
        Route::patch('/{id}/approve', [AdminController::class, 'approveKeahlianTambahan'])->name('approve');
        Route::patch('/{id}/reject', [AdminController::class, 'rejectKeahlianTambahan'])->name('reject');
    });

});

Route::middleware(['auth'])->group(function () {
    Route::patch('/user/{id}/update-status', [App\Http\Controllers\v1\UserController::class, 'updateStatusPengajuan'])
        ->name('user.update-status')
        ->middleware('role:admin,dosen');
});

// Route::get('/test-translate', function () {
//     $text = "Halo Saya Cuman Ingin test Translate berbasis teks get.";

//     app()->setLocale('en');
//     $en = autoTranslate($text);

//     app()->setLocale('es');
//     $es = autoTranslate($text);

//     dd([
//         'original' => $text,
//         'english' => $en,
//         'spanish' => $es,
//     ]);
// });


// Route untuk testing notification 
Route::get('/test-notification', function () {
    \App\Http\Controllers\v1\NotificationController::add(
        'user-registered',
        [
            'title' => 'Test Notification',
            'message' => 'This is a test notification at ' . now()->format('H:i:s'),
            'user_id' => 1,
            'user_name' => 'Test User',
            'link' => '/',
        ],
        'high'
    );
    
    return 'Notification sent!';
});

   




