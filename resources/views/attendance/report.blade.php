@extends('layouts.phoenix')

@section('title', 'Attendance Report')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Student Attendance Report</h2>
    </div>
  </div>

  <div class="card shadow-none border border-300 mb-4">
    <div class="card-body">
      <form action="{{ route('attendance.report') }}" method="GET">
        <div class="row g-3 align-items-end">
          <div class="col-12 col-md-3">
            <label class="form-label">Class</label>
            <select class="form-select select2" name="class_id" required>
              <option value="">Select Class</option>
              @foreach($classes as $class)
              <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 col-md-3">
            <label class="form-label">Section</label>
            <select class="form-select select2" name="section_id" required>
              <option value="">Select Section</option>
              @foreach($classes as $class)
                @foreach($class->sections as $section)
                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $class->name }} - {{ $section->name }}</option>
                @endforeach
              @endforeach
            </select>
          </div>
          <div class="col-12 col-md-3">
            <label class="form-label">Date</label>
            <input class="form-control" type="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
          </div>
          <div class="col-auto">
            <button class="btn btn-primary" type="submit">Generate Report</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @if(count($attendanceData) > 0)
  <div class="card shadow-none border border-300">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm fs--1 mb-0">
          <thead>
            <tr>
              <th class="align-middle">ADMISSION NO</th>
              <th class="align-middle">STUDENT NAME</th>
              <th class="align-middle text-center">STATUS</th>
            </tr>
          </thead>
          <tbody>
            @foreach($attendanceData as $record)
            <tr>
              <td class="align-middle">{{ $record->student->admission_no }}</td>
              <td class="align-middle fw-bold">{{ $record->student->user->name }}</td>
              <td class="align-middle text-center">
                <span class="badge badge-phoenix fs--2 {{ $record->status == 'Present' ? 'badge-phoenix-success' : ($record->status == 'Absent' ? 'badge-phoenix-danger' : 'badge-phoenix-warning') }}">
                    {{ $record->status }}
                </span>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @elseif(request('class_id'))
  <div class="alert alert-info">No attendance records found for the selected criteria.</div>
  @endif
</div>
@endsection
