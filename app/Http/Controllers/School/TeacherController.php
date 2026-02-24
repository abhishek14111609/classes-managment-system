<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Batch;
use App\Models\User;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'batches'])
            ->latest()
            ->paginate(15);

        return view('school.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $batches = Batch::active()->get();

        return view('school.teachers.create', compact('batches'));
    }

    public function store(StoreTeacherRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $userData = [
                    'school_id' => auth()->user()->school_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'username' => $request->username ?? explode('@', $request->email)[0],
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'is_active' => $request->boolean('is_active', true),
                ];

                if ($request->hasFile('avatar')) {
                    $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
                }

                $user = User::create($userData);
                $user->assignRole('teacher');

                $employeeId = $request->employee_id;
                if (empty($employeeId)) {
                    $schoolName = auth()->user()->school->name ?? 'INS';
                    $schoolPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $schoolName), 0, 3));
                    $instituteType = auth()->user()->school->institute_type ?? 'academic';
                    $employeePrefix = $schoolPrefix . ($instituteType === 'sport' ? '-COA-' : '-EMP-');
                    $employeeId = $employeePrefix . date('y') . strtoupper(\Illuminate\Support\Str::random(4));
                }

                $teacher = Teacher::create([
                    'school_id' => auth()->user()->school_id,
                    'user_id' => $user->id,
                    'employee_id' => $employeeId,
                    'qualification' => $request->qualification,
                    'specialization' => $request->specialization,
                    'joining_date' => $request->joining_date,
                    'salary' => $request->salary,
                    'is_active' => $request->boolean('is_active', true),
                ]);

                if ($request->has('batches')) {
                    $teacher->batches()->sync($request->batches);
                }
            });

            return redirect()->route('school.teachers.index')
                ->with('success', 'Teacher created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating teacher: ' . $e->getMessage());
        }
    }

    public function edit(Teacher $teacher)
    {
        $batches = Batch::active()->get();
        $teacher->load('batches');

        return view('school.teachers.edit', compact('teacher', 'batches'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        try {
            DB::transaction(function () use ($request, $teacher) {
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'is_active' => $request->boolean('is_active', true),
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                if ($request->hasFile('avatar')) {
                    $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
                }

                $teacher->user->update($userData);

                $teacher->update([
                    'employee_id' => $request->employee_id,
                    'qualification' => $request->qualification,
                    'specialization' => $request->specialization,
                    'joining_date' => $request->joining_date,
                    'salary' => $request->salary,
                    'is_active' => $request->boolean('is_active', true),
                ]);

                if ($request->has('batches')) {
                    $teacher->batches()->sync($request->batches);
                }
            });

            return redirect()->route('school.teachers.index')
                ->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating teacher: ' . $e->getMessage());
        }
    }

    public function destroy(Teacher $teacher)
    {
        try {
            DB::transaction(function () use ($teacher) {
                $teacher->user->delete();
                $teacher->delete();
            });

            return redirect()->route('school.teachers.index')
                ->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting teacher: ' . $e->getMessage());
        }
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'batches.students', 'coachedEvents']);

        return view('school.teachers.show', compact('teacher'));
    }
}
