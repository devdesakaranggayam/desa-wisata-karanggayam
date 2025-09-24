<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Produk;
use App\Models\Wisata;
use App\Models\Kesenian;
use Illuminate\Support\Facades\Route;

if (!function_exists('is_active_sidebar')) {
    function is_active_sidebar(array $routes) {
        $current = Route::currentRouteName();
        $active = in_array($current, $routes);
        if ($active) {
            return "active open";
        }

        return "";
    }
}

if (!function_exists('log_error')) {
    function log_error($err):void {
        $msg = sprintf("%s in %s:%s", $err->getMessage(), $err->getFile(), $err->getLine());
        Log::error($msg);
    }
}

if (!function_exists('error_msg')) {
    function error_msg(string $type) : string {
        switch ($type) {
            case 'general':
                $result = 'Terjadi Kesalahan, silahkan hubungi admin.';
                break;
            case 'insert':
                $result = 'Terjadi kesalahan saat menyimpan data.';
                break;
            case 'update':
                $result = 'Terjadi kesalahan saat mengubah data.';
                break;
            case 'delete':
                $result = 'Terjadi kesalahan menghapus data.';
                break;
            default:
                $result = '';
                break;
        }

        return $result;
    }
}

if (!function_exists('success_msg')) {
    function success_msg(string $type) : string {
        switch ($type) {
            case 'insert':
                $result = 'Data berhasil disimpan.';
                break;
            case 'update':
                $result = 'Data berhasil dirubah.';
                break;
            case 'delete':
                $result = 'Data berhasil dihapus.';
                break;
            default:
                $result = '';
                break;
        }

        return $result;
    }
}

if (!function_exists('datepicker_tosql')) {
    function datepicker_tosql($strDate) {
        $date = Carbon::createFromFormat('d-m-Y', $strDate);
        return $date->format('Y-m-d');
    }
}

if (!function_exists('money_indo_tosql')) {
    function money_indo_tosql($value) {
        if (!$value) {
            return 0;
        }
        
        $removedDot = str_replace('.','',$value);
        return str_replace(',', '.', $removedDot);
    }
}

if (!function_exists('money')) {
    function money($value) {
        return number_format($value, 2, ',', '.');
    }
}

if (!function_exists('number_format_id')) {
    function number_format_id($value) {
        return number_format($value,0,',', '.');
    }
}

if (!function_exists('sql_to_datepicker')) {
    function to_datepicker($value) {
        $date = Carbon::parse($value);
        return $date->format('d-m-Y');
    }
}

function list_bulan() {
    return [
        '1'=>'Januari', 
        '2'=>'Februari', 
        '3'=>'Maret', 
        '4'=>'April', 
        '5'=>'Mei', 
        '6'=>'Juni', 
        '7'=>'Juli', 
        '8'=>'Agustus',
        '9'=>'September', 
        '10'=>'Oktober', 
        '11'=>'November', 
        '12'=>'Desember'
    ];
}

if (!function_exists('tgl_indo')) {
    function tgl_indo($value, $type='date') {
        if (!$value) {
            return '-';
        }

        $date = Carbon::parse($value);
        $year = $date->year;
        $month = $date->month;
        $day = $date->day;

        $result = $day. ' ' . list_bulan()[$month] .' '. $year;

        if ($type == 'datetime') {
            $result .= ' ' .$date->format('H:i:s');
        }

        return $result;
    }
}


if (!function_exists('get_daterange')) {
    function get_daterange($value, array $resultKeys = []) {
        $keys = ['start_date','end_date'];
        if ($resultKeys) {
            $keys[0] = $resultKeys[0];
            $keys[1] = $resultKeys[1];
        }
        
        if (!$value) {
            return [
                $keys[0] => null,
                $keys[1] => null
            ];
        }

        $datesArray = explode(' sd ', $value);        
        return [
            $keys[0] => datepicker_tosql($datesArray[0]),
            $keys[1] => datepicker_tosql($datesArray[1]),
        ];
    }
}

if (! function_exists('user')) {
    function user() {
        return auth('web')->user();
    }
}

if (! function_exists('admin')) {
    function admin() {
        return auth('admin')->user();
    }
}

if (! function_exists('random_kesenian')) {
    function random_kesenian($limit, $excludeId = null) {
        return \App\Models\Kesenian::when($excludeId, function ($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->with('files')
            ->inRandomOrder()
            ->take($limit)
            ->get()
            ->map(function ($item) {
                $item->type = 'kesenian';
                return $item;
            });
    }
}

if (! function_exists('random_wisata')) {
    function random_wisata($limit, $excludeId = null) {
        return \App\Models\Wisata::when($excludeId, function ($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->with('files')
            ->inRandomOrder()
            ->take($limit)
            ->get()
            ->map(function ($item) {
                $item->type = 'wisata';
                return $item;
            });
    }
}

if (! function_exists('random_produk')) {
    function random_produk($limit, $excludeId = null) {
        return Produk::when($excludeId, function ($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->with('files')
            ->inRandomOrder()
            ->take($limit)
            ->get()
            ->map(function ($item) {
                $item->type = 'produk';
                return $item;
            });
    }
}

if (! function_exists('map_files')) {
    function map_files($files) {
        $result = [];
        foreach ($files as $index => $file) {
            $result[] = [
                "id" => $file->id,
                "nama" => $file->nama,
                "tipe_file" => $file->tipe_file,
                "urutan" => $file->urutan ?? $index,
                "file_url" => $file->file_url
            ];
        }
        return $result;
    }
}

if (! function_exists('default_img')) {
    function default_img($tipe) {
        // mapping tipe ke file di folder public/assets/img/default/
        $defaults = [
            'produk'   => 'assets/img/default/produk.png',
            'wisata'   => 'assets/img/default/wisata.png',
            'kesenian' => 'assets/img/default/kesenian.png',
            'default'  => 'assets/img/default/default.png',
        ];

        // cek apakah ada di mapping
        $file = $defaults[$tipe] ?? $defaults['default'];

        // balikin full url (pakai helper asset)
        return asset($file);
    }
}


if (! function_exists('is_superadmin')) {
    function is_superadmin() {
        return auth('admin')->user()->is_superadmin ?? false;
    }
}

if (! function_exists('is_dev')) {
    function is_dev() {
        return auth('admin')->user()->username == "developer" ?? false;
    }
}

if (! function_exists('search_produk')) {
    /**
     * Cari produk berdasarkan nama atau deskripsi
     *
     * @param string $query
     * @param int|null $limit
     * @param array $options ['sort_by' => 'kolom', 'sort_dir' => 'asc|desc']
     * @return \Illuminate\Support\Collection
     */
    function search_produk($query, $limit = null, $options = []) {
        $builder = Produk::where(function ($q) use ($query) {
                $q->where('nama', 'like', '%' . $query . '%')
                  ->orWhere('deskripsi', 'like', '%' . $query . '%');
            })
            ->with('files');

        // kalau ada opsi sorting
        if (isset($options['sort_by'])) {
            $sortBy  = $options['sort_by'];
            $sortDir = $options['sort_dir'] ?? 'asc'; // default asc
            $builder->orderBy($sortBy, $sortDir);
        }

        // kalau $limit ada → pakai limit(), kalau tidak → langsung get()
        $result = $limit ? $builder->limit($limit)->get() : $builder->get();

        return $result->map(function ($item) {
            $item->type = 'produk';
            return $item;
        });
    }
}


if (! function_exists('search_kesenian')) {
    /**
     * Cari kesenian berdasarkan nama atau deskripsi
     *
     * @param string $query
     * @param int|null $limit
     * @param array $options ['sort_by' => 'kolom', 'sort_dir' => 'asc|desc']
     * @return \Illuminate\Support\Collection
     */
    function search_kesenian($query, $limit = null, $options = []) {
        $builder = Kesenian::where(function ($q) use ($query) {
                $q->where('nama', 'like', '%' . $query . '%')
                  ->orWhere('deskripsi', 'like', '%' . $query . '%');
            })
            ->with('files');

        // kalau ada opsi sorting
        if (isset($options['sort_by'])) {
            $sortBy  = $options['sort_by'];
            $sortDir = $options['sort_dir'] ?? 'asc'; // default asc
            $builder->orderBy($sortBy, $sortDir);
        }

        $result = $limit ? $builder->limit($limit)->get() : $builder->get();

        return $result->map(function ($item) {
            $item->type = 'kesenian';
            return $item;
        });
    }
}

if (! function_exists('search_wisata')) {
    /**
     * Cari wisata berdasarkan nama atau deskripsi, dengan optional limit & sorting
     *
     * @param string $query
     * @param int|null $limit
     * @param array $options ['sort_by' => 'kolom', 'sort_dir' => 'asc|desc']
     * @return \Illuminate\Support\Collection
     */
    function search_wisata($query, $limit = null, $options = []) {
        $builder = Wisata::where(function ($q) use ($query) {
                $q->where('nama', 'like', '%' . $query . '%')
                  ->orWhere('deskripsi', 'like', '%' . $query . '%');
            })
            ->with('files');

        // kalau ada opsi sorting
        if (isset($options['sort_by'])) {
            $sortBy  = $options['sort_by'];
            $sortDir = $options['sort_dir'] ?? 'asc'; // default asc
            $builder->orderBy($sortBy, $sortDir);
        }

        $result = $limit ? $builder->limit($limit)->get() : $builder->get();

        return $result->map(function ($item) {
            $item->type = 'wisata';
            return $item;
        });
    }
}
