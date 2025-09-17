<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use App\Models\User;
use App\Models\Admin;
use App\Models\Produk;
use App\Models\Kesenian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tokoCount = Toko::count();
        $produkCount = Produk::count();
        $kesenianCount = Kesenian::count();
        $userCount = User::count();
        $adminCount = Admin::count();

        // Statistik kesenian
        $kesenianDates = Kesenian::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('date');
        $kesenianCounts = Kesenian::selectRaw('COUNT(*) as count, DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count');

        // Statistik produk
        $produkDates = Produk::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('date');
        $produkCounts = Produk::selectRaw('COUNT(*) as count, DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count');

        // Statistik pengguna
        $userDates = User::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('date');
        $userCounts = User::selectRaw('COUNT(*) as count, DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count');

        return view('dashboard.index', compact(
            'tokoCount', 
            'produkCount',
            'kesenianCount',
            'userCount',
            'adminCount',
            'kesenianDates',
            'kesenianCounts',
            'produkDates',
            'produkCounts',
            'userDates',
            'userCounts'
        ));
    }
}
