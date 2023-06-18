<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Book;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // laravel spatie berhasil meresponn jika saya menggunakan disini 
        if (Gate::allows('index peminjaman')) {
            $data_buku = Book::all();
            $data_anggota = Member::all();
            
            return view('admin.transaction.index', compact('data_buku', 'data_anggota'));
        } else {
            return abort(403);
        }
    }

    public function api(Request $request)
    {
        $transactions = Transaction::with(['transactionDetails.book', 'member'])
        ->select(['id', 'date_start', 'date_end', 'status', 'member_id']);

        if ($request->has('status')) {
            $status = $request->status;
            if ($status !== '') {
                $transactions->where('status', $status);
            }
        }

        if ($request->has('tanggalPinjamFilter')) {
            $selectedTanggalPinjam = $request->tanggalPinjamFilter;
        
            if ($selectedTanggalPinjam === '' || $selectedTanggalPinjam === null) {
                // Tidak ada filter tanggal pinjam yang dipilih, tampilkan semua data
                $transactions->whereNotNull('id');
            } else {
                if ($selectedTanggalPinjam === "1") {
                    // Filter tanggal pinjam hari ini
                    $transactions->whereDate('date_start', now()->toDateString());
                } elseif ($selectedTanggalPinjam === "7") {
                    // Filter tanggal pinjam 7 hari terakhir
                    $tanggalFilter = now()->subDays(6)->toDateString();
                    $transactions->where('date_start', '>=', $tanggalFilter);
                } elseif ($selectedTanggalPinjam === "30") {
                    // Filter tanggal pinjam 30 hari terakhir
                    $tanggalFilter = now()->subDays(29)->toDateString();
                    $transactions->where('date_start', '>=', $tanggalFilter);
                }
            }
        }
        
        $datatables = datatables()->of($transactions)
            ->addColumn('lama_pinjam', function ($transaction) {
                $dateStart = \DateTime::createFromFormat('Y-m-d', $transaction->date_start);
                $dateEnd = \DateTime::createFromFormat('Y-m-d', $transaction->date_end);
                $interval = $dateEnd->diff($dateStart);
                $lamaPinjam = $interval->days;
    
                return $lamaPinjam;
            })
            ->addColumn('total_buku', function ($transaction) {
                $totalQty = 0;
                foreach ($transaction->transactionDetails as $transactionDetail) {
                    $totalQty += $transactionDetail->qty;
                }
                return $totalQty;
            })
            ->addColumn('total_bayar', function ($transaction) {
                $totalBayar = 0;
                foreach ($transaction->transactionDetails as $transactionDetail) {
                    $totalBayar += $transactionDetail->qty * $transactionDetail->book->price;
                }
                return $totalBayar;
            })
            ->addColumn('nama_peminjam', function ($transaction) {
                return $transaction->member->name; // Ganti 'name' dengan kolom nama peminjam yang sesuai
            })
            ->addIndexColumn();
    
        return $datatables->toJson();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $members = Member::select('id', 'name')->get();
        $books = Book::select('id', 'title', 'qty')->get();
        return view('admin.transaction.create', compact('members', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'member_id' => ['required', 'numeric'],
            'date_start' => ['required', 'date'],
            'date_end' => ['required', 'date'],
            'book_id' => ['required', 'array'],
            'book_id.*' => ['required', 'numeric'],
        ]);
    
        // Ubah format tanggal menggunakan metode built-in Laravel
        $dateStart = \DateTime::createFromFormat('m/d/Y', $validatedData['date_start'])->format('Y-m-d');
        $dateEnd = \DateTime::createFromFormat('m/d/Y', $validatedData['date_end'])->format('Y-m-d');
    
        // Membuat data transaksi
        $transaction = Transaction::create([
            'member_id' => $validatedData['member_id'],
            'date_start' => $dateStart,
            'date_end' => $dateEnd, // Menggunakan $dateEnd
            'status' => 0, // Nilai status diisi otomatis 0
        ]);
    
        // Membuat data detail transaksi
        foreach ($validatedData['book_id'] as $bookId) {
            $book = Book::find($bookId);
    
            // Kurangi nilai qty pada tabel books
            if ($book && $book->qty > 0) {
                $book->qty = $book->qty - 1;
                $book->save();
    
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'book_id' => $bookId,
                    'qty' => 1, // Jumlah qty diisi dengan 1 sesuai dengan kebutuhan Anda
                ]);
            }
        }
    
        return redirect()->route('transactions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('admin.transaction.detail', compact('transaction'));
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $members = Member::all();
        $books = Book::all();
        $transactionBooks = $transaction->transactionDetails->pluck('book_id')->toArray();
    
        return view('admin.transaction.edit', compact('transaction', 'members', 'books', 'transactionBooks'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        // Validasi input form
        $request->validate([
            'member_id' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'book_id' => 'required',
            'status' => 'required',
        ]);
    
        // Mengambil nilai status yang dipilih
        $status = $request->input('status');
    
        // Mengupdate kolom member_id
        $transaction->member_id = $request->input('member_id');
    
        // Mengupdate kolom date_start
        $dateStart = date('Y-m-d', strtotime($request->input('date_start')));
        $transaction->date_start = $dateStart;
    
        // Mengupdate kolom date_end
        $dateEnd = date('Y-m-d', strtotime($request->input('date_end')));
        $transaction->date_end = $dateEnd;
    
        // Mengupdate kolom status
        $transaction->status = $status;
    
        // Simpan perubahan pada model Transaction
        $transaction->save();
    
        // Mengupdate kolom qty pada transaction_details dan books
        $transactionDetails = $transaction->transactionDetails;
        foreach ($transactionDetails as $detail) {
            if ($status == 1) {
                // Jika status bernilai 1 ("Sudah Dikembalikan")
                // Lakukan pengurangan qty pada kolom transaction_details.qty dan books.qty
                $detail->decrement('qty');
                $detail->book()->decrement('qty');
            } else {
                // Jika status bernilai 0 ("Belum Dikembalikan")
                // Lakukan penambahan qty pada kolom transaction_details.qty dan books.qty
                $detail->increment('qty');
                $detail->book()->increment('qty');
            }
        }
    
        // Mengupdate kolom book_id pada transaction_details
        $bookIds = $request->input('book_id');
        $existingBookIds = $transactionDetails->pluck('book_id')->toArray();
    
        // Menghapus transaction_details yang tidak ada dalam array $bookIds
        $transactionDetailsToDelete = $transactionDetails->whereIn('book_id', array_diff($existingBookIds, $bookIds));
        $transactionDetailsToDelete->each(function ($detail) {
            $detail->delete();
        });
    
        // Menambahkan transaction_details baru yang belum ada
        $newBookIds = array_diff($bookIds, $existingBookIds);
        foreach ($newBookIds as $newBookId) {
            $transaction->transactionDetails()->create([
                'book_id' => $newBookId,
                'qty' => ($status == 1) ? -1 : 1, // Mengatur qty sesuai dengan status
            ]);
        }
    
        // Tampilkan array menggunakan vardump
        // var_dump($request->all());

        // Mengarahkan pengguna ke halaman index.blade.php setelah berhasil menyimpan data
        return redirect('/transactions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        // Hapus data pada tabel transaction_details berdasarkan transaction_id
        $transaction->transactionDetails()->delete();
    
        // Hapus data pada tabel transactions
        $transaction->delete();
    
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
    
    
}
