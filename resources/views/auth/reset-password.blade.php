<x-guest-layout>

    <form class="app-form" method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="row">

            <div class="col-12">
                <div class="mb-5 text-center text-lg-start">
                    <h2 class="text-white f-w-600">
                        Reset Your Password
                    </h2>
                    <p class="f-s-16 mt-2">
                        Enter your new password below
                    </p>
                </div>
            </div>

            <!-- Email -->
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input class="form-control" id="email" name="email" type="email"
                        placeholder="Email"
                        value="{{ old('email', $request->email) }}"
                        required autofocus>

                    <label for="email">Email</label>

                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input class="form-control" id="password" name="password" type="password"
                        placeholder="Password" required>

                    <label for="password">New Password</label>

                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="col-12">
                <div class="form-floating mb-3">
                    <input class="form-control" id="password_confirmation" name="password_confirmation"
                        type="password" placeholder="Confirm Password" required>

                    <label for="password_confirmation">Confirm Password</label>

                    @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit -->
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Reset Password
                </button>
            </div>
            <div class="col-12 mt-3">
                <a href="{{ url('/') }}" class="btn btn-outline-primary text-white btn-lg w-100">
                    ← Back to Home
                </a>
            </div>

        </div>

    </form>

</x-guest-layout>