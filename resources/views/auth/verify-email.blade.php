@extends('layouts.auth')

@section('title', 'Verify Email — LearnUp')

@section('simple-card')
    <h1 class="font-heading text-xl font-bold text-gray-900 sm:text-2xl">Verify Your Email</h1>
    <p class="mt-2 text-center text-sm text-gray-500">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the
        link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mt-4 w-full rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            ✅ A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <div class="mt-6 flex w-full flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full rounded-xl bg-gradient-to-r from-[#6a63e7] to-[#5147d3] px-6 py-3.5 font-semibold text-white shadow-[0_8px_25px_rgba(106,99,231,0.4)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_30px_rgba(106,99,231,0.5)]">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-sm font-medium text-gray-500 transition-colors hover:text-gray-700">
                Log Out
            </button>
        </form>
    </div>
@endsection
