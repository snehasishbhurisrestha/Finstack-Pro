<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-floating mb-3">
            <input 
                class="form-control"
                id="email"
                name="email"
                type="email"
                placeholder="Email"
                value="{{ old('email') }}"
                required 
                autofocus
            >
            <label for="email">Email Address</label>

            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary btn-lg w-100">
                Email Password Reset Link
            </button>
        </div>
        <div class="col-12 mt-3">
                <a href="{{ url('/login') }}" class="btn btn-outline-primary text-white btn-lg w-100">
                    ← Back to Sign in
                </a>
            </div>
    </form>
</x-guest-layout>