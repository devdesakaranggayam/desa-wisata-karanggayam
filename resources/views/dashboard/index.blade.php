@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Dashboard</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Statistik Pengguna -->
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-header">Jumlah Pengguna</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $userCount }}</h5>
                            <p class="card-text">Jumlah pengguna terdaftar</p>
                        </div>
                    </div>
                </div>

                <!-- Statistik Kesenian -->
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-header">Jumlah Kesenian</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $kesenianCount }}</h5>
                            <p class="card-text">Kesenian terdaftar</p>
                        </div>
                    </div>
                </div>  

                <!-- Statistik Produk -->
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-header">Jumlah Produk</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $produkCount }}</h5>
                            <p class="card-text">Produk yang tersedia di sistem</p>
                        </div>
                    </div>
                </div>

                <!-- Statistik Toko -->
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-header">Jumlah Toko</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $tokoCount }}</h5>
                            <p class="card-text">Toko terdaftar di sistem</p>
                        </div>
                    </div>
                </div>         
            </div>

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mt-4">
                        <h5>Statistik Pengguna</h5>
                        <canvas id="userChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mt-4">
                        <h5>Statistik Kesenian</h5>
                        <canvas id="kesenianChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mt-4">
                        <h5>Statistik Produk</h5>
                        <canvas id="produkChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mt-4">
                        <h5>Statistik Toko</h5>
                        <canvas id="tokoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();

        // Grafik Pengguna
        var ctxUser = document.getElementById('userChart').getContext('2d');
        var userChart = new Chart(ctxUser, {
            type: 'line',
            data: {
                labels: @json($userDates),
                datasets: [{
                    label: 'Jumlah Pengguna',
                    data: @json($userCounts),
                    borderColor: 'rgba(255, 193, 7, 1)',
                    tension: 0.1
                }]
            },
            options: { responsive: true }
        });

        // Grafik Kesenian
        var ctxKes = document.getElementById('kesenianChart').getContext('2d');
        var kesenianChart = new Chart(ctxKes, {
            type: 'line',
            data: {
                labels: @json($kesenianDates),
                datasets: [{
                    label: 'Jumlah Kesenian',
                    data: @json($kesenianCounts),
                    borderColor: 'rgba(54, 162, 235, 1)',
                    tension: 0.1
                }]
            },
            options: { responsive: true }
        });

        // Grafik Produk
        var ctxPrd = document.getElementById('produkChart').getContext('2d');
        var produkChart = new Chart(ctxPrd, {
            type: 'line',
            data: {
                labels: @json($produkDates),
                datasets: [{
                    label: 'Jumlah Produk',
                    data: @json($produkCounts),
                    borderColor: 'rgba(40, 167, 69, 1)',
                    tension: 0.1
                }]
            },
            options: { responsive: true }
        });

        // Grafik Toko (optional, kalau kamu mau tracking toko juga per tanggal)
        var ctxToko = document.getElementById('tokoChart').getContext('2d');
        var tokoChart = new Chart(ctxToko, {
            type: 'line',
            data: {
                labels: @json($tokoDates ?? []),
                datasets: [{
                    label: 'Jumlah Toko',
                    data: @json($tokoCounts ?? []),
                    borderColor: 'rgba(0, 123, 255, 1)',
                    tension: 0.1
                }]
            },
            options: { responsive: true }
        });
    });
</script>
@endpush
