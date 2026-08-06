{{-- Registration form (back face of the flip card) --}}
<div class="flex flex-col">

    <div class="mb-8 flex flex-col items-center">
        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-[#6a63e7] to-[#5147d3] shadow-lg shadow-[#6a63e7]/30">
            <span class="text-2xl font-bold text-white">L</span>
        </div>
        <h1 class="font-heading text-2xl font-bold text-gray-900 sm:text-3xl">Create New Account</h1>
        <p class="mt-2 text-sm text-gray-500">Join the LAT prep family — it only takes a minute.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5">
        @csrf

        <x-auth-input
            type="text"
            name="name"
            label="Full Name"
            autocomplete="name"
            value="{{ old('name') }}"
        />

        <x-auth-input
            type="email"
            name="email"
            label="Email Address"
            autocomplete="email"
            value="{{ old('email') }}"
        />

        <x-auth-input
            type="password"
            name="password"
            label="Create Password"
            autocomplete="new-password"
            :show-password-toggle="true"
        />

        <x-auth-input
            type="password"
            name="password_confirmation"
            label="Confirm Password"
            autocomplete="new-password"
        />

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit"
                class="w-full rounded-xl bg-gradient-to-r from-[#6a63e7] to-[#5147d3] px-6 py-3.5 font-semibold text-white shadow-[0_8px_25px_rgba(106,99,231,0.4)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_rgba(106,99,231,0.5)] focus:outline-none focus:ring-4 focus:ring-[#6a63e7]/25 active:translate-y-0">
            Sign Up
        </button>

        <p class="text-center text-xs leading-relaxed text-gray-400">
            By signing up, you agree to our
            <a href="{{ route('terms') }}" class="text-[#6a63e7] hover:underline">Terms</a> and
            <a href="{{ route('privacy') }}" class="text-[#6a63e7] hover:underline">Privacy Policy</a>.
        </p>
    </form>

    <p class="mt-8 border-t border-gray-100 pt-6 text-center text-sm text-gray-500">
        Already have an account?
        <button type="button" @click="flip()"
                class="font-semibold text-[#6a63e7] underline decoration-2 underline-offset-2 transition-colors hover:text-[#5147d3]">
            Sign In
        </button>
    </p>
</div>
