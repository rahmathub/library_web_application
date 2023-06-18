<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // security
    public function __construct() {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.author.index');

    }

    public function api() {
        $authors = Author::all();

        // foreach($authors as $key => $author){
        //     $author->date = convert_date($author->create_at);
        // }

        // menggunakan helpers date di yajra
        $datatables = datatables()->of($authors)
                            ->addColumn('date', function($author){
                                return convert_date($author->create_at);
                            })->addIndexColumn();

        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                // VALIDASI KEDUA 
        // pada method store() di CatalogController
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:15'],
            'phone_number' => ['required', 'string', 'max:12'],
            'address' => ['required', 'string', 'max:30'],
        ]);

        /* ALUR VALIDASI DI ATAS
        Code tersebut adalah method store() pada CatalogController yang digunakan untuk menyimpan data baru ke dalam tabel catalogs.

        Pada baris kedua, dilakukan validasi menggunakan validate() yang merupakan fungsi bawaan Laravel untuk memvalidasi inputan dari form. 
        Pada contoh tersebut, validasi dilakukan untuk name dengan ketentuan sebagai berikut:

        - required: Inputan untuk field name harus diisi atau tidak boleh kosong.
        - string: Inputan untuk field name harus berupa string (teks), tidak boleh angka atau karakter lain.
        - max:255: Inputan untuk field name maksimal terdiri dari 255 karakter.

        Jika inputan dari form berhasil melewati validasi, maka data akan disimpan pada tabel catalogs menggunakan method create() pada Model Catalog. 
        Kemudian, user akan di-redirect ke halaman index dari catalogs. Jika terdapat error dalam validasi, maka user akan tetap berada pada halaman create dan error message akan ditampilkan.
        */

        Author::create($request->all());

        return redirect()->route('authors.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Author $author)
    {
        // pada method store() di AuthorController
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:15'],
            'phone_number' => ['required', 'string', 'max:12'],
            'address' => ['required', 'string', 'max:30'],
        ]);

        $author->update($request->all());

        return redirect()->route('authors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        $author->delete(); 
    }
}
