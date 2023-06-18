<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;

use App\Models\Author;
use App\Models\Book;
use App\Models\Member;
use App\Models\Catalog;
use App\Models\Publisher;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Gate;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function dashboard()
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

        return view('admin.dashboard.index', compact('total_buku', 'total_anggota', 'total_peminjaman', 'total_penerbit', 'data_donut','label_donut','data_bar'));
    }

    public function catalogs()
    {
        $catalogs = Catalog::all();
        return view('admin.catalog.index', compact('catalogs'));
    }

    public function publishers()
    {
        $publishers = Publisher::all();

        return view('admin.publisher.index', compact('publishers'));
    }

    public function authors()
    {
        $data_pengarang = Author::all();
        
        return view('admin.author.index', compact('data_pengarang'));
    }   
    
    public function members()
    {
        return view('admin.member.index');
    }

    public function books()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $catalogs = Catalog::all();
        $books = Book::all(); // tambahkan ini
        return view('admin.book.index', compact('publishers', 'authors', 'catalogs', 'books')); // tambahkan $books ke compact
    }

    public function transactions()
    {
        if (Gate::allows('index peminjaman')) {
            $data_buku = Book::all();
            $data_anggota = Member::all();
            
            return view('admin.transaction.index', compact('data_buku', 'data_anggota'));
        } else {
            return abort(403);
        }
    }

    public function testSpatie()
    {
        // $role = Role::create(['name' => 'petugas']);
        // $permission = Permission::create(['name' => 'index peminjaman']);
    
        // $role->givePermissionTo($permission);
        // $permission->assignRole($role);
    
        // $user = auth()->user();
        // $user->assignRole('petugas');
        // return $user;
        
    
        $user = User::with('roles')->get();
        return $user;
    
        // $user = auth()->user();
        // $user->removeRole('petugas');
    }
}