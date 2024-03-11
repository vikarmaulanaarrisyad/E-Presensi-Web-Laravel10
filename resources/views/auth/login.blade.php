@extends('layouts.guest')

@section('title', 'Login Page')

@section('content')
    <div class="login-form mt-1">
        <div class="section">
            <img src="{{ asset('template') }}/img/logo/logo_login.jpg" alt="image" class="form-image">
        </div>
        <div class="section mt-1">
            <h1>E-Presensi</h1>
            <h4>Silahkan login</h4>
        </div>
        <div class="section mt-1 mb-5">
            <form id="loginForm" action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group boxed">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        id="email" placeholder="Email address">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>

                <div class="form-group boxed">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>

                <div class="form-links mt-2">
                    <div><a href="page-forgot-password.html" class="text-muted">Forgot Password?</a></div>
                </div>
                <div class="form-button-group">
                    <button type="button" onclick="login()" class="btn btn-success btn-block btn-lg btn-login"
                        id="loginButton">
                        <span id="buttonText">Login</span>
                        <span id="loadingSpinner" style="display:none;">Loading...</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Menangani keypress event
        $(document).on('keypress', function(e) {
            if (e.which == 13) {
                login();
            }
        });

        // Fungsi untuk login
        function login() {
            let email = $('#email').val();
            let password = $('#password').val();

            if (!email) {
                toastr.info('Email wajib diisi');
                return;
            }

            if (!password) {
                toastr.info('Password wajib diisi');
                return;
            }

            // Disable the button to prevent multiple clicks during the Ajax request
            const loginButton = $('#loginButton');
            const buttonText = $('#buttonText');
            const loadingSpinner = $('#loadingSpinner');

            loginButton.attr('disabled', true);
            buttonText.hide();
            loadingSpinner.show();

            $.ajax({
                type: 'POST',
                url: '{{ route('login') }}',
                data: $('#loginForm').serialize(),
                success: function(response) {
                    // You can redirect or perform any other action here
                    toastr.success('Selamat anda berhasil login ke dalam sistem kami');

                    // Menunggu 3 detik sebelum mengarahkan ke dashboard
                    setTimeout(function() {
                        window.location.href = '{{ route('dashboard') }}';
                    }, 3000);
                },
                error: function(errors) {
                    // Handle the error response
                    loopErrors(errors.responseJSON.errors);
                    toastr.error(errors.responseJSON.message);
                },
                complete: function() {
                    // Re-enable the button and hide the loading indicator
                    loginButton.attr('disabled', false);
                    buttonText.show();
                    loadingSpinner.hide();
                }
            });
        }
    </script>
@endpush
