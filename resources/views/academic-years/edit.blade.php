@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Edit Academic Year
            </h2>
            <nav>
                <ol class="flex items-center gap-1.5 text-sm">
                    <li>
                        <a class="font-medium text-gray-400 dark:text-amoled-text hover:text-school-blue transition duration-150" href="{{ route('academic-years.index') }}">Academic Years</a>
                    </li>
                    <li class="text-gray-300 dark:text-gray-600">/</li>
                    <li class="font-medium text-gray-800 dark:text-gray-200">Edit</li>
                </ol>
            </nav>
        </div>

        <!-- Form Card -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">
            <div class="border-b border-gray-200 dark:border-amoled-border py-4 px-6 sm:px-8">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-amoled-text">
                    Academic Year Information
                </h3>
            </div>

            <form action="{{ route('academic-years.update', $academicYear) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 sm:p-8 space-y-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Academic Year Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $academicYear->name) }}"
                            placeholder="e.g. 2025/2026"
                            required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition duration-150 focus:border-school-blue focus:ring-3 focus:ring-school-blue/10 dark:border-amoled-border dark:bg-amoled-input dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-school-blue"
                        />
                        @error('name')
                            <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active Toggle -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Status
                        </label>
                        <label for="is_active" class="relative inline-flex items-center cursor-pointer gap-3" x-data="{ checked: {{ old('is_active', $academicYear->is_active) ? 'true' : 'false' }} }">
                            <input type="hidden" name="is_active" value="0">
                            <div class="relative">
                                <input
                                    type="checkbox"
                                    id="is_active"
                                    name="is_active"
                                    value="1"
                                    class="sr-only peer"
                                    x-model="checked"
                                    {{ old('is_active', $academicYear->is_active) ? 'checked' : '' }}
                                />
                                <div class="w-11 h-6 bg-gray-200 dark:bg-amoled-border rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:border-gray-300 dark:after:border-gray-600 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400" x-text="checked ? 'Active' : 'Non-Active'">{{ $academicYear->is_active ? 'Active' : 'Non-Active' }}</span>
                        </label>
                        <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500">
                            Setting this to active will deactivate all other academic years.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('academic-years.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-gray-200 dark:border-amoled-border bg-transparent py-2.5 px-6 text-sm font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.06] transition duration-150 ease-in-out">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Update Academic Year
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
