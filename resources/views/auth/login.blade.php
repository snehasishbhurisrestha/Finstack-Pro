<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form class="app-form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="row">

            <div class="col-12">
                <div class="mb-5 text-center text-lg-start">
                    <h2 class="text-white f-w-600">
                        Welcome To <span class="text-dark">{{ config('app.name', 'Laravel') }}!</span>
                    </h2>
                    <p class="f-s-16 mt-2">
                        Sign in with your data that you entered during your registration
                    </p>
                </div>
            </div>

            <!-- Email -->
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input class="form-control" id="email" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required autofocus>

                    <label for="email">Email</label>

                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            </div>

            <!-- Password -->
            <div class="col-12">
                <div class="form-floating mb-3">

                    <input class="form-control" id="password" name="password" type="password" placeholder="Password" required>

                    <label for="password">Password</label>

                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>

                <div class="text-end">
                    @if (Route::has('password.request'))
                        <a class="text-dark f-w-500 text-decoration-underline" href="{{ route('password.request') }}">
                            Forgot password
                        </a>
                    @endif
                </div>

            </div>

            <!-- Remember -->
            <div class="col-12">
                <div class="form-check d-flex align-items-center gap-2 mb-3">

                    <input class="form-check-input w-25 h-25" id="remember_me" type="checkbox" name="remember">

                    <label class="form-check-label text-dark mt-2 f-s-16" for="remember_me">
                        Remember me
                    </label>

                </div>
            </div>

            <!-- Login Button -->
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Sign In
                </button>
            </div>
            {{-- <div class="col-12 mt-3">
                <a href="{{ url('/') }}" class="btn btn-outline-primary text-white btn-lg w-100">
                    ← Back to Home
                </a>
            </div> --}}

            <!-- Register -->
            {{-- <div class="col-12 mt-4">
                <div class="text-center text-lg-start f-w-500">
                    Don't Have Your Account yet?

                    @if (Route::has('register'))
                        <a class="text-decoration-underline" href="{{ route('register') }}">
                            Sign up
                        </a>
                    @endif

                </div>
            </div> --}}

        </div>

    </form>

</x-guest-layout>