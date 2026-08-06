@extends('layouts.auth')

@section('title', 'Forgot Password — LearnUp')

@section('simple-card')
    <h1 class="font-heading text-xl font-bold text-gray-900 sm:text-2xl">Reset Your Password</h1>
    <p class="mt-2 text-center text-sm text-gray-500">
        Forgot your password? No problem. Just let us know your email address and we will email you a password
        reset link that will allow you to choose a new one.
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 flex w-full flex-col gap-5">
        @csrf

        <x-auth-input
            type="email"
            name="email"
            label="Email Address"
            autocomplete="email"
            value="{{ old('email') }}"
        />

        <button type="submit"
                class="w-full rounded-xl bg-gradient-to-r from-[#6a63e7] to-[#5147d3] px-6 py-3.5 font-semibold text-white shadow-[0_8px_25px_rgba(106,99,231,0.4)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_rgba(106,99,231,0.5)] focus:outline-none focus:ring-4 focus:ring-[#6a63e7]/25">
            Email Password Reset Link
        </button>
    </form>

    <a href="{{ route('login') }}" class="mt-6 text-center text-sm font-medium text-[#6a63e7] hover:text-[#5147d3]">
        ← Back to login
    </a>
@endsection
