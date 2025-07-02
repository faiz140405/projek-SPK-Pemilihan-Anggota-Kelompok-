{{-- resources/views/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard SPK Tim Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-6">Ringkasan Proyek SPK</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-blue-100 p-6 rounded-lg shadow-md text-center">
                            <div class="text-3xl font-bold text-blue-800">{{ $totalCriterias }}</div>
                            <div class="text-blue-600">Total Kriteria</div>
                            <a href="{{ route('spk.criteria') }}" class="text-blue-500 hover:underline text-sm mt-2 block">Lihat Kriteria</a>
                        </div>
                        <div class="bg-green-100 p-6 rounded-lg shadow-md text-center">
                            <div class="text-3xl font-bold text-green-800">{{ $totalMembers }}</div>
                            <div class="text-green-600">Total Anggota Tim</div>
                            <a href="{{ route('spk.members') }}" class="text-green-500 hover:underline text-sm mt-2 block">Lihat Anggota</a>
                        </div>
                        <div class="bg-yellow-100 p-6 rounded-lg shadow-md text-center">
                            <div class="text-3xl font-bold text-yellow-800">{{ $totalRatings }}</div>
                            <div class="text-yellow-600">Total Penilaian</div>
                            <a href="{{ route('spk.input_ratings') }}" class="text-yellow-500 hover:underline text-sm mt-2 block">Input Penilaian</a>
                        </div>
                    </div>

                    <h3 class="text-xl font-semibold mb-4">Top 5 Anggota Tim Rekomendasi</h3>
                    <div class="bg-gray-50 p-6 rounded-lg shadow-md mb-8">
                        @if (isset($calculationMessage))
                            <p class="text-red-600">{{ $calculationMessage }}</p>
                        @elseif (empty($topMembersData['labels']))
                            <p class="text-gray-600">Tidak ada anggota tim yang memenuhi syarat untuk peringkat (mungkin penilaian belum lengkap).</p>
                            <a href="{{ route('spk.input_ratings') }}" class="text-indigo-600 hover:underline text-sm mt-2 block">Lengkapi Penilaian Sekarang</a>
                        @else
                            <canvas id="topMembersChart" class="max-h-96"></canvas>
                        @endif
                    </div>

                    <h3 class="text-xl font-semibold mb-4">Aksi Cepat</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('spk.criteria') }}" class="px-6 py-3 bg-purple-600 text-white rounded-md shadow-md hover:bg-purple-700 transition">
                            <i class="fa-solid fa-list-check mr-2"></i> Kelola Kriteria
                        </a>
                        <a href="{{ route('spk.members') }}" class="px-6 py-3 bg-teal-600 text-white rounded-md shadow-md hover:bg-teal-700 transition">
                            <i class="fa-solid fa-users mr-2"></i> Kelola Anggota
                        </a>
                        <a href="{{ route('spk.input_ratings') }}" class="px-6 py-3 bg-orange-600 text-white rounded-md shadow-md hover:bg-orange-700 transition">
                            <i class="fa-solid fa-star mr-2"></i> Input Penilaian
                        </a>
                        <a href="{{ route('spk.results') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md shadow-md hover:bg-indigo-700 transition">
                            <i class="fa-solid fa-chart-line mr-2"></i> Lihat Hasil SPK
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" >
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> {{-- Untuk ikon, ganti dengan kit Anda atau hapus --}}

    @if (!empty($topMembersData['labels']))
        <script>
            const ctx = document.getElementById('topMembersChart');

            new Chart(ctx, {
                type: 'bar', // Bisa juga 'horizontalBar' jika lebih suka
                data: {
                    labels: @json($topMembersData['labels']), // Nama anggota tim
                    datasets: [{
                        label: 'Skor Akhir SPK',
                        data: @json($topMembersData['scores']), // Skor akhir
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)', // Teal
                            'rgba(153, 102, 255, 0.6)', // Purple
                            'rgba(255, 159, 64, 0.6)', // Orange
                            'rgba(255, 99, 132, 0.6)',  // Red
                            'rgba(54, 162, 235, 0.6)',  // Blue
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Penting untuk kontrol ukuran
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Skor'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Anggota Tim'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Tidak perlu legend jika hanya 1 dataset
                        },
                        title: {
                            display: true,
                            text: 'Peringkat Anggota Tim Berdasarkan Skor SPK'
                        }
                    }
                }
            });
        </script>
    @endif
    @endpush
</x-app-layout>