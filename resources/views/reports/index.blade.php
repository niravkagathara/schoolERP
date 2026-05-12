@extends('layouts.phoenix')

@section('title', 'System Reports')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">System Reports</h2>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12 col-md-6 col-xl-4">
      <div class="card h-100 shadow-none border border-300 hover-bg-light transition-base">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="p-2 bg-soft-primary rounded-pill me-3">
                <span class="text-primary" data-feather="user-check"></span>
            </div>
            <h4 class="mb-0">Attendance Report</h4>
          </div>
          <p class="text-700 fs--1">Daily and monthly attendance statistics for students across all classes.</p>
          <a href="{{ route('attendance.report') }}" class="btn btn-link p-0 text-primary fw-bold">View Report <span class="fas fa-chevron-right ms-1 fs--2"></span></a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
      <div class="card h-100 shadow-none border border-300 hover-bg-light transition-base">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="p-2 bg-soft-success rounded-pill me-3">
                <span class="text-success" data-feather="dollar-sign"></span>
            </div>
            <h4 class="mb-0">Fee Collection Report</h4>
          </div>
          <p class="text-700 fs--1">Summary of collected fees, pending payments, and overall financial health.</p>
          <a href="{{ route('fees.collect.index') }}" class="btn btn-link p-0 text-success fw-bold">View Report <span class="fas fa-chevron-right ms-1 fs--2"></span></a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
      <div class="card h-100 shadow-none border border-300 hover-bg-light transition-base">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="p-2 bg-soft-warning rounded-pill me-3">
                <span class="text-warning" data-feather="clipboard"></span>
            </div>
            <h4 class="mb-0">Exam Results Report</h4>
          </div>
          <p class="text-700 fs--1">Detailed analysis of student performance and grade distributions.</p>
          <a href="{{ route('exams.index') }}" class="btn btn-link p-0 text-warning fw-bold">View Report <span class="fas fa-chevron-right ms-1 fs--2"></span></a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
      <div class="card h-100 shadow-none border border-300 hover-bg-light transition-base">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="p-2 bg-soft-info rounded-pill me-3">
                <span class="text-info" data-feather="users"></span>
            </div>
            <h4 class="mb-0">Student Enrollment</h4>
          </div>
          <p class="text-700 fs--1">Current enrollment list filtered by class, gender, and status.</p>
          <a href="{{ route('students.index') }}" class="btn btn-link p-0 text-info fw-bold">View Report <span class="fas fa-chevron-right ms-1 fs--2"></span></a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
