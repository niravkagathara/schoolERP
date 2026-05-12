<x-guest-layout>
    <x-slot name="auth_title">Sign In</x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3 text-start">
            <label class="form-label" for="email">Email address</label>
            <div class="form-icon-container">
                <input class="form-control form-icon-input @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus />
                <span class="fas fa-user text-900 fs--1 form-icon"></span>
            </div>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 text-start">
            <label class="form-label" for="password">Password</label>
            <div class="form-icon-container">
                <input class="form-control form-icon-input @error('password') is-invalid @enderror" id="password" type="password" name="password" placeholder="Password" required />
                <span class="fas fa-key text-900 fs--1 form-icon"></span>
            </div>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row flex-between-center mb-7">
            <div class="col-auto">
                <div class="form-check mb-0">
                    <input class="form-check-input" id="basic-checkbox" type="checkbox" name="remember" />
                    <label class="form-check-label mb-0" for="basic-checkbox">Remember me</label>
                </div>
            </div>
            <div class="col-auto">
                @if (Route::has('password.request'))
                    <a class="fs--1 fw-semi-bold" href="{{ route('password.request') }}">Forgot Password?</a>
                @endif
            </div>
        </div>

        <button class="btn btn-primary w-100 mb-3" type="submit">Log In</button>
        
        <div class="text-center">
            <a class="fs--1 fw-bold" href="{{ route('register') }}">Create an account</a>
        </div>
    </form>
</x-guest-layout>
