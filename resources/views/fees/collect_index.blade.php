@extends('layouts.phoenix')

@section('title', 'Fee Collection')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Fee Collection</h2>
    </div>
  </div>

  <div class="card shadow-none border border-300 mb-4">
    <div class="card-body">
      <form action="{{ route('fees.collect.index') }}" method="GET">
        <div class="row g-3 align-items-end">
          <div class="col-12 col-md-6">
            <label class="form-label">Search Student (Name or Admission No)</label>
            <input class="form-control" name="search" type="text" value="{{ request('search') }}" placeholder="Search..." required>
          </div>
          <div class="col-auto">
            <button class="btn btn-primary" type="submit">Search</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-none border border-300">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm fs--1 mb-0">
          <thead>
            <tr>
              <th class="align-middle">ADMISSION NO</th>
              <th class="align-middle">NAME</th>
              <th class="align-middle">CLASS</th>
              <th class="align-middle text-end">ALLOCATED FEES</th>
              <th class="align-middle text-end">PAID FEES</th>
              <th class="align-middle text-end">BALANCE</th>
              <th class="align-middle text-center">STATUS</th>
              <th class="align-middle text-end">ACTION</th>
            </tr>
          </thead>
          <tbody>
            @forelse($students as $student)
            @php
              $allocated = $student->class ? $student->class->fees->sum('amount') : 0;
              $paid = $student->feePayments->sum('amount_paid');
              $balance = $allocated - $paid;
              if ($balance <= 0 && $allocated > 0) {
                  $statusClass = 'success';
                  $statusText = 'Paid';
              } elseif ($paid > 0) {
                  $statusClass = 'warning';
                  $statusText = 'Partial';
              } else {
                  $statusClass = 'danger';
                  $statusText = 'Unpaid';
              }
            @endphp
            <tr>
              <td class="align-middle">{{ $student->admission_no }}</td>
              <td class="align-middle fw-bold">{{ $student->user->name ?? 'N/A' }}</td>
              <td class="align-middle">{{ $student->class->name ?? 'N/A' }}</td>
              <td class="align-middle text-end">${{ number_format($allocated, 2) }}</td>
              <td class="align-middle text-end">${{ number_format($paid, 2) }}</td>
              <td class="align-middle text-end fw-bold text-{{ $balance > 0 ? 'danger' : 'success' }}">${{ number_format(max(0, $balance), 2) }}</td>
              <td class="align-middle text-center">
                <span class="badge badge-phoenix badge-phoenix-{{ $statusClass }}">{{ $statusText }}</span>
              </td>
              <td class="align-middle text-end">
                <a href="{{ route('fees.collect.student', $student->id) }}" class="btn btn-sm btn-phoenix-primary">Collect Fees</a>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-3">No students found.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($students->hasPages())
    <div class="card-footer border-0">
      {{ $students->links('pagination::bootstrap-5') }}
    </div>
    @endif
  </div>
</div>
@endsection
