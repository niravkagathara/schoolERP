@extends('layouts.phoenix')

@section('title', 'Student Profile - ' . $student->user->name)

@section('content')
<div class="mb-9">
  <div class="row align-items-center justify-content-between g-3 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Student Profile</h2>
    </div>
    <div class="col-auto">
      <a href="{{ route('students.index') }}" class="btn btn-phoenix-secondary me-2">
        <span class="fas fa-chevron-left me-2"></span>Back to List
      </a>
      @can('manage-students')
      <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
        <span class="fas fa-edit me-2"></span>Edit Profile
      </a>
      @endcan
    </div>
  </div>

  <div class="row g-5">
    <div class="col-12 col-xl-4">
      <div class="card shadow-none border border-300 mb-3">
        <div class="card-body text-center">
          <div class="avatar avatar-5xl mb-3">
            <div class="avatar-name rounded-circle"><span>{{ substr($student->user->name, 0, 1) }}</span></div>
          </div>
          <h4 class="mb-1 text-800">{{ $student->user->name }}</h4>
          <p class="text-700">Admission No: <span class="fw-bold">{{ $student->admission_no }}</span></p>
          <div class="d-flex flex-center gap-2">
            <span class="badge badge-phoenix badge-phoenix-primary">{{ $student->class->name }}</span>
            <span class="badge badge-phoenix badge-phoenix-info">{{ $student->section->name }}</span>
          </div>
        </div>
      </div>

      <div class="card shadow-none border border-300">
        <div class="card-header border-bottom border-300">
          <h5 class="mb-0">Contact Information</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <h6 class="text-900 mb-1">Email</h6>
            <p class="text-800 mb-0">{{ $student->user->email }}</p>
          </div>
          <div class="mb-3">
            <h6 class="text-900 mb-1">Phone</h6>
            <p class="text-800 mb-0">{{ $student->phone ?? 'Not provided' }}</p>
          </div>
          <div class="mb-0">
            <h6 class="text-900 mb-1">Address</h6>
            <p class="text-800 mb-0">{{ $student->address ?? 'Not provided' }}</p>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-8">
      <div class="card shadow-none border border-300 mb-4">
        <div class="card-header border-bottom border-300 bg-soft">
          <h4 class="mb-0">Personal Details</h4>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12 col-sm-6">
              <h6 class="text-900 mb-1">Date of Birth</h6>
              <p class="text-800 mb-0">{{ \Carbon\Carbon::parse($student->dob)->format('d M, Y') }}</p>
            </div>
            <div class="col-12 col-sm-6">
              <h6 class="text-900 mb-1">Gender</h6>
              <p class="text-800 mb-0">{{ $student->gender }}</p>
            </div>
            <div class="col-12 col-sm-6">
              <h6 class="text-900 mb-1">Admission Date</h6>
              <p class="text-800 mb-0">{{ $student->created_at->format('d M, Y') }}</p>
            </div>
            <div class="col-12 col-sm-6">
              <h6 class="text-900 mb-1">Current Session</h6>
              <p class="text-800 mb-0">{{ $student->session->name ?? '2024-2025' }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-none border border-300">
        <div class="card-header border-bottom border-300 d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Fee Summary</h4>
          <a href="{{ route('fees.collect.student', $student->id) }}" class="btn btn-sm btn-phoenix-primary">Manage Fees</a>
        </div>
        <div class="card-body">
          <div class="table-responsive scrollbar">
            <table class="table table-sm fs--1 mb-0">
              <thead>
                <tr>
                  <th class="white-space-nowrap border-top">FEE TYPE</th>
                  <th class="border-top">AMOUNT</th>
                  <th class="border-top">STATUS</th>
                </tr>
              </thead>
              <tbody>
                @forelse($student->feePayments ?? [] as $payment)
                <tr>
                  <td class="align-middle fw-bold">{{ $payment->fee->category->name }}</td>
                  <td class="align-middle">${{ number_format($payment->amount_paid, 2) }}</td>
                  <td class="align-middle"><span class="badge badge-phoenix fs--2 badge-phoenix-success">Paid</span></td>
                </tr>
                @empty
                <tr>
                  <td colspan="3" class="text-center py-4">No recent payments found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
