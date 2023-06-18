@extends('layouts.admin')
@section('header', 'Transaction')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Theme style -->
@endsection

@section('content')
    {{-- if you use $role and @endrole you have to write text in @role( table db role = name) --}}
    {{-- @role('petugas') --}}
    {{-- if you use @can and @endcan you write text in $can('table db permission = name')  --}}
    {{-- @can('index peminjaman') --}}
        <div id="controller">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{ url('transactions/create') }}"  class="btn btn-sm btn-primary pull-right">
                                        Create New Transaction
                                    </a>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" id="status" class="form-control" @change="filterByStatus($event.target.value)">
                                        <option value="">Filter Status</option>
                                        <option value="0">Belum Dikembalikan</option>
                                        <option value="1">Sudah Dikembalikan</option>
                                    </select>
                                    
                                </div>
                                <div class="col-md-2">
                                    <select name="tanggalPinjamFilter" id="tanggalPinjamFilter" class="form-control" v-model="selectedTanggalPinjam" @change="filterByTanggalPinjam()">
                                        <option value="">Filter Tanggal Pinjam</option>
                                        <option value="1">Hari ini</option>
                                        <option value="7">7 Hari Terakhir</option>
                                        <option value="30">30 Hari Terakhir</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th style="width: 10px">Tanggal Pinjam</th>
                                        <th class="text-center">Tanggal Kembali</th>
                                        <th class="text-center">Nama Peminjam</th>
                                        <th class="text-center">Lama Pinjam (Hari)</th>
                                        <th class="text-center">Total Buku</th>
                                        <th class="text-center">Total Bayar</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- @endcan --}}
    {{-- @endrole --}}
@endsection

@section('js')
    <!-- DataTables & Plugins -->
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
        var table;
    
        var controller = new Vue({
            el: '#controller',
            data: {
                table: null,
                selectedTanggalPinjam: ''
            },
            mounted() {
                this.initDataTable();
            },
            methods: {
                initDataTable() {
                    var self = this; // Menyimpan referensi objek Vue
    
                    if ($.fn.DataTable.isDataTable('#datatable')) {
                        this.table = $('#datatable').DataTable();
                    } else {
                        this.table = $('#datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{{ url('api/transactions') }}',
                                data: function (d) {
                                    // Menambahkan parameter tanggalPinjamFilter saat melakukan request AJAX
                                    d.tanggalPinjamFilter = self.selectedTanggalPinjam;
                                }
                            },
                            columns: [
                                {data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false},
                                {data: 'date_start', className: 'text-center', searchable: true},
                                {data: 'date_end', className: 'text-center', searchable: true},
                                {data: 'member.name', className: 'text-center', searchable: true},
                                {data: 'lama_pinjam', className: 'text-center', searchable: true},
                                {data: 'total_buku', className: 'text-center', searchable: true},
                                {
                                    data: 'total_bayar',
                                    className: 'text-center',
                                    render: function (data) {
                                        return 'Rp ' + parseInt(data).toLocaleString();
                                    },
                                    searchable: true
                                },
                                {
                                    data: 'status',
                                    className: 'text-center',
                                    render: function (data) {
                                        if (data === 0) {
                                            return 'Belum Dikembalikan';
                                        } else {
                                            return 'Sudah Dikembalikan';
                                        }
                                    },
                                    searchable: true
                                },
                                {
                                    data: null,
                                    className: 'text-center',
                                    render: function (data, type, row) {
                                        var editUrl = '{{ url('transactions') }}/' + data.id + '/edit';
                                        var deleteUrl = '{{ url('transactions') }}/' + data.id;
                                        var detailUrl = '{{ url('transactions') }}/' + data.id;

                                        return '<a href="' + editUrl + '" class="btn btn-primary btn-sm mr-1 mb-1">Edit</a>' +
                                            '<button class="btn btn-danger btn-sm mb-1 mr-1" onclick="controller.deleteData(' + data.id + ')">Hapus</button>' +
                                            '<a href="' + detailUrl + '" class="btn btn-info btn-sm mr-1 mb-1">Detail</a>';
                                    },
                                    searchable: false
                                },
                            ]
                        });
                    }
                },
                deleteData(id) {
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        axios.delete('{{ url('transactions') }}/' + id)
                            .then(response => {
                                this.table.ajax.reload();
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    }
                },
                filterByStatus(status) {
                    if (status === "") {
                        this.table.ajax.url('{{ url('api/transactions') }}').load(); // Menggunakan metode load() untuk memuat ulang data
                    } else {
                        this.table.ajax.url('{{ url('api/transactions') }}?status=' + status).load(); // Menggunakan metode load() untuk memuat ulang data
                    }
                },
                filterByTanggalPinjam() {
                    let selectedValue = this.selectedTanggalPinjam;

                    if (selectedValue === "") {
                        this.table.ajax.url('{{ url('api/transactions') }}').load();
                    } else if (selectedValue === "1") {
                        let today = new Date().toISOString().split('T')[0];
                        this.table.ajax.url('{{ url('api/transactions') }}?tanggalPinjamFilter=' + today).load();
                    } else if (selectedValue === "7") {
                        let lastWeek = new Date();
                        lastWeek.setDate(lastWeek.getDate() - 6); // Ubah menjadi 6 untuk mendapatkan 7 hari terakhir
                        let formattedDate = lastWeek.toISOString().split('T')[0];
                        this.table.ajax.url('{{ url('api/transactions') }}?tanggalPinjamFilter=' + formattedDate).load();
                    } else if (selectedValue === "30") {
                        let last30Days = new Date();
                        last30Days.setDate(last30Days.getDate() - 29); // Ubah menjadi 29 untuk mendapatkan 30 hari terakhir
                        let formattedDate = last30Days.toISOString().split('T')[0];
                        this.table.ajax.url('{{ url('api/transactions') }}?tanggalPinjamFilter=' + formattedDate).load();
                    } else {
                        this.table.ajax.url('{{ url('api/transactions') }}').load();
                    }
                }
            }
        });
    </script>
@endsection

