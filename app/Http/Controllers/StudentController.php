<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filterDept = $request->query('department');
        $filterClass = $request->query('class');

        $activeYear = AcademicYear::where('is_active', true)->first();
        $activeYearId = $activeYear?->id;

        $students = Student::with(['user', 'department', 'academicYear'])
            ->when($activeYearId, function ($query, $activeYearId) {
                $query->where('academic_year_id', $activeYearId);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nis', 'like', "%{$search}%")
                        ->orWhere('class_name', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($filterDept, function ($query, $filterDept) {
                $query->whereHas('department', fn ($q) => $q->where('name', $filterDept));
            })
            ->when($filterClass, function ($query, $filterClass) {
                $query->where('class_name', $filterClass);
            })
            ->join('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.name', 'asc')
            ->select('students.*')
            ->paginate(25)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();
        $availableClasses = Student::AVAILABLE_CLASSES;

        return view('students.index', compact('students', 'search', 'filterDept', 'filterClass', 'departments', 'availableClasses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $availableClasses = Student::AVAILABLE_CLASSES;

        return view('students.create', compact('departments', 'availableClasses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            // Auto-generate email if empty
            $email = ! empty($validated['email'])
                ? $validated['email']
                : $validated['nis'].'@smkn2magelang.sch.id';

            // Auto-detect active academic year
            $activeYear = AcademicYear::where('is_active', true)->first();

            // Step 1: Create User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $email,
                'password' => Hash::make($validated['nis']),
                'role' => 'student',
            ]);

            // Assign Spatie role
            $user->assignRole('student');

            // Step 2: Create Student profile
            Student::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'],
                'class_name' => $validated['class_name'],
                'place_of_birth' => $validated['place_of_birth'],
                'date_of_birth' => $validated['date_of_birth'],
                'department_id' => $validated['department_id'],
                'academic_year_id' => $activeYear?->id,
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);
        });

        CacheService::flushDashboard();

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
        $availableClasses = Student::AVAILABLE_CLASSES;

        return view('students.edit', compact('student', 'departments', 'availableClasses'));
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
                $email = $nis.'@smkn2magelang.sch.id';
            } elseif (! empty($validated['email'])) {
                $email = $validated['email'];
            }

            // If NIS changed, and email was using the old NIS pattern, update it
            $oldNisEmail = $student->nis.'@smkn2magelang.sch.id';
            if ($student->user->email === $oldNisEmail && $validated['nis'] !== $student->nis) {
                $email = $validated['nis'].'@smkn2magelang.sch.id';
            }

            // Update User
            $userData = [
                'name' => $validated['name'],
                'email' => $email,
            ];

            // Reset password to NIS if requested
            if ($request->boolean('reset_password')) {
                $nis = $validated['nis'] ?? $student->nis;
                $userData['password'] = Hash::make($nis);
            }

            $student->user->update($userData);

            // Update Student
            $student->update([
                'nis' => $validated['nis'],
                'class_name' => $validated['class_name'],
                'place_of_birth' => $validated['place_of_birth'],
                'date_of_birth' => $validated['date_of_birth'],
                'department_id' => $validated['department_id'],
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);
        });

        CacheService::flushDashboard();

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

        CacheService::flushDashboard();

        return redirect()->route('students.index')
            ->with('success', 'Peserta didik berhasil dihapus.');
    }
}
