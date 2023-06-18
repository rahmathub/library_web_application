<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Catalog;
use App\Models\Publisher;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $catalogs = Catalog::all();
        $books = Book::all(); // tambahkan ini
        return view('admin.book.index', compact('publishers', 'authors', 'catalogs', 'books')); // tambahkan $books ke compact
    }


    public function api(){
        $books = Book::all();
        
        return json_encode($books);
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
        $this->validate($request, [
            'isbn' => ['required', 'numeric', 'unique:books'],
            'title' => ['required', 'string', 'max:255'],
            'year' => ['required', 'numeric', 'min:1900', 'max:' . (date('Y') + 1)],
            'publisher_id' => ['required', 'exists:publishers,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'catalog_id' => ['required', 'exists:catalogs,id'],
            'qty' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);
    
        Book::create($request->all());
    
        return redirect()->route('books.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $this->validate($request, [
            'isbn' => ['required', 'integer', 'digits_between:1,13'],
            'title' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'digits:4', 'between:1900,' . (date('Y') + 1)],
            'publisher_id' => ['required', 'exists:publishers,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'catalog_id' => ['required', 'exists:catalogs,id'],
            'qty' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
        ]);
    
        $book->update($request->all());
    
        return redirect()->route('books.index');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->delete();
    }
}
