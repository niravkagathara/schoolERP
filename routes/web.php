<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Super Admin Routes
    Route::middleware(['permission:manage-users'])->prefix('super-admin')->name('super_admin.')->group(function () {
        Route::get('/users', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'index'])->name('users.index');
        Route::put('/users/{user}/role', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'updateRole'])->name('users.update_role');
        Route::get('/permissions', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'permissions'])->name('permissions.index');
        Route::put('/permissions/{role}', [\App\Http\Controllers\SuperAdmin\UserManagementController::class, 'updatePermissions'])->name('permissions.update');
    });

    // Academic Routes (View access for all auth users, scoped in controllers)
    Route::prefix('academic')->name('academic.')->group(function () {
        // Classes
        Route::get('/classes', [\App\Http\Controllers\Academic\ClassController::class, 'index'])->name('classes.index');
        Route::post('/classes', [\App\Http\Controllers\Academic\ClassController::class, 'store'])->name('classes.store')->middleware('permission:manage-academics');
        Route::put('/classes/{class}', [\App\Http\Controllers\Academic\ClassController::class, 'update'])->name('classes.update')->middleware('permission:manage-academics');
        Route::delete('/classes/{class}', [\App\Http\Controllers\Academic\ClassController::class, 'destroy'])->name('classes.destroy')->middleware('permission:manage-academics');
        
        // Sections
        Route::get('/sections', [\App\Http\Controllers\Academic\SectionController::class, 'index'])->name('sections.index');
        Route::post('/sections', [\App\Http\Controllers\Academic\SectionController::class, 'store'])->name('sections.store')->middleware('permission:manage-academics');
        Route::put('/sections/{section}', [\App\Http\Controllers\Academic\SectionController::class, 'update'])->name('sections.update')->middleware('permission:manage-academics');
        Route::delete('/sections/{section}', [\App\Http\Controllers\Academic\SectionController::class, 'destroy'])->name('sections.destroy')->middleware('permission:manage-academics');

        // Subjects
        Route::get('/subjects', [\App\Http\Controllers\Academic\SubjectController::class, 'index'])->name('subjects.index');
        Route::post('/subjects', [\App\Http\Controllers\Academic\SubjectController::class, 'store'])->name('subjects.store')->middleware('permission:manage-academics');
        Route::put('/subjects/{subject}', [\App\Http\Controllers\Academic\SubjectController::class, 'update'])->name('subjects.update')->middleware('permission:manage-academics');
        Route::delete('/subjects/{subject}', [\App\Http\Controllers\Academic\SubjectController::class, 'destroy'])->name('subjects.destroy')->middleware('permission:manage-academics');
    });

    // Student Routes
    Route::get('students', [\App\Http\Controllers\Student\StudentController::class, 'index'])->name('students.index');
    Route::get('students/create', [\App\Http\Controllers\Student\StudentController::class, 'create'])->name('students.create')->middleware('permission:manage-students');
    Route::post('students', [\App\Http\Controllers\Student\StudentController::class, 'store'])->name('students.store')->middleware('permission:manage-students');
    Route::get('students/{student}', [\App\Http\Controllers\Student\StudentController::class, 'show'])->name('students.show');
    Route::get('students/{student}/edit', [\App\Http\Controllers\Student\StudentController::class, 'edit'])->name('students.edit')->middleware('permission:manage-students');
    Route::put('students/{student}', [\App\Http\Controllers\Student\StudentController::class, 'update'])->name('students.update')->middleware('permission:manage-students');
    Route::delete('students/{student}', [\App\Http\Controllers\Student\StudentController::class, 'destroy'])->name('students.destroy')->middleware('permission:manage-students');

    // Attendance Routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Attendance\AttendanceController::class, 'index'])->name('index')
            ->middleware(['permission:view-attendance|mark-attendance']);
        Route::post('/store', [\App\Http\Controllers\Attendance\AttendanceController::class, 'store'])->name('store')
            ->middleware(['permission:mark-attendance']);
        Route::get('/report', [\App\Http\Controllers\Attendance\AttendanceController::class, 'report'])->name('report')
            ->middleware(['permission:view-attendance']);
        Route::get('/calendar/{student}', [\App\Http\Controllers\Attendance\AttendanceController::class, 'studentCalendar'])->name('student.calendar')
            ->middleware(['permission:view-attendance']);
        
        // Staff Attendance
        Route::get('/staff', [\App\Http\Controllers\Attendance\StaffAttendanceController::class, 'index'])->name('staff.index')
            ->middleware(['permission:mark-attendance']);
        Route::post('/staff', [\App\Http\Controllers\Attendance\StaffAttendanceController::class, 'store'])->name('staff.store')
            ->middleware(['permission:mark-attendance']);
    });

    // Fee Routes
    Route::prefix('fees')->name('fees.')->group(function () {
        Route::get('/categories', [\App\Http\Controllers\Fee\FeeController::class, 'categories'])->name('categories')
            ->middleware(['permission:manage-fees']);
        Route::post('/categories', [\App\Http\Controllers\Fee\FeeController::class, 'storeCategory'])->name('categories.store')
            ->middleware(['permission:manage-fees']);
        Route::get('/collect', [\App\Http\Controllers\Fee\FeeController::class, 'collectIndex'])->name('collect.index')
            ->middleware(['permission:manage-fees']);
        Route::get('/collect/{student}', [\App\Http\Controllers\Fee\FeeController::class, 'collectStudent'])->name('collect.student')
            ->middleware(['permission:view-fees|manage-fees']);
        Route::post('/payment', [\App\Http\Controllers\Fee\FeeController::class, 'storePayment'])->name('payment.store')
            ->middleware(['permission:manage-fees']);
        Route::get('/receipt/{payment}', [\App\Http\Controllers\Fee\FeeController::class, 'downloadReceipt'])->name('receipt.download')
            ->middleware(['permission:view-fees|manage-fees']);
    });

    // Exam Routes
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Exam\ExamController::class, 'index'])->name('index')
            ->middleware(['permission:view-results|manage-exams']);
        Route::post('/', [\App\Http\Controllers\Exam\ExamController::class, 'store'])->name('store')
            ->middleware(['permission:manage-exams']);
        Route::get('/marks', [\App\Http\Controllers\Exam\ExamController::class, 'marksEntry'])->name('marks.entry')
            ->middleware(['permission:manage-exams']);
        Route::post('/marks', [\App\Http\Controllers\Exam\ExamController::class, 'storeMarks'])->name('marks.store')
            ->middleware(['permission:manage-exams']);
    });

    // Homework Routes
    Route::prefix('homework')->name('homework.')->group(function () {
        Route::get('/', [\App\Http\Controllers\HomeworkController::class, 'index'])->name('index')
            ->middleware(['permission:view-homework|manage-homework']);
        Route::post('/', [\App\Http\Controllers\HomeworkController::class, 'store'])->name('store')
            ->middleware(['permission:manage-homework']);
        Route::delete('/{homework}', [\App\Http\Controllers\HomeworkController::class, 'destroy'])->name('destroy')
            ->middleware(['permission:manage-homework']);
    });

    // Reports Routes
    Route::get('/reports', function() {
        return view('reports.index');
    })->name('reports.index')->middleware(['permission:view-reports']);

    // Staff Routes
    Route::prefix('staff')->name('staff.')->middleware(['permission:manage-staff'])->group(function () {
        Route::get('/', [\App\Http\Controllers\StaffController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\StaffController::class, 'store'])->name('store');
        Route::put('/{teacher}', [\App\Http\Controllers\StaffController::class, 'update'])->name('update');
        Route::delete('/{teacher}', [\App\Http\Controllers\StaffController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
