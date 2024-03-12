@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
@endsection

@push('css_vendor')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts_vendor')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@push('css')
    <style>
        .webcame-capture,
        .webcame-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 400px;
        }
    </style>
@endpush

@section('content')
    <div class="row" style="margin-top: 70px;">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcame-capture"></div>
        </div>
    </div>
    <div class="row">
        @if ($cekPresensi > 0)
            @if ($cekJamOut)
                <div class="col">
                    <button id="takeabsen" class="btn btn-danger btn-block mt-1">Absen Pulang</button>
                </div>
            @endif
        @else
            <div class="col">
                <button id="takeabsen" class="btn btn-primary btn-block mt-1">Absen Masuk</button>
            </div>
        @endif
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 80,
        })

        Webcam.attach('.webcame-capture');
        var lokasi = document.getElementById('lokasi');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;

            let map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

            //-6.908262821419684,109.15962572615987
            var circle = L.circle([position.coords.latitude, position.coords.longitude], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 20
            }).addTo(map);
        }

        function errorCallback() {
            //
        }

        //menangkap hasil kamera
        $('#takeabsen').on('click', function(e) {
            Webcam.snap(function(uri) {
                image = uri;
            });
            var lokasi = $('#lokasi').val();
            if (!lokasi) return alert('Lokasi tidak ditemukan');

            // Disable the button during the AJAX request
            $('#takeabsen').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: '{{ route('presensi.store') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi
                },
                cache: false,
                success: function(response) {
                    toastr.success(response.message)
                    // Menunggu 4 detik sebelum mengarahkan ke dashboard
                    setTimeout(function() {
                        window.location.href = '{{ route('dashboard') }}';
                    }, 4000);

                    // Disable the button after a successful request
                    $('#takeabsen').prop('disabled', true);
                },
                error: function(errors) {
                    toastr.error(errors.responseJSON.message)

                    // Enable the button after an unsuccessful request
                    $('#takeabsen').prop('disabled', false);
                }
            })
        })
    </script>
@endpush
