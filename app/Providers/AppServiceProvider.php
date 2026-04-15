<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Jurusan;
use App\Models\Keahlian;
use App\Models\Angkatan;
use App\Models\User;
use App\Models\Project;
use App\Models\Sertifikat;
use App\Models\Postingan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;


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
    public function boot(): void
    {
         Schema::defaultStringLength(191);
        View::composer('components.header', function ($view) {   // <-- ganti 'layouts.header' dengan nama view header kamu
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
