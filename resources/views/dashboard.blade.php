<x-app-layout>
    @section('title', 'Input')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Data') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-6">Form Input Transaksi</h3>
                    <!-- Form Input Transaksi -->
                    <form method="POST"
                        action="{{ isset($editTransaction) ? route('transaction.update', $editTransaction->id) : route('transaction.store') }}">
                        @csrf
                        @if (isset($editTransaction))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <x-input-label for="tgl-transaksi" :value="__('Tanggal Transaksi')" />
                            <x-text-input id="tgl-transaksi" type="date" name="tanggal" class="block mt-1 w-full"
                                value="{{ $editTransaction->tanggal ?? '' }}" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="kategori" :value="__('Kategori Transaksi')" />
                            <select id="kategori" name="kategori"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                <option value="Pemasukan"
                                    {{ isset($editTransaction) && $editTransaction->kategori == 'Pemasukan' ? 'selected' : '' }}>
                                    Pemasukan</option>
                                <option value="Pengeluaran"
                                    {{ isset($editTransaction) && $editTransaction->kategori == 'Pengeluaran' ? 'selected' : '' }}>
                                    Pengeluaran</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="jumlah" :value="__('Jumlah')" />
                            <x-text-input id="jumlah" type="number" name="jumlah" class="block mt-1 w-full"
                                value="{{ $editTransaction->jumlah ?? '' }}" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="total_harga" :value="__('Total Harga')" />
                            <x-text-input id="total_harga" type="number" name="total_harga" class="block mt-1 w-full"
                                value="{{ $editTransaction->total_harga ?? '' }}" required />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="metode_pembayaran" :value="__('Metode Pembayaran')" />
                            <select id="metode_pembayaran" name="metode"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm" required>
                                <option value="Cash"
                                    {{ isset($editTransaction) && $editTransaction->metode_pembayaran == 'Cash' ? 'selected' : '' }}>
                                    Cash</option>
                                <option value="Transfer"
                                    {{ isset($editTransaction) && $editTransaction->metode_pembayaran == 'Transfer' ? 'selected' : '' }}>
                                    Transfer</option>
                            </select>
                        </div>

                        <x-primary-button class="mt-4">
                            {{ isset($editTransaction) ? __('Update') : __('Submit') }}
                        </x-primary-button>
                    </form>

                    <!-- Tabel Hasil Input -->
                    <div class="mt-8 overflow-x-auto w-full">
                        <h3 class="text-lg font-semibold mb-4">Data</h3>
                        <!-- Form Pencarian -->
                        <form method="GET" action="{{ route('transaction.index') }}" class="mb-4">
                            <input type="text" name="search" placeholder="Search"
                                value="{{ request()->query('search') }}" class="px-4 py-2 border rounded-md">
                            <button type="submit" class="px-4 py-2 text-black rounded-md">Cari</button>
                        </form>

                        <table class="min-w-full w-full bg-white border border-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b">Tanggal</th>
                                    <th class="py-2 px-4 border-b">Kategori</th>
                                    <th class="py-2 px-4 border-b">Jumlah</th>
                                    <th class="py-2 px-4 border-b">Total Harga</th>
                                    <th class="py-2 px-4 border-b">Metode Pembayaran</th>
                                    <th class="py-2 px-4 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $transaction->tanggal }}</td>
                                        <td class="py-2 px-4 border-b">{{ $transaction->kategori }}</td>
                                        <td class="py-2 px-4 border-b">{{ $transaction->jumlah }}</td>
                                        <td class="py-2 px-4 border-b">{{ $transaction->total_harga }}</td>
                                        <td class="py-2 px-4 border-b">{{ $transaction->metode_pembayaran }}</td>
                                        <td class="py-2 px-4 border-b">
                                            <!-- Form untuk Edit -->
                                            <a href="{{ route('transaction.edit', $transaction->id) }}"
                                                class="text-blue-500 hover:text-blue-700">Edit</a>
                                            <!-- Form untuk Delete -->
                                            <form action="{{ route('transaction.destroy', $transaction->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 ml-2"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    });
</script>
