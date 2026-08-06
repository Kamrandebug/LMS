{{-- Login form (front face of the flip card) --}}
<div class="flex flex-col">

    <div class="mb-8 flex flex-col items-center">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#6a63e7] to-[#5147d3] shadow-lg shadow-[#6a63e7]/30">
            <span class="text-2xl font-bold text-white">L</span>
        </div>
        <h1 class="font-heading text-2xl font-bold text-gray-900 sm:text-3xl">Login to Your Account</h1>
        <p class="mt-2 text-sm text-gray-500">Welcome back! Let's sharpen those legal skills.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
        @csrf

        <x-auth-input
            type="email"
            name="email"
            label="Email Address"
            autocomplete="email"
            value="{{ old('email') }}"
        />

        <div class="space-y-1.5">
            <x-auth-input
                type="password"
                name="password"
                label="Password"
                autocomplete="current-password"
                :show-password-toggle="true"
            />

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-[#6a63e7] focus:ring-[#6a63e7]/30">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#6a63e7] transition-colors hover:text-[#5147d3]">
                    Forgot Password?
                </a>
            </div>
        </div>

        @if ($errors->has('email') || $errors->has('password'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <button type="submit"
                class="w-full rounded-xl bg-gradient-to-r from-[#6a63e7] to-[#5147d3] px-6 py-3.5 font-semibold text-white shadow-[0_8px_25px_rgba(106,99,231,0.4)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_rgba(106,99,231,0.5)] focus:outline-none focus:ring-4 focus:ring-[#6a63e7]/25 active:translate-y-0">
            Sign In
        </button>
    </form>

    <p class="mt-8 border-t border-gray-100 pt-6 text-center text-sm text-gray-500">
        Don't have an account?
        <button type="button" @click="flip()"
                class="font-semibold text-[#6a63e7] underline decoration-2 underline-offset-2 transition-colors hover:text-[#5147d3]">
            Sign Up
        </button>
    </p>
</div>
