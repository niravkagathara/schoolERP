@extends('layouts.phoenix')

@section('title', 'Mark Attendance')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Mark Student Attendance</h2>
    </div>
  </div>

  <div class="card shadow-none border border-300 mb-4">
    <div class="card-body">
      <form action="{{ route('attendance.index') }}" method="GET" novalidate>
        <div class="row g-3 align-items-end">
          <div class="col-12 col-md-4">
            <label class="form-label">Class</label>
            <select class="form-select select2" name="class_id" required>
              <option value="">Select Class</option>
              @foreach($classes as $class)
              <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12 col-md-4">
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
          <div class="col-auto">
            <button class="btn btn-primary" type="submit">Filter Students</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @if(count($students) > 0)
  <form action="{{ route('attendance.store') }}" method="POST" novalidate>
    @csrf
    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
    <input type="hidden" name="section_id" value="{{ request('section_id') }}">
    
    <div class="card shadow-none border border-300">
      <div class="card-header border-bottom border-300">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="form-label mb-0">Date:</label>
            </div>
            <div class="col-auto">
                <input class="form-control form-control-sm" type="date" name="date" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm fs--1 mb-0">
            <thead>
              <tr>
                <th class="align-middle" style="width:20%;">ADMISSION NO</th>
                <th class="align-middle" style="width:40%;">NAME</th>
                <th class="align-middle text-center" style="width:40%;">STATUS</th>
              </tr>
            </thead>
            <tbody>
              @foreach($students as $student)
              <tr>
                <td class="align-middle">{{ $student->admission_no }}</td>
                <td class="align-middle fw-bold">{{ $student->user->name }}</td>
                <td class="align-middle text-center">
                  <div class="d-flex justify-content-center gap-3">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="attendance[{{ $student->id }}]" id="p{{ $student->id }}" value="present" checked>
                      <label class="form-check-label text-success fw-bold" for="p{{ $student->id }}">Present</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="attendance[{{ $student->id }}]" id="a{{ $student->id }}" value="absent">
                      <label class="form-check-label text-danger fw-bold" for="a{{ $student->id }}">Absent</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="attendance[{{ $student->id }}]" id="l{{ $student->id }}" value="late">
                      <label class="form-check-label text-warning fw-bold" for="l{{ $student->id }}">Late</label>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <div class="card-footer border-top border-300 text-end">
        <button class="btn btn-success px-5" type="submit">Submit Attendance</button>
      </div>
    </div>
  </form>
  @elseif(request('class_id'))
  <div class="alert alert-info">No students found for selected class/section.</div>
  @endif
</div>
@endsection
