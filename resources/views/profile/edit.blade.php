@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-6">
    {{-- Page Header --}}
    <div>
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">Edit Profil</h1>
        <p class="text-sm text-gray-500 dark:text-amoled-text mt-1">Kelola informasi akun dan keamanan Anda</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('status') === 'profile-updated')
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center gap-2.5 p-3 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 dark:bg-green-500/10 dark:text-green-400 dark:border-green-800/40" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
            <p><span class="font-bold">Berhasil!</span> Profil berhasil diperbarui.</p>
        </div>
    @endif

    @if(session('status') === 'password-updated')
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="flex items-center gap-2.5 p-3 text-sm text-green-800 border border-green-200 rounded-xl bg-green-50 dark:bg-green-500/10 dark:text-green-400 dark:border-green-800/40" role="alert">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/></svg>
            <p><span class="font-bold">Berhasil!</span> Password berhasil diperbarui.</p>
        </div>
    @endif

    {{-- Update Profile Information --}}
    @include('profile.partials.update-profile-information-form')

    {{-- Update Password --}}
    @include('profile.partials.update-password-form')

</div>
@endsection
