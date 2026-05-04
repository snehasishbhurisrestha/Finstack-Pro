<x-guest-layout>

    <form class="app-form" method="POST" action="{{ route('register.player') }}">
        @csrf

        <div class="row">

            <div class="col-12">
                <div class="mb-5 text-center text-lg-start">

                    <h2 class="text-white f-w-600">
                        Player Registration
                    </h2>

                    <p class="f-s-16 mt-2">
                        Join now to track your progress, improve your skills, and stay on top of your game
                    </p>

                </div>
            </div>


            {{-- NAME --}}
            <div class="col-12">
                <div class="form-floating mb-3">

                    <input class="form-control"
                           name="name"
                           type="text"
                           placeholder="Full Name"
                           required>

                    <label>Full Name</label>

                </div>
            </div>


            {{-- EMAIL --}}
            <div class="col-12">
                <div class="form-floating mb-3">

                    <input class="form-control"
                           name="email"
                           type="email"
                           placeholder="Email"
                           required>

                    <label>Email</label>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                </div>
            </div>


            {{-- PASSWORD --}}
            <div class="col-12">
                <div class="form-floating mb-3">

                    <input class="form-control"
                           name="password"
                           type="password"
                           placeholder="Password"
                           required>

                    <label>Password</label>

                </div>
            </div>


            {{-- CONFIRM PASSWORD --}}
            <div class="col-12">
                <div class="form-floating mb-3">

                    <input class="form-control"
                           name="password_confirmation"
                           type="password"
                           placeholder="Confirm Password"
                           required>

                    <label>Confirm Password</label>

                </div>
            </div>


            {{-- BUTTON --}}
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Create Account
                </button>
            </div>


            {{-- LOGIN LINK --}}
            <div class="col-12 mt-4">
                <div class="text-center text-lg-start f-w-500">

                    Already have an account?

                    <a class="text-decoration-underline" href="{{ route('login') }}">
                        Sign in
                    </a>

                </div>
            </div>

        </div>

    </form>

</x-guest-layout>