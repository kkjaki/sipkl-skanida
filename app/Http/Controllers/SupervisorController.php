<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupervisorRequest;
use App\Http\Requests\UpdateSupervisorRequest;
use App\Models\Department;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filterDept = $request->query('department');
        $filterRole = $request->query('role');

        $supervisors = Supervisor::with(['user', 'department'])
            ->when($search, function ($query, $search) {
                $query->where('nip', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->when($filterDept, function ($query, $filterDept) {
                $query->where('department_id', $filterDept);
            })
            ->when($filterRole, function ($query, $filterRole) {
                $query->whereHas('user', function ($q) use ($filterRole) {
                    if ($filterRole === 'department_head') {
                        $q->role('department_head');
                    } else {
                        $q->role('supervisor')->withoutRole('department_head');
                    }
                });
            })
            ->latest('user_id')
            ->paginate(10)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();

        return view('supervisors.index', compact('supervisors', 'search', 'departments', 'filterDept', 'filterRole'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('supervisors.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupervisorRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            // Auto-generate email if empty
            $email = ! empty($validated['email'])
                ? $validated['email']
                : $validated['nip'].'@smkn2magelang.sch.id';

            // Create User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $email,
                'password' => Hash::make($validated['nip']),
                'role' => 'supervisor',
            ]);

            // Assign roles
            $user->assignRole('supervisor');
            if ($request->boolean('is_department_head')) {
                $user->assignRole('department_head');
            }

            // Create Supervisor profile
            Supervisor::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'],
                'department_id' => $validated['department_id'],
            ]);
        });

        Cache::forget('dashboard_stats');

        return redirect()->route('supervisors.index')
            ->with('success', 'Guru pembimbing berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supervisor = Supervisor::with('user')->findOrFail($id);
        $departments = Department::orderBy('name')->get();

        return view('supervisors.edit', compact('supervisor', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupervisorRequest $request, string $id)
    {
        $validated = $request->validated();
        $supervisor = Supervisor::with('user')->findOrFail($id);

        DB::transaction(function () use ($validated, $supervisor, $request) {
            // Update email
            $email = ! empty($validated['email'])
                ? $validated['email']
                : $supervisor->user->email;

            // Update User
            $supervisor->user->update([
                'name' => $validated['name'],
                'email' => $email,
            ]);

            // Sync roles
            $roles = ['supervisor'];
            if ($request->boolean('is_department_head')) {
                $roles[] = 'department_head';
            }
            $supervisor->user->syncRoles($roles);

            // Update Supervisor
            $supervisor->update([
                'nip' => $validated['nip'],
                'department_id' => $validated['department_id'],
            ]);
        });

        Cache::forget('dashboard_stats');

        return redirect()->route('supervisors.index')
            ->with('success', 'Data guru pembimbing berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supervisor = Supervisor::with('user')->findOrFail($id);

        $hasRelatedData = $supervisor->internships()->exists()
            || $supervisor->allocations()->exists();

        if ($hasRelatedData) {
            return redirect()->route('supervisors.index')
                ->with('error', 'Guru pembimbing tidak dapat dihapus karena masih memiliki data tertaut.');
        }

        // Deleting the User cascades to Supervisor
        $supervisor->user->delete();

        Cache::forget('dashboard_stats');

        return redirect()->route('supervisors.index')
            ->with('success', 'Guru pembimbing berhasil dihapus.');
    }
}
