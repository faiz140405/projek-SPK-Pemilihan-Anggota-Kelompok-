{{-- resources/views/spk/input-ratings.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Penilaian Anggota Tim') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Sukses!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa masalah dengan input Anda:</span>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($members->isEmpty() || $criterias->isEmpty())
                        <p class="text-red-600">Anda perlu menambahkan setidaknya satu anggota tim dan satu kriteria sebelum bisa melakukan penilaian.</p>
                        <div class="mt-4">
                            @if ($members->isEmpty())
                                <a href="{{ route('spk.members') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Tambah Anggota Tim Sekarang</a><br>
                            @endif
                            @if ($criterias->isEmpty())
                                <a href="{{ route('spk.criteria') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Tambah Kriteria Sekarang</a>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('spk.store_ratings') }}" method="POST">
                            @csrf

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggota Tim</th>
                                            @foreach ($criterias as $criteria)
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $criteria->name }} (Bobot: {{ $criteria->weight }})</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($members as $member)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $member->name }}</td>
                                                @foreach ($criterias as $criteria)
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <input type="number"
                                                               name="ratings[{{ $member->id }}][{{ $criteria->id }}]"
                                                               value="{{ old('ratings.' . $member->id . '.' . $criteria->id, $ratings->get($member->id . '-' . $criteria->id)->score ?? '') }}"
                                                               min="1" max="10" {{-- Sesuaikan rentang skor Anda --}}
                                                               class="w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        @error('ratings.' . $member->id . '.' . $criteria->id)
                                                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="mt-6 px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Penilaian
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>