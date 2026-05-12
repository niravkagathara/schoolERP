<x-guest-layout>
    <x-slot name="auth_title">Sign Up</x-slot>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3 text-start">
            <label class="form-label" for="name">Full Name</label>
            <input class="form-control @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Name" required autofocus />
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3 text-start">
            <label class="form-label" for="email">Email address</label>
            <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required />
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <label class="form-label" for="password">Password</label>
                <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" placeholder="Password" required />
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-6">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required />
            </div>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" id="termsService" type="checkbox" required />
            <label class="form-label fs--1 text-none" for="termsService">I accept the <a href="#!">terms </a>and <a href="#!">privacy policy</a></label>
        </div>

        <button class="btn btn-primary w-100 mb-3" type="submit">Sign Up</button>
        
        <div class="text-center">
            <a class="fs--1 fw-bold" href="{{ route('login') }}">Sign in to existing account</a>
        </div>
    </form>
</x-guest-layout>
