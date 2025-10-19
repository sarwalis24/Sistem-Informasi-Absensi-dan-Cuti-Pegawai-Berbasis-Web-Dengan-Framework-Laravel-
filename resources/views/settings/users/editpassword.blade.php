@extends('layouts.mobile.app')
@section('content')
    <style>
        /* Tambahkan style untuk header dan content */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 70px;
            padding-top: 5px;
            position: relative;
            z-index: 1;
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="#" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Setting User</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 80px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('users.updatepassword', Crypt::encrypt($user->id)) }}" method="POST" id="formIzin" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <input type="password" class="feedback-input passwordbaru" name="passwordbaru" placeholder="Password Baru" id="passwordbaru" />
                    <input type="password" class="feedback-input konfirmasipassword" name="konfirmasipassword" placeholder="Konfirmasi Password"
                        id="konfirmasipassword" />
                    <div class="form-group">
                        <input type="checkbox" id="show-password" onclick="tooglePassword()" />
                        <label for="show-password">Tampilkan Password</label>
                    </div>
                    <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        function tooglePassword() {
            var x = document.getElementById("passwordbaru");
            var y = document.getElementById("konfirmasipassword");
            if (x.type === "password") {
                x.type = "text";
                y.type = "text";
            } else {
                x.type = "password";
                y.type = "password";
            }
        }
    </script>
@endpush
