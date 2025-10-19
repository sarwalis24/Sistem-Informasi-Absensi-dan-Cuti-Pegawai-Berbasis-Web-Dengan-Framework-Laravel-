<style>
    #map {
        height: 300px;
        width: 100%;
    }

    #map_out {
        height: 300px;
        width: 100%;
    }
</style>

@if ($status == 'in')
    <div class="row">
        <div class="col-4 text-center">
            @if (!empty($presensi->foto_in))

                @if (Storage::disk('public')->exists('/uploads/absensi/' . $presensi->foto_in))
                    <img src="{{ url('/storage/uploads/absensi/' . $presensi->foto_in) }}" class="card-img rounded thumbnail" alt="">
                @else
                    <i class="ti ti-fingerprint text-success" style="font-size: 10rem;"></i>
                @endif
            @else
                <i class="ti ti-fingerprint text-success" style="font-size: 10rem;"></i>
            @endif
        </div>
        <div class="col-8">
            <table class="table">
                <tr>
                    <th>NPP</th>
                    <td>{{ $presensi->nik }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $presensi->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($presensi->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Jam Masuk</th>
                    <td>{{ date('d-m-Y H:i', strtotime($presensi->jam_in)) }}</td>
                </tr>
                <tr>
                    <th>Jarak</th>
                    <td>
                        @php
                            if (!empty($presensi->lokasi_in)) {
                                $lokasi_in = explode(',', $presensi->lokasi_in);
                                $latitude_in = $lokasi_in[0];
                                $longitude_in = $lokasi_in[1];
                                $jarak_in = HitungJarak($latitude, $longitude, $latitude_in, $longitude_in);
                            } else {
                                $jarak_in['meters'] = 0;
                            }

                        @endphp

                        {{ formatAngkaDesimal($jarak_in['meters']) }} Meter

                    </td>
                </tr>
            </table>

        </div>
    </div>
    @if (!empty($presensi->lokasi_in))
        <div class="row mt-3">
            <div class="col">
                <div id="map"></div>
            </div>
        </div>
    @endif
@else
    <div class="row">
        <div class="col-4 text-center">
            @if (!empty($presensi->foto_out))
                @if (Storage::disk('public')->exists('/uploads/absensi/' . $presensi->foto_out))
                    <img src="{{ url('/storage/uploads/absensi/' . $presensi->foto_out) }}" class="card-img rounded thumbnail" alt="">
                @else
                    <i class="ti ti-fingerprint text-success" style="font-size: 10rem;"></i>
                @endif
            @else
                <i class="ti ti-fingerprint text-success" style="font-size: 10rem;"></i>
            @endif
        </div>
        <div class="col-8">
            <table class="table">
                <tr>
                    <th>NIK</th>
                    <td>{{ $presensi->nik }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $presensi->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ DateToIndo($presensi->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Jam Pulang</th>
                    <td>{{ date('d-m-Y H:i', strtotime($presensi->jam_out)) }}</td>
                </tr>
                <tr>
                    <th>Jarak</th>
                    <td>
                        @php
                            if (!empty($presensi->lokasi_out)) {
                                $lokasi_out = explode(',', $presensi->lokasi_out);
                                $latitude_out = $lokasi_out[0];
                                $longitude_out = $lokasi_out[1];
                                $jarak_out = HitungJarak($latitude, $longitude, $latitude_out, $longitude_out);
                            } else {
                                $jarak_out['meters'] = 0;
                            }
                            
                        @endphp

                        {{ formatAngkaDesimal($jarak_out['meters']) }} Meter

                    </td>
                </tr>
            </table>

        </div>
    </div>
    @if (!empty($presensi->lokasi_out))
        <div class="row mt-3">
            <div class="col">
                <div id="map_out"></div>
            </div>
        </div>
    @endif
@endif

<script>
    var lokasi = "{{ $presensi->lokasi_in }}";
    var lok = lokasi.split(",");
    var latitude = lok[0];
    var longitude = lok[1];

    var latitude_kantor = "{{ $latitude }}";
    var longitude_kantor = "{{ $longitude }}";
    console.log(latitude_kantor + "," + longitude_kantor);
    var rd = "{{ $cabang->radius_cabang }}";
    var map = L.map('map', {
        center: [latitude, longitude],
        zoom: 15
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([latitude, longitude]).addTo(map);
    var circle = L.circle([latitude_kantor, longitude_kantor], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: rd
    }).addTo(map);

    setInterval(function() {
        map.invalidateSize();
    }, 100);
</script>


<script>
    var lokasi = "{{ $cabang->lokasi_out }}";
    var lok = lokasi.split(",");
    var latitude = lok[0];
    var longitude = lok[1];

    var latitude_kantor = "{{ $latitude }}";
    var longitude_kantor = "{{ $longitude }}";
    console.log(latitude_kantor + "," + longitude_kantor);
    var rd = "{{ $cabang->radius_cabang }}";
    var map_out = L.map('map_out', {
        center: [latitude, longitude],
        zoom: 15
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map_out);
    var marker = L.marker([latitude, longitude]).addTo(map_out);
    var circle = L.circle([latitude_kantor, longitude_kantor], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: rd
    }).addTo(map_out);

    setInterval(function() {
        map_out.invalidateSize();
    }, 100);
</script>
