<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\Angkatan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL; 
use Illuminate\Http\Request;  

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void // Tambahkan Request $request di sini
    {
        Schema::defaultStringLength(191);

        /**
         * Solusi Otomatis Locale untuk Route
         * Ini akan memaksa semua fungsi route() menggunakan locale dari URL saat ini.
         */
        URL::defaults(['locale' => $request->segment(1)]);

        // View Composer untuk Header
        View::composer('components.header', function ($view) {
            $jurusanList = Jurusan::select('id_jurusan', 'nama_jurusan')
                ->orderBy('nama_jurusan')
                ->get();

            $keahlianList = Keahlian::select('id_keahlian', 'nama_keahlian')
                ->orderBy('nama_keahlian')
                ->get();

            $angkatanlist = Angkatan::select('id', 'nama_angkatan')
                ->orderBy('nama_angkatan')
                ->get();

            $view->with([
                'jurusanList'  => $jurusanList,
                'keahlianList' => $keahlianList,
                'angkatanList' => $angkatanlist
            ]);
        });
    }
}