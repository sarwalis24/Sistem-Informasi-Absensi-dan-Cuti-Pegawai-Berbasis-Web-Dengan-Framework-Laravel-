<form action="{{ route('departemen.update', Crypt::encrypt($departemen->kode_dept)) }}" method="POST" id="formDepartemen">
   @csrf
   @method('PUT')
   <x-input-with-icon label="Kode Departemen" name="kode_dept" icon="ti ti-barcode" value="{{ $departemen->kode_dept }}" />
   <x-input-with-icon label="Nama Departemen" name="nama_dept" icon="ti ti-building" value="{{ $departemen->nama_dept }}" />
   <di class="form-group mb-3">
      <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i> Submit</button>
   </di>
</form>
<script src="{{ asset('assets/js/pages/departemen.js') }}"></script>
