<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Catalog;
use App\Models\Author;
use App\Models\Book;
use App\Models\Member;
use App\Models\Publisher;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Box dashboard yang ada 4 itu
        $total_anggota = Member::count();
        $total_buku = Book::count();
        $total_peminjaman = Transaction::whereMonth('created_at', date('m'))->count();
        $total_penerbit = Publisher::count();

        // Grafik Penerbit
        $data_donut = Book::select(DB::raw("COUNT(publisher_id) as total"))->groupBy('publisher_id')->orderBy('publisher_id', 'asc')->pluck('total');
        $label_donut = Publisher::orderBy('publishers.id', 'asc')->join('books', 'books.publisher_id', '=', 'publishers.id')->groupBy('name')->pluck('name');

        
        // Bar Grafik Peminjaman
        $label_bar = ['Peminjaman', 'Pengembalian'];
        $data_bar = [];

        foreach ($label_bar as $key => $value) {
            $data_bar[$key]['label'] = $label_bar[$key];
            $data_bar[$key]['backgroundColor'] = $key == 1 ? 'rgba[60,141,188,0.9]' : 'rgba(210, 214, 222, 1)';
            $data_month = [];

            foreach (range(1,12) as $month) {
                if($key == 0) {
                    $data_month[] = Transaction::select(DB::raw("COUNT(*) as total"))->whereMonth('created_at', $month)->first()->total;
                } else {
                    $data_month[] = Transaction::select(DB::raw("COUNT(*) as total"))->whereMonth('updated_at', $month)->first()->total;

                }
            }
            $data_bar[$key]['data'] = $data_month;
        }
        
        // Batas grafik peminjaman

        // Relasi Model Member
        $members = Member::with('user')->get();
        
        // Relasi Model Book
        $books = Book::with('publisher')->get();     
        $books1 = Book::with('author')->get();
        $books2 = Book::with('catalog')->get(); 

        // Relasi Model Catalog
        $catalogs = Catalog::with('books')->get();

        // Relasi Model Author
        $authors = Author::with('books')->get();

        // Relasi Model Publisher
        $publishers = Publisher::with('books')->get();

        // QUERY BUILDER
        // No 1
        $data = Member::select('*')
                    ->join('users', 'users.member_id', '=', 'members.id')
                    ->get();

        // No 2
        $data2 = Member::select('*')
                    ->leftJoin('users','users.member_id','=','members.id')
                    ->where('users.id', NULL)
                    ->get();

        // No 3
        $data3 = Transaction::select('members.id', 'members.name')
                    ->rightJoin('members', 'members.id', '=', 'transactions.member_id')
                    ->where('transactions.member_id', NULL)
                    ->get();

        // No 4
        $data4 = Member::select('members.id', 'members.name', 'members.phone_number')
                    ->join('transactions', 'transactions.member_id', '=', 'members.id')
                    ->orderBy('members.id', 'asc')
                    ->get();

        // No 5
        $data5 = Member::select('members.id', 'members.name', 'members.phone_number')
                    ->join('transactions', 'transactions.member_id', '=', 'members.id')
                    ->groupBy('members.id', 'members.name', 'members.phone_number')
                    ->havingRaw('COUNT(transactions.member_id) > 1')
                    ->get();

        /* WHERE tidak diperlukan karena JOIN sudah menyertakan kondisi yang sama dengan WHERE tersebut, 
        yaitu transactions.member_id = members.id. Oleh karena itu, pada saat menggunakan JOIN, 
        WHERE dapat dihilangkan dan cukup menggunakan kondisi pada JOIN tersebut.
        padahal di query sql tanpa laraver harus menggunakan where
        */

        // No 6
        $data6 = Member::select('members.name','members.phone_number','transactions.created_at')
                    ->join('transactions', 'transactions.member_id', '=', 'members.id')
                    ->get();

        // No 7
        $data7 = Member::select('members.name', 'members.phone_number','transactions.created_at','transactions.updated_at')
                    ->join('transactions','transactions.member_id','=','members.id')
                    ->whereMonth('transactions.updated_at', '=', '06')
                    ->whereYear('transactions.updated_at', '=', '2021')
                    ->get();

        // No 8
        $data8 = Member::select('members.name','members.phone_number','members.address','transactions.created_at','transactions.updated_at')
                    ->join('transactions','transactions.member_id','=','members.id')
                    ->whereMonth('transactions.updated_at', '=', '05')
                    ->whereYear('transactions.updated_at', '=', '2021')
                    ->get();

        //  No 9
        $data9 = Member::select('members.name','members.phone_number','members.address','transactions.created_at','transactions.updated_at')
                    ->join('transactions', 'transactions.member_id','=','members.id')
                    ->whereMonth('transactions.updated_at','=','06')
                    ->whereYear('transactions.updated_at','=','2021')
                    ->get();

        // No 10
        $data10 = Member::select('members.name','members.phone_number','members.address','transactions.created_at','transactions.updated_at')
                    ->join('transactions', 'transactions.member_id','=','members.id')
                    ->where('members.address','like','%Bandung%')
                    ->get();

        // No 11
        $data11 = Member::select('members.name','members.phone_number','members.address','transactions.created_at','transactions.updated_at')
                    ->join('transactions','transactions.member_id','=','members.id')
                    ->where('members.address','like','%Bandung%')
                    ->where('members.gender','like','%P%')
                    ->get();

        // No 12
        $data12 = Member::select('members.name','members.phone_number','members.address','transactions.created_at','transactions.updated_at','transaction_details.book_id','transaction_details.qty')
                    ->join('transactions','transactions.member_id','=','members.id')
                    ->join('transaction_details','transaction_details.transaction_id','=','transactions.id')
                    ->where('transaction_details.qty','>', 1)
                    ->get();

        // No 13
        $data13 = Member::select('members.name','members.phone_number','members.address','transactions.created_at','transactions.updated_at','transaction_details.book_id','transaction_details.qty','books.title','books.price', Member::raw('transaction_details.qty*books.price as total_harga'))
                    ->join('transactions','transactions.member_id','=','members.id')
                    ->join('transaction_details', 'transaction_details.id','=','transactions.id')
                    ->join('books','books.id','=','transaction_details.book_id')
                    ->get();

        // No 14
        $data14 = Member::select('members.name','members.phone_number','members.address','transactions.created_at','transactions.updated_at','books.id','books.title','publishers.name','authors.name','catalogs.name')
                    ->join('transactions', 'transactions.member_id','=','members.id')
                    ->join('transaction_details', 'transaction_details.id','=','transactions.id')
                    ->join('books', 'books.id','=','transaction_details.book_id')
                    ->join('publishers', 'publishers.id','=','books.publisher_id')
                    ->join('authors', 'authors.id','=','books.author_id')
                    ->join('catalogs', 'catalogs.id','=','books.catalog_id')
                    ->get();

        // No 15
        $data15 = Catalog::select('catalogs.*','books.title')
                    ->join('books','books.catalog_id','=','catalogs.id')
                    ->get();

        // No 16
        $data16 = Book::select('books.*','publishers.name')
                    ->leftJoin('publishers', 'publishers.id','=','books.publisher_id')
                    ->get();

        // No 17
        $data17 = Book::where('author_id', 'PG05')
                    ->count();
        
        /* MAKSUD DIATAS NO 17 
        MAKSUD COUNT KENAPA TIDAK DI PAKAI get()
        Pada query tersebut, digunakan fungsi agregat COUNT(*) untuk menghitung jumlah baris atau record yang memenuhi kondisi WHERE. 
        Fungsi agregat ini akan mengembalikan nilai tunggal yaitu jumlah baris. Karena itu, kita tidak membutuhkan get() untuk mengambil 
        data lainnya, seperti kolom atau field di tabel tersebut.

        Jika kita menggunakan get() pada query tersebut, maka hasil yang diperoleh adalah seluruh data pada tabel buku yang memenuhi 
        kondisi WHERE, bukan jumlahnya. 


        MAKSUD KENAPA TIDAK MEMAKAI select()
        Karena query yang dimaksudkan hanya memerlukan perhitungan jumlah baris (count) dan tidak membutuhkan data dari tabel, sehingga tidak 
        perlu menentukan field yang ingin ditampilkan menggunakan select().
        Selain itu, COUNT() sudah menjadi fungsi agregasi bawaan dari SQL yang berfungsi untuk menghitung jumlah baris pada suatu tabel.
        */

        // No 18
        $data18 = Book::select('*')
                    ->where('price','>', 10000)
                    ->get();

        // No 19
        $data19 = Book::select('*')
                    ->join('publishers', 'publishers.id','=','books.publisher_id')
                    ->where('publishers.name','Penerbit 01')
                    ->where('books.qty', '>', 10)
                    ->orderBy('books.title', 'asc')
                    ->get();

        // No 20
        $data20 = Member::select('*')
                    ->whereMonth('created_at', '=', '06')
                    ->whereYear('created_at', '=', '2022')
                    ->get();
        

        // PANGGILAN QUERY BUILDER $data1 -$data20
        // return $data20;

        
        // PANGGILAN MODEL
        // return $members;
        // return $books;
        // return $books1;
        // return $books2;
        // return $catalogs;
        // return $authors;
        // return $publishers;
        // return view('home');
        return view('home', compact('total_buku', 'total_anggota', 'total_peminjaman', 'total_penerbit', 'data_donut','label_donut','data_bar'));
    }
}
