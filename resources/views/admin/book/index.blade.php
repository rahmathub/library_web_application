@extends('layouts.admin')
@section('header', 'Book')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Theme style -->
@endsection

@section('content')
<div id="controller">
    <div class="row">
        <div class="row-md-5 offset-md-3">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fa fa-search"></i>
                    </span>
                </div>
                <input type="text" class="form-control" autocomplete="off" placeholder="Search from title" v-model="search">
            </div>
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary" @click="addData()">Create New Book</button>
        </div>
    </div>

    <hr> 

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12" v-for="book in filteredList" :key="book.id">
            <div class="info-box" v-on:click="editData(book)">
                <div class="info-box-content">
                    <span class="info-box-text h3">@{{ book.title }} (@{{ book.qty }})</span>
                    <span class="info-box-number">Rp. @{{ numberWithSpaces(book.price) }},-<small></small></span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" :action="actionUrl"  autocomplete="off">
                    <div class="modal-header">
                        <h4 class="modal-title">@{{ editStatus ? 'Edit' : 'Tambah' }} Book</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        
                        <input type="hidden" name="_method" v-if="editStatus" :value="'PUT'">

                        <div class="form-group">
                            <label>ISBN</label>
                            <input type="number" class="form-control" name="isbn" required v-model="book.isbn">
                        </div>

                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" required v-model="book.title">
                        </div>

                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" class="form-control" name="year" required v-model="book.year">
                        </div>

                        <div class="form-group">
                            <label>Publisher</label>
                            <select name="publisher_id" class="form-control" v-model="book.publisher_id">
                                <option v-for="publisher in publishers" :value="publisher.id" :selected="book.publisher_id == publisher.id">@{{ publisher.name }}</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Author</label>
                            <select name="author_id" class="form-control" v-model="book.author_id">
                                <option v-for="author in authors" :value="author.id" :selected="book.author_id == author.id">@{{ author.name }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Katalog</label>
                            <select name="catalog_id" class="form-control" v-model="book.catalog_id">
                                <option v-for="catalog in catalogs" :value="catalog.id" :selected="book.catalog_id == catalog.id">@{{ catalog.name }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Qty Stock</label>
                            <input type="number" class="form-control" name="qty" required v-model="book.qty">
                        </div>

                        <div class="form-group">
                            <label>Harga Pinjam</label>
                            <input type="number" class="form-control" name="price" required v-model="book.price">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default bg-danger" v-if="editStatus" v-on:click="deleteData(book.id)">Delete book</button>
                        <button type="submit" class="btn btn-primary">@{{ editStatus ? 'Save Changes' : 'Save Add Book' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    // DataTables  & Plugins 
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>


    <script type="text/javascript">
        var actionUrl = '{{ url('books') }}';
        var apiUrl = '{{ url('api/books') }}';
        var defaultUrl = '{{ url('books') }}';
        var app = new Vue({
            el: '#controller',
            data: {
                books: [],
                search: '',
                book: {},
                apiUrl,
                actionUrl,
                defaultUrl,
                editStatus: false,
                publishers: {!! $publishers !!},
                authors: {!! $authors !!},
                catalogs: {!! $catalogs !!}
            },
            mounted: function() {
                this.get_books();
            },
            methods: {
                get_books() {
                    const _this = this;
                    $.ajax({
                        url: apiUrl,
                        method: 'GET',
                        success: function(data) {
                            _this.books = JSON.parse(data);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                },
                addData() {
                    this.book = {};
                    this.editStatus = false;
                    this.actionUrl = this.defaultUrl;
                    $('#modal-default').modal();
                },
                editData(book = {}) {
                    this.book = { ...book }; // create a copy of the book object
                    this.editStatus = Boolean(book.id); // if book.id exists, it's an edit status
                    this.actionUrl = this.defaultUrl + '/' + book.id; //update url to edit url
                    $('#modal-default').modal();
                },
                deleteData(id) {
                    if (confirm("Are you sure?")) {
                        axios.delete(this.defaultUrl + '/' + id)
                            .then(response => {
                                alert('Data has been removed');
                                this.get_books();
                                $('#modal-default').modal('hide'); // menutup modal
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    }
                },
                numberWithSpaces(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            },
            computed: {
                filteredList() {
                    return this.books.filter(book => {
                        return book.title.toLowerCase().includes(this.search.toLowerCase())
                    })
                }
            }
        })
    </script>
@endsection