<form action="{{ route('departemen.store') }}" method="POST" id="formDepartemen">
   @csrf
   <x-input-with-icon label="Kode Departemen" name="kode_dept" icon="ti ti-barcode" />
   <x-input-with-icon label="Nama Departemen" name="nama_dept" icon="ti ti-building" />
   <di class="form-group mb-3">
      <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i> Submit</button>
   </di>
</form>
<script src="{{ asset('assets/js/pages/departemen.js') }}"></script>
