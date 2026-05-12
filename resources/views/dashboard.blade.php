@extends('layouts.phoenix')

@section('title', 'Dashboard')

@section('content')
<div class="pb-5">
  <div class="row g-4">
    <div class="col-12 col-xxl-6">
      <div class="mb-8">
        <h2 class="mb-2">School ERP Dashboard</h2>
        <h5 class="text-700 fw-semi-bold">Welcome back, {{ auth()->user()->name }}! Here's what's happening today.</h5>
      </div>
      <div class="row g-3">
        <div class="col-12 col-md-4">
          <div class="card h-100 shadow-none border border-300">
            <div class="card-body">
              <div class="d-flex align-items-center"><span class="badge badge-phoenix badge-phoenix-primary p-2"><span class="fas fa-user-graduate fs-2"></span></span>
                <div class="ms-3">
                  <h4 class="mb-0">{{ number_format($total_students) }}</h4>
                  <p class="text-800 fs--1 mb-0">Total Students</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card h-100 shadow-none border border-300">
            <div class="card-body">
              <div class="d-flex align-items-center"><span class="badge badge-phoenix badge-phoenix-warning p-2"><span class="fas fa-chalkboard-teacher fs-2"></span></span>
                <div class="ms-3">
                  <h4 class="mb-0">{{ number_format($total_staff) }}</h4>
                  <p class="text-800 fs--1 mb-0">Total Staff</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card h-100 shadow-none border border-300">
            <div class="card-body">
              <div class="d-flex align-items-center"><span class="badge badge-phoenix badge-phoenix-success p-2"><span class="fas fa-dollar-sign fs-2"></span></span>
                <div class="ms-3">
                  <h4 class="mb-0">${{ number_format($total_fees, 2) }}</h4>
                  <p class="text-800 fs--1 mb-0">Fees Collected</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @canany(['manage-students', 'mark-attendance', 'manage-fees', 'manage-homework'])
  <div class="mt-4">
    <div class="card shadow-none border border-300">
      <div class="card-header border-bottom border-300 bg-soft">
        <h4 class="mb-0">Quick Management</h4>
      </div>
      <div class="card-body">
        <div class="row g-3">
          @can('manage-students')
          <div class="col-auto">
            <a href="{{ route('students.create') }}" class="btn btn-phoenix-primary">
              <span class="fas fa-user-plus me-2"></span>New Admission
            </a>
          </div>
          @endcan
          @can('mark-attendance')
          <div class="col-auto">
            <a href="{{ route('attendance.index') }}" class="btn btn-phoenix-info">
              <span class="fas fa-calendar-check me-2"></span>Mark Attendance
            </a>
          </div>
          @endcan
          @can('manage-fees')
          <div class="col-auto">
            <a href="{{ route('fees.collect.index') }}" class="btn btn-phoenix-success">
              <span class="fas fa-hand-holding-usd me-2"></span>Collect Fees
            </a>
          </div>
          @endcan
          @can('manage-homework')
          <div class="col-auto">
            <a href="{{ route('homework.index') }}" class="btn btn-phoenix-warning">
              <span class="fas fa-book-open me-2"></span>Assign Homework
            </a>
          </div>
          @endcan
        </div>
      </div>
    </div>
  </div>
  @endcanany

  <div class="row g-4 mt-2">
    @if(auth()->user()->can('manage-students') || auth()->user()->hasAnyRole(['Teacher', 'Staff']))
    <div class="col-12 col-xl-6">
        <div class="card shadow-none border border-300 h-100">
            <div class="card-header border-bottom border-300">
                <h4 class="mb-0">Recent Admissions</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm fs--1 mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_admissions as $recent)
                            <tr>
                                <td>{{ $recent->user->name }}</td>
                                <td>{{ $recent->class->name }}</td>
                                <td>{{ $recent->created_at->format('d M') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-3">No recent admissions</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-12 @if(auth()->user()->can('manage-students') || auth()->user()->hasAnyRole(['Teacher', 'Staff'])) col-xl-6 @else col-12 @endif">
        <div class="card shadow-none border border-300 h-100">
            <div class="card-header border-bottom border-300">
                <h4 class="mb-0">Academic Sessions</h4>
            </div>
            <div class="card-body">
                <p class="text-700">Current Session: {{ $current_session->name ?? 'N/A' }}</p>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p class="fs--1 mt-2 mb-0">65% of academic year completed</p>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
