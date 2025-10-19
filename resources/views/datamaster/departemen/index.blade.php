@extends('layouts.app')
@section('titlepage', 'Departemen')

@section('content')
@section('navigasi')
   <span>Departemen</span>
@endsection

<div class="row">
   <div class="col-lg-6 col-sm-12 col-xs-12">
      <div class="card">
         <div class="card-header">
            @can('departemen.create')
               <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                  Departemen</a>
            @endcan
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-12">
                  <form action="{{ route('departemen.index') }}">
                     <div class="row">
                        {{-- <div class="col-lg-4 col-sm-12 col-md-12">
                           <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                              name="nama_karyawan" icon="ti ti-search" />
                        </div>
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button class="btn btn-primary"><i
                                 class="ti ti-icons ti-search me-1"></i>Cari</button>
                        </div> --}}
                     </div>

                  </form>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="table-responsive mb-2">
                     <table class="table  table-hover table-bordered table-striped">
                        <thead class="table-dark">
                           <tr>
                              <th>Kode Dept</th>
                              <th>Nama Departemen</th>
                              <th>#</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($departemen as $d)
                              <tr>
                                 <td>{{ $d->kode_dept }}</td>
                                 <td>{{ $d->nama_dept }}</td>
                                 <td>
                                    <div class="d-flex">
                                       @can('departemen.edit')
                                          <div>
                                             <a href="#" class="me-2 btnEdit"
                                                kode_dept="{{ Crypt::encrypt($d->kode_dept) }}">
                                                <i class="ti ti-edit text-success"></i>
                                             </a>
                                          </div>
                                       @endcan

                                       @can('departemen.delete')
                                          <div>
                                             <form method="POST" name="deleteform" class="deleteform"
                                                action="{{ route('departemen.delete', Crypt::encrypt($d->kode_dept)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm ml-1">
                                                   <i class="ti ti-trash text-danger"></i>
                                                </a>
                                             </form>
                                          </div>
                                       @endcan

                                    </div>
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<x-modal-form id="modal" show="loadmodal" />
@endsection
@push('myscript')
<script>
   $(function() {

      function loading() {
         $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
      };
      loading();

      $("#btnCreate").click(function() {
         $("#modal").modal("show");
         $(".modal-title").text("Tambah Data Departemen");
         $("#loadmodal").load("{{ route('departemen.create') }}");
      });


      $(".btnEdit").click(function() {
         loading();
         const kode_dept = $(this).attr("kode_dept");
         $("#modal").modal("show");
         $(".modal-title").text("Edit Departemen");
         $("#loadmodal").load(`/departemen/${kode_dept}`);
      });
   });
</script>
@endpush
