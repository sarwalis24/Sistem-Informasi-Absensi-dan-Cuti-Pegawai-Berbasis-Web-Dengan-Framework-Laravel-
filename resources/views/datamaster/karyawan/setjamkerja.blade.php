<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>NIK</th>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $karyawan->nama_karyawan }}</td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>{{ textUpperCase($karyawan->nama_dept) }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td>{{ textUpperCase($karyawan->nama_cabang) }}</td>
            </tr>

        </table>

    </div>
</div>
<div class="row">
    <div class="col">
        <div class="nav-align-top  mb-6">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home"
                        aria-controls="navs-top-home" aria-selected="true">Set Jam Kerja</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile"
                        aria-controls="navs-top-profile" aria-selected="false" tabindex="-1">Set Jam Kerja By Date</button>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                    <form action="{{ route('karyawan.storejamkerjabyday', Crypt::encrypt($karyawan->nik)) }}" id="formSetJamkerja" method="POST">
                        @csrf
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nama_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                @endphp
                                @foreach ($nama_hari as $hari)
                                    <tr>
                                        <td class="text-capitalize" style="width: 10%">
                                            <input type="hidden" name="hari[]" value="{{ $hari }}">
                                            {{ $hari }}
                                        </td>
                                        <td>
                                            <div class="form-group p-0" style="margin-bottom: 0px !important">
                                                <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                                    <option value="">Pilih Jam Kerja</option>
                                                    @foreach ($jamkerja as $d)
                                                        @if (array_key_exists($hari, $jamkerjabyday) && $jamkerjabyday[$hari] == $d->kode_jam_kerja)
                                                            <option value="{{ $d->kode_jam_kerja }}" selected>{{ $d->nama_jam_kerja }}
                                                                ({{ $d->jam_masuk }} -
                                                                {{ $d->jam_pulang }})
                                                            </option>
                                                        @else
                                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}
                                                                ({{ $d->jam_masuk }} -
                                                                {{ $d->jam_pulang }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Update Jam
                                Kerja</button></button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-3">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
                                    @foreach ($list_bulan as $d)
                                        <option {{ $d['kode_bulan'] == date('m') ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                            {{ $d['nama_bulan'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mb-3">
                                <select name="tahun" id="tahun" class="form-select">
                                    <option value="">Tahun</option>
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option {{ $t == date('Y') ? 'selected' : '' }} value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <form action="#" id="formJamkerjabydate">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <x-input-with-icon icon="ti ti-calendar" label="Tanggal" datepicker="flatpickr-date" name="tanggal" />
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <div class="form-group p-0" style="margin-bottom: 0px !important">
                                    <select name="kode_jam_kerja" id="kode_jam_kerja" class="form-select">
                                        <option value="">Pilih Jam Kerja</option>
                                        @foreach ($jamkerja as $d)
                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }} ({{ $d->jam_masuk }} -
                                                {{ $d->jam_pulang }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-12 col-sm-12">
                                <a href="#" class=" btn btn-primary" id="btnAddjamkerjabydate">
                                    <i class="ti ti-calendar-plus"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <th>Tanggal</th>
                                    <th>Jam Kerja</th>
                                    <th>#</th>
                                </thead>
                                <tbody id="getjamkerjabydate"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.flatpickr-date').flatpickr();
        const formJamkerjabydate = $("#formJamkerjabydate");
        $("#btnAddjamkerjabydate").click(function() {
            let nik = "{{ $karyawan->nik }}";
            let tanggal = formJamkerjabydate.find("#tanggal").val();
            let kode_jam_kerja = formJamkerjabydate.find("#kode_jam_kerja").val();

            if (tanggal == "") {
                swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formJamkerjabydate.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_jam_kerja == "") {
                swal.fire({
                    title: "Oops!",
                    text: "Jam Kerja Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formJamkerjabydate.find("#kode_jam_kerja").focus();
                    },
                });
                return false;
            } else {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('karyawan.storejamkerjabydate') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        nik: nik,
                        tanggal: tanggal,
                        kode_jam_kerja: kode_jam_kerja
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond.success == false) {
                            swal.fire({
                                title: "Oops!",
                                text: respond.message,
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    formJamkerjabydate.find("#tanggal").focus();
                                },
                            });
                            return false;
                        } else {
                            swal.fire({
                                title: "Berhasil!",
                                text: "Berhasil Menambahkan Jam Kerja !",
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    formJamkerjabydate.find("#tanggal").val("");
                                    formJamkerjabydate.find("#kode_jam_kerja").val("");
                                    //formJamkerjabydate.find("#tanggal").focus();
                                    loadjamkerjabydate();
                                },
                            })
                        }

                    },
                    error: function(respond) {
                        swal.fire({
                            title: "Oops!",
                            text: "Gagal Menambahkan Jam Kerja ! " + respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                            didClose: (e) => {
                                formJamkerjabydate.find("#kode_jam_kerja").focus();
                            },
                        });
                    }
                });
            }
        });

        $("#bulan, #tahun").change(function() {
            loadjamkerjabydate();
        });

        function loadjamkerjabydate() {
            let bulan = $("#bulan").val();
            let tahun = $("#tahun").val();
            let nik = "{{ $karyawan->nik }}";
            $.ajax({
                type: 'POST',
                url: "{{ route('karyawan.getjamkerjabydate') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $(document).find("#getjamkerjabydate").html("");
                    respond.map((d) => {
                        //kosongkan row

                        $(document).find("#getjamkerjabydate").append(`
                            <tr>
                                <td>${d.tanggal}</td>
                                <td>${d.nama_jam_kerja} ${d.jam_masuk} - ${d.jam_pulang}</td>
                                <td>
                                    <a href="#" class="deletejamkerjabydate" tanggal="${d.tanggal}" nik="${d.nik}">
                                        <i class="ti ti-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        `);
                    })
                },
                error: function(respond) {
                    console.log(error);
                }
            });

        }

        loadjamkerjabydate();

        $(document).on("click", ".deletejamkerjabydate", function(e) {
            let tanggal = $(this).attr("tanggal");
            let nik = $(this).attr("nik");
            swal.fire({
                title: "Apakah Anda Yakin ?",
                text: "Data Jam Kerja Tanggal " + tanggal + " Akan Dihapus !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus !",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('karyawan.deletejamkerjabydate') }}",
                        type: "POST",
                        data: {
                            nik: nik,
                            tanggal: tanggal,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            swal.fire({
                                title: "Berhasil !",
                                text: response.message,
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    loadjamkerjabydate();
                                },
                            });

                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            swal.fire({
                                title: "Oops!",
                                text: xhr.responseJSON.message,
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    loadjamkerjabydate();
                                },
                            })
                        },
                    });
                }
            });
        });
    });
</script>
