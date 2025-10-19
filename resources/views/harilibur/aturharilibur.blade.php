@extends('layouts.app')
@section('titlepage', 'Atur Hari Libur')

@section('content')
@section('navigasi')
    <span>Atur Hari Libur</span>
@endsection

<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('harilibur.setharilibur')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-user-plus me-2"></i> Tambah Karyawan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <table class="table">
                            <tr>
                                <th>Kode Libur</th>
                                <td class="text-end">{{ $harilibur->kode_libur }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal</th>
                                <td class="text-end">{{ DateToIndo($harilibur->tanggal) }}</td>
                            </tr>


                            <tr>
                                <th>Cabang</th>
                                <td class="text-end">{{ $harilibur->nama_cabang }}</td>
                            </tr>

                            <tr>
                                <th>Keterangan</th>
                                <td class="text-end">{{ $harilibur->keterangan }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nik</th>
                                    <th>Nama Karyawan</th>
                                    <th>Dept</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="loadliburkaryawan">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        function loadliburkaryawan() {
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            $("#loadliburkaryawan").html(`<tr><td colspan="4" class="text-center">Loading...</td></tr>`);
            $("#loadliburkaryawan").load(`/harilibur/${kode_libur}/getkaryawanlibur`);
        }
        loadliburkaryawan();


        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        }
        $("#btnCreate").click(function() {
            loading();
            const kode_libur = "{{ Crypt::encrypt($harilibur->kode_libur) }}";
            $("#modal").modal("show");
            $(".modal-title").text("Input Hari Libur");
            $("#loadmodal").load(`/harilibur/${kode_libur}/aturkaryawan`);
        });

        $(document).on('click', '.delete', function(e) {
            const kode_libur = "{{ $harilibur->kode_libur }}";
            const nik = $(this).attr("nik");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: `/harilibur/deletekaryawanlibur`,
                        data: {
                            _token: "{{ csrf_token() }}",
                            kode_libur: kode_libur,
                            nik: nik
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond.success == true) {
                                loadliburkaryawan();
                            } else {
                                Swal.fire({
                                    title: "Oops!",
                                    text: respond.message,
                                    icon: "warning",
                                    showConfirmButton: true,
                                });
                            }
                        }
                    });
                    loadliburkaryawan();
                }
            })
        });
    });
</script>
@endpush
