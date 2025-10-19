<!-- ///////////// Js Files ////////////////////  -->
<!-- Jquery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap-->
<script src="{{ asset('assets/template/js/lib/popper.min.js') }}"></script>
<script src="{{ asset('assets/template/js/lib/bootstrap.min.js') }}"></script>
<!-- Ionicons -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<!-- Owl Carousel -->
{{-- <script src="{{ asset('') }}assets/js/plugins/owl-carousel/owl.carousel.min.js"></script> --}}
<!-- jQuery Circle Progress -->
<script src="{{ asset('assets/template/js/plugins/jquery-circle-progress/circle-progress.min.js') }}"></script>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script src="{{ asset('assets/template/js/maskMoney.js') }}"></script>
<!-- Base Js File -->
<script src="{{ asset('assets/template/js/base.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/rolldate@3.1.3/dist/rolldate.min.js"></script>
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
<script src="{{ asset('assets/vendor/face-api.min.js') }}"></script>
<style>
    .toast-bottom-full-width {
        bottom: 5rem
    }
</style>
{{-- <script>
    toastr.options.showEasing = 'swing';
    toastr.options.hideEasing = 'linear';
    toastr.options.progressBar = true;
    toastr.options.positionClass = 'toast-bottom-full-width';
    toastr.success("Berhasil", "Data Berhasil Disimpan", {
        timeOut: 3000
    });
</script> --}}
@if ($message = Session::get('success'))
    <script>
        toastr.options.showEasing = 'swing';
        toastr.options.hideEasing = 'linear';
        toastr.options.progressBar = true;
        toastr.options.positionClass = 'toast-bottom-full-width';
        toastr.success("Berhasil", "{{ $message }}", {
            timeOut: 3000
        });
    </script>
@endif

@if ($message = Session::get('error'))
    <script>
        toastr.options.showEasing = 'swing';
        toastr.options.hideEasing = 'linear';
        toastr.options.progressBar = true;
        toastr.options.positionClass = 'toast-bottom-full-width';
        toastr.error("Gagal", "{{ $message }}", {
            timeOut: 3000
        });
    </script>
@endif

@if ($message = Session::get('warning'))
    <script>
        toastr.options.showEasing = 'swing';
        toastr.options.hideEasing = 'linear';
        toastr.options.progressBar = true;
        toastr.warning("Warning", "{{ $message }}", {
            timeOut: 3000
        });
    </script>
@endif

@if ($errors->any())
    @php
        $err = '';
    @endphp
    @foreach ($errors->all() as $error)
        @php
            $err .= $error;
        @endphp
    @endforeach
    <script>
        toastr.options.showEasing = 'swing';
        toastr.options.hideEasing = 'linear';
        toastr.options.progressBar = true;
        // toastr.options.positionClass = 'toast-top-center';
        toastr.error("Gagal", "{{ $err }}", {
            timeOut: 3000
        });
    </script>
@endif
<script>
    $('.cancel-confirm').click(function(event) {
        var form = $(this).closest("form");
        var name = $(this).data("name");
        event.preventDefault();
        Swal.fire({
            title: `Apakah Anda Yakin Ingin Membatalkan Data Ini ?`,
            text: "Data ini akan dibatalkan.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            showCancelButton: true,
            confirmButtonColor: "#554bbb",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Batalkan Saja Saja!"
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
</script>
<script>
    $(document).ready(function() {

        function adjustZoom() {
            var width = $(window).width(); // Ambil lebar layar
            //alert(width);
            // $('body').css('zoom', '120%');
            if (width <= 400) { // Misalnya untuk layar kecil (mobile)
                $('body').css('zoom', '88%'); // Zoom out ke 80%
            } else if (width <= 768) { // Untuk tablet kecil
                $('body').css('zoom', '90%');
            } else {
                $('body').css('zoom', '100%'); // Normal zoom
            }
        }

        adjustZoom(); // Panggil saat halaman dimuat

        $(window).resize(function() {
            adjustZoom(); // Panggil lagi saat ukuran layar berubah
        });
    });
</script>
@stack('myscript')
