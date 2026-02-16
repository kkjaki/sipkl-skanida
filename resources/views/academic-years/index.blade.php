@extends('layouts.app')

@section('content')
    <div class="flex flex-col gap-6">
        <!-- Top Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                Academic Year Management
            </h2>
            <a href="{{ route('academic-years.create') }}" class="inline-flex items-center justify-center gap-2.5 rounded-xl bg-school-blue py-2.5 px-6 text-center text-sm font-medium text-white hover:bg-school-blue/90 transition duration-150 ease-in-out shadow-sm">
                <span>
                    <svg class="fill-current w-4 h-4" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 0C4.47715 0 0 4.47715 0 10C0 15.5228 4.47715 20 10 20C15.5228 20 20 15.5228 20 10C20 4.47715 15.5228 0 10 0ZM15 11H11V15H9V11H5V9H9V5H11V9H15V11Z" fill=""/>
                    </svg>
                </span>
                New Academic Year
            </a>
        </div>

        @if(session('success'))
            <div class="flex w-full border-l-4 border-success bg-white dark:bg-amoled-surface px-4 py-3 shadow-sm rounded-r-xl">
                <div class="w-full">
                    <p class="text-sm text-success font-medium">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        <!-- Content Container -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-amoled-border dark:bg-amoled-surface">

            <!-- Table View (Desktop) -->
            <div class="hidden md:block max-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 text-left dark:bg-white/[0.04] border-b border-gray-200 dark:border-amoled-border">
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text xl:pl-8 w-16">
                                No
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                Name
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text">
                                Status
                            </th>
                            <th class="py-3.5 px-4 text-sm font-semibold text-gray-500 dark:text-amoled-text text-right xl:pr-8">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($academicYears as $index => $year)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.04] transition duration-150 border-b border-gray-200 dark:border-amoled-border last:border-b-0">
                                <td class="py-4 px-4 xl:pl-8">
                                    <span class="text-sm text-gray-500 dark:text-amoled-text">{{ $academicYears->firstItem() + $index }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <h5 class="font-medium text-gray-800 dark:text-white text-sm">{{ $year->name }}</h5>
                                </td>
                                <td class="py-4 px-4">
                                    @if($year->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-semibold bg-emerald-500/10 text-emerald-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-semibold bg-zinc-500/10 text-zinc-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                                            Non-Active
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 pr-8 xl:pr-8 text-right">
                                    <div class="flex items-center justify-end space-x-3">
                                        {{-- Set Active Button --}}
                                        @unless($year->is_active)
                                            <form action="{{ route('academic-years.activate', $year) }}" method="POST" class="inline-block" onsubmit="return confirm('Set {{ $year->name }} as the active academic year?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-gray-400 hover:text-emerald-500 transition duration-150" title="Set as Active">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endunless

                                        {{-- Edit --}}
                                        <a href="{{ route('academic-years.edit', $year) }}" class="text-gray-400 hover:text-school-blue transition duration-150" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('academic-years.destroy', $year) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this academic year?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-danger transition duration-150" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 px-4 text-center text-sm text-gray-500 dark:text-amoled-text">
                                    No academic years found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Card View (Mobile) -->
            <div class="md:hidden flex flex-col divide-y divide-gray-200 dark:divide-amoled-border">
                 @forelse ($academicYears as $index => $year)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                             <div>
                                 <h5 class="font-semibold text-gray-800 dark:text-white text-sm">{{ $year->name }}</h5>
                                 <div class="mt-1.5">
                                     @if($year->is_active)
                                         <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-semibold bg-emerald-500/10 text-emerald-500">
                                             <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                             Active
                                         </span>
                                     @else
                                         <span class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-xs font-semibold bg-zinc-500/10 text-zinc-500">
                                             <span class="w-1.5 h-1.5 rounded-full bg-zinc-500"></span>
                                             Non-Active
                                         </span>
                                     @endif
                                 </div>
                             </div>
                        </div>
                        <div class="flex items-center justify-end gap-4 mt-2">
                             @unless($year->is_active)
                                 <form action="{{ route('academic-years.activate', $year) }}" method="POST" class="inline-block" onsubmit="return confirm('Set {{ $year->name }} as active?');">
                                     @csrf
                                     @method('PATCH')
                                     <button type="submit" class="text-sm font-medium text-emerald-500 hover:text-emerald-400 flex items-center gap-1">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                         Activate
                                     </button>
                                 </form>
                             @endunless
                             <a href="{{ route('academic-years.edit', $year) }}" class="text-sm font-medium text-school-blue hover:text-school-blue/80 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit
                            </a>
                            <form action="{{ route('academic-years.destroy', $year) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this academic year?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-danger hover:text-danger/80 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                 @empty
                    <div class="p-6 text-center text-sm text-gray-500 dark:text-amoled-text">
                        No academic years found.
                    </div>
                 @endforelse
            </div>

            <div class="px-4 py-3 border-t border-gray-200 dark:border-amoled-border">
                {{ $academicYears->links() }}
            </div>
        </div>
    </div>
@endsection
