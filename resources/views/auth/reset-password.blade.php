@extends('layouts.auth')

@section('title', 'Reset Password — LearnUp')

@section('simple-card')
    <h1 class="font-heading text-xl font-bold text-gray-900 sm:text-2xl">Choose a New Password</h1>
    <p class="mt-2 text-center text-sm text-gray-500">
        Enter a new, strong password for your LearnUp account.
    </p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 flex w-full flex-col gap-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-auth-input
            type="email"
            name="email"
            label="Email Address"
            autocomplete="email"
            value="{{ old('email', $request->email) }}"
        />

        <x-auth-input
            type="password"
            name="password"
            label="New Password"
            autocomplete="new-password"
            :show-password-toggle="true"
        />

        <x-auth-input
            type="password"
            name="password_confirmation"
            label="Confirm New Password"
            autocomplete="new-password"
        />

        <button type="submit"
                class="w-full rounded-xl bg-gradient-to-r from-[#6a63e7] to-[#5147d3] px-6 py-3.5 font-semibold text-white shadow-[0_8px_25px_rgba(106,99,231,0.4)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_rgba(106,99,231,0.5)] focus:outline-none focus:ring-4 focus:ring-[#6a63e7]/25">
            Reset Password
        </button>
    </form>
@endsection
