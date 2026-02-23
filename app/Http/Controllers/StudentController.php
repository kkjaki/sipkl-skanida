<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Department;
use App\Models\AcademicYear;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $students = Student::with(['user', 'department', 'academicYear'])
            ->when($search, function ($query, $search) {
                $query->where('nis', 'like', "%{$search}%")
                      ->orWhere('class_name', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
            })
            ->latest('user_id')
            ->paginate(10)
            ->withQueryString();

        return view('students.index', compact('students', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('students.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            // Auto-generate email if empty
            $email = !empty($validated['email'])
                ? $validated['email']
                : $validated['nis'] . '@smkn2magelang.sch.id';

            // Auto-detect active academic year
            $activeYear = AcademicYear::where('is_active', true)->first();

            // Step 1: Create User
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $email,
                'password' => Hash::make($validated['nis']),
                'role'     => 'student',
            ]);

            // Assign Spatie role
            $user->assignRole('student');

            // Step 2: Create Student profile
            Student::create([
                'user_id'          => $user->id,
                'nis'              => $validated['nis'],
                'class_name'       => $validated['class_name'],
                'department_id'    => $validated['department_id'],
                'academic_year_id' => $activeYear?->id,
                'address'          => $validated['address'] ?? null,
                'phone'            => $validated['phone'] ?? null,
            ]);
        });

        // Invalidate dashboard cache so stats refresh
        Cache::forget('dashboard_stats');

        return redirect()->route('students.index')
            ->with('success', 'Peserta didik berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::with(['user', 'academicYear'])->findOrFail($id);
        $departments = Department::orderBy('name')->get();

        return view('students.edit', compact('student', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, string $id)
    {
        $validated = $request->validated();
        $student = Student::with('user')->findOrFail($id);

        DB::transaction(function () use ($validated, $student, $request) {
            // Determine email
            $email = $student->user->email;

            if ($request->boolean('reset_email')) {
                // Reset to default school email pattern
                $nis = $validated['nis'] ?? $student->nis;
                $email = $nis . '@smkn2magelang.sch.id';
            } elseif (!empty($validated['email'])) {
                $email = $validated['email'];
            }

            // If NIS changed, and email was using the old NIS pattern, update it
            $oldNisEmail = $student->nis . '@smkn2magelang.sch.id';
            if ($student->user->email === $oldNisEmail && $validated['nis'] !== $student->nis) {
                $email = $validated['nis'] . '@smkn2magelang.sch.id';
            }

            // Update User
            $student->user->update([
                'name'  => $validated['name'],
                'email' => $email,
            ]);

            // Update Student
            $student->update([
                'nis'           => $validated['nis'],
                'class_name'    => $validated['class_name'],
                'department_id' => $validated['department_id'],
                'address'       => $validated['address'] ?? null,
                'phone'         => $validated['phone'] ?? null,
            ]);
        });

        // Invalidate dashboard cache so stats refresh
        Cache::forget('dashboard_stats');

        return redirect()->route('students.index')
            ->with('success', 'Data peserta didik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::with('user')->findOrFail($id);

        // Deleting the User cascades to Student (onDelete('cascade'))
        $student->user->delete();

        // Invalidate dashboard cache so stats refresh
        Cache::forget('dashboard_stats');

        return redirect()->route('students.index')
            ->with('success', 'Peserta didik berhasil dihapus.');
    }
}
