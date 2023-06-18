@extends('layouts.admin')
@section('header', 'Transaction')

@section('css')
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/dropzone/min/dropzone.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
@endsection


@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Create New Transaction</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="{{ url('transactions') }}">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-lg-2">
                        <label>Member</label>
                    </div>
                    <div class="col-lg-10">
                        <select name="member_id" class="form-control select2" style="width: 100%;" required>
                            {{-- looping data member --}}
                            @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
        
                <div class="form-group row">
                    <div class="col-lg-2">
                        <label>Tanggal</label>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input name="date_start" type="text" class="form-control datetimepicker-input" data-target="#reservationdate" required/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 text-center">
                        <label>-</label>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group date" id="pinjam_selesai" data-target-input="nearest">
                            <input name="date_end" type="text" class="form-control datetimepicker-input" data-target="#pinjam_selesai" required/>
                            <div class="input-group-append" data-target="#pinjam_selesai" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>                        
                </div>
                
                <div class="form-group row">
                    <div class="col-lg-2">
                        <label>Buku</label>
                    </div>
                    <div class="col-lg-10">
                        <select name="book_id[]" class="select2" multiple="multiple" data-placeholder="Pilih Buku" style="width: 100%;" required>
                            @foreach($books as $book)
                                {{-- saya membuat filter dsini  --}}
                                {{-- filternya adalah ingin menampilkan stok atau qty > 0 --}}
                                @if($book->qty > 0)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
        
                <div class="form-group row">
                    <div class="col-lg-12">
                        @error('book_ids')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
        
    </div>
    <!-- /.card -->
@endsection

@section('js')
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Bootstrap Switch -->
    <script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- BS-Stepper -->
    <script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <!-- dropzonejs -->
    <script src="{{ asset('assets/plugins/dropzone/min/dropzone.min.js') }}"></script>


    <script>
        $(function ()   {
            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });

            //Date picker
            $('#pinjam_selesai').datetimepicker({
                format: 'L'
            });
        })

        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endsection