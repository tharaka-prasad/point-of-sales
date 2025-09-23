@extends('layouts.auth')

@section('login')
    <div class="d-flex align-items-center justify-content-center min-vh-100 w-100"
        style="background: linear-gradient(135deg, #f0f4ff, #dbeafe);">

        <div class="d-flex shadow-lg rounded-4 overflow-hidden"
            style="width: 700px; max-width: 95%; background: #fff; border-radius: 25px;">

            {{-- Left Side (Logo & Branding) --}}
            <div class="d-flex flex-column justify-content-center align-items-center text-white p-5"
                style="flex: 1; background: #5a86f2; border-top-right-radius: 150px; border-bottom-right-radius: 150px;">
                <h2 class="fw-bold mb-4">Ekrain Technologies</h2>


                <img src="{{ $setting?->path_logo && file_exists(public_path($setting->path_logo))
                    ? url($setting->path_logo)
                    : asset('admin/images/logo_e.jpeg') }}"
                    alt="Logo" width="180" class="mb-3" style="border-radius: 20px;">

            </div>

            {{-- Right Side (Login Form) --}}
            <div class="p-5" style="flex: 1.2;">
                <h3 class="fw-bold text-dark mb-4">Login</h3>
                <form action="{{ route('login') }}" method="post" id="loginForm">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="email" name="email"
                                class="form-control py-2 px-3 rounded-start border-end-0 @error('email') is-invalid @enderror"
                                placeholder="Username" value="{{ old('email') }}" required autofocus>
                            <span class="input-group-text bg-light rounded-end"><i class="fas fa-user"></i></span>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1"><i class="far fa-times-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="password" name="password" class="form-control py-2 px-3 rounded-start border-end-0"
                                placeholder="Password" required>
                            <span class="input-group-text bg-light rounded-end"><i class="fas fa-lock"></i></span>
                        </div>
                    </div>

                    {{-- Login button --}}
                    <button type="submit" id="loginBtn"
                        class="btn btn-primary w-100 py-2 fw-semibold shadow-sm rounded-3">
                        <span id="loginText">Login</span>
                        <span id="loginSpinner" class="spinner-border spinner-border-sm text-light ms-2 d-none"
                            role="status"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Loading Script --}}
    @push('scripts')
        <script>
            document.getElementById('loginForm').addEventListener('submit', function() {
                let btn = document.getElementById('loginBtn');
                let text = document.getElementById('loginText');
                let spinner = document.getElementById('loginSpinner');
                btn.disabled = true;
                text.textContent = "Logging in...";
                spinner.classList.remove('d-none');
            });
        </script>
    @endpush
@endsection
