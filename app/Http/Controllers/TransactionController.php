<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'jumlah' => 'required|numeric',
            'total_harga' => 'required|numeric',  // Tambahkan validasi untuk total_harga
            'metode' => 'required|string',
        ]);

        // Simpan data transaksi ke dalam database
        Transaction::create([
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'total_harga' => $request->total_harga,  // Pastikan mengisi total_harga
            'metode_pembayaran' => $request->metode,
        ]);

        // Redirect atau tampilkan pesan sukses
        return redirect()->back()->with('success', 'Transaksi berhasil disimpan.');
    }
    public function edit($id)
    {
        $editTransaction = Transaction::findOrFail($id);
        $transactions = Transaction::orderBy('tanggal', 'desc')->get();
        return view('dashboard', compact('editTransaction', 'transactions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'jumlah' => 'required|numeric',
            'total_harga' => 'required|numeric',
            'metode' => 'required|string',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update([
            'tanggal' => $request->tanggal,
            'kategori' => $request->kategori,
            'jumlah' => $request->jumlah,
            'total_harga' => $request->total_harga,
            'metode_pembayaran' => $request->metode,
        ]);

        return redirect()->route('transaction.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function index(Request $request)
    {
        $search = $request->query('search');
        $transactions = Transaction::query();

        if ($search) {
            $transactions->where('kategori', 'like', "%{$search}%")
                ->orWhere('metode_pembayaran', 'like', "%{$search}%")
                ->orWhere('tanggal', 'like', "%{$search}%");
        }

        $transactions = $transactions->orderBy('tanggal', 'desc')->get();

        $transactionToEdit = null;
        if ($request->has('edit_id')) {
            $transactionToEdit = Transaction::find($request->edit_id);
        }

        return view('dashboard', compact('transactions', 'transactionToEdit'));
    }


    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete();
            return redirect()->route('transaction.index')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->route('transaction.index')->with('error', 'Data tidak ditemukan.');
        }
    }
}
