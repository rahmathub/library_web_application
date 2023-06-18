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
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Transaction Detail</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="form-group row">
                <label class="col-lg-2">Member</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ $transaction->member->name }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2">Tanggal Pinjam</label>
                <div class="col-lg-5">
                    <label>Tanggal Mulai Pinjam :</label>
                    <input type="text" class="form-control" value="{{ $transaction->date_start }}" readonly>
                </div>
                <div class="col-lg-5">
                    <label>Tanggal Selesai Pinjam :</label>
                    <input type="text" class="form-control" value="{{ $transaction->date_end }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2">Buku</label>
                <div class="col-lg-10">
                    <ul>
                        @foreach($transaction->transactionDetails as $detail)
                            <li>{{ $detail->book->title }} (Total buku : {{ $detail->qty }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2">Status</label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" value="{{ $transaction->status == 1 ? 'Sudah Dikembalikan' : 'Belum Dikembalikan' }}" readonly>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
@endsection




@section('js')

@endsection