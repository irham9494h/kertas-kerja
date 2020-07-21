@extends('layouts.app')

@section('title', 'Oops, Data tidak ditemukan.')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="error-page">
                    <h2 class="headline text-danger">404</h2>

                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Halaman tidak ditemukan.</h3>

                        <p>
                            Spertinya Anda mengakses URL yang keliru.
                            <a href="{{route('home')}}">Klik disini</a> untuk kembali ke beranda.
                        </p>

                        <p>
                            Atau silahkan hubungi Administrator jika Anda membutuhkan bantuan dengan URL yang Anda
                            akses.
                        </p>
                    </div>
                    <!-- /.error-content -->
                </div>
            </div>
        </div>
    </div>
@endsection
