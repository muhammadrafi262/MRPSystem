@extends('layouts.heading')

@section('content')
<div class="pc-container">
    <div class="pc-content">
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Landing Page</h5>
              </div>
              <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home.landingpage') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript: void(0)">Landing Page</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="pc-content">
        <main>
          <div class="content text-center">
            <div class="content-description">
              <h1 class="title">MRP System</h1>
                <article id="beranda">
                  <p>
                    Selamat Datang ke Sistem MRP kami!  
                    Sistem ini membantu Anda merencanakan kebutuhan material dan memantau setiap transaksi produksi secara efisien.  
                    Gunakan fitur-fitur yang tersedia untuk mempermudah proses bisnis Anda.  
                    Jika Anda sudah memiliki akun, silakan klik tombol <strong>Login</strong> untuk masuk ke sistem.  
                    Jika belum memiliki akun, klik tombol <strong>Register</strong> untuk mendaftar.
                  </p>
                </article>
                <p></p>
                <div class="button-container">
                  <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-primary btn-lg" href="{{ route('login') }}">Login</a>
                    <div id="exampleModalCenter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Register Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>Mohon maaf untuk registrasi pengguna hanya dapat dilakukan oleh admin. 
                              Silahkan hubungi admin. Terimakasih</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Register</button>
                  </div>
                </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>

@endsection