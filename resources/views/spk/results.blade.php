{{-- resources/views/spk/results.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Perhitungan SPK') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (isset($message))
                        <p class="text-red-600 mb-4">{{ $message }}</p>
                    @endif

                    @if ($criterias->isEmpty())
                        <p class="text-red-600">Tidak ada kriteria yang didefinisikan. Silakan <a href="{{ route('spk.criteria') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">tambah kriteria</a> terlebih dahulu.</p>
                    @elseif ($members->isEmpty())
                        <p class="text-red-600">Tidak ada anggota tim yang terdaftar. Silakan <a href="{{ route('spk.members') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">tambah anggota tim</a> terlebih dahulu.</p>
                    @else
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Bobot Kriteria:</h3>
                        <ul class="list-disc list-inside mb-6">
                            @foreach ($criterias as $criteria)
                                <li>{{ $criteria->name }}: {{ $criteria->weight }}</li>
                            @endforeach
                        </ul>

                        <h3 class="text-lg font-medium text-gray-900 mb-4">Hasil Peringkat Anggota Tim:</h3>
                        @if (empty($results))
                            <p class="text-red-600">Tidak ada hasil perhitungan. Pastikan semua anggota tim memiliki penilaian lengkap untuk semua kriteria.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peringkat</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Anggota</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Akhir</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($results as $index => $result)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $result['member']->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $result['member']->email }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if ($result['final_score'] !== null)
                                                        {{ number_format($result['final_score'], 4) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if ($result['final_score'] !== null)
                                                        Lengkap
                                                    @else
                                                        <span class="text-red-500">{{ $result['message'] ?? 'Penilaian belum lengkap' }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>