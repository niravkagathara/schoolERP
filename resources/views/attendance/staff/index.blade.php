@extends('layouts.phoenix')

@section('title', 'Staff Attendance')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Staff Attendance Entry</h2>
    </div>
  </div>

  <div class="card shadow-none border border-300 mb-4">
    <div class="card-body">
      <form action="{{ route('attendance.staff.index') }}" method="GET">
        <div class="row g-3 align-items-end">
          <div class="col-auto">
            <label class="form-label">Date</label>
            <input class="form-control" type="date" name="date" value="{{ $date }}" onchange="this.form.submit()">
          </div>
        </div>
      </form>
    </div>
  </div>

  <form action="{{ route('attendance.staff.store') }}" method="POST" novalidate>
    @csrf
    <input type="hidden" name="date" value="{{ $date }}">
    @error('attendance') <div class="alert alert-danger">{{ $message }}</div> @enderror
    
    <div class="card shadow-none border border-300">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm fs--1 mb-0">
            <thead>
              <tr>
                <th class="align-middle">STAFF NAME</th>
                <th class="align-middle">ROLE</th>
                <th class="align-middle text-center">STATUS</th>
              </tr>
            </thead>
            <tbody>
              @foreach($staffMembers as $staff)
              <tr>
                <td class="align-middle fw-bold">{{ $staff->name }}</td>
                <td class="align-middle">
                    @foreach($staff->roles as $role)
                    <span class="badge badge-phoenix badge-phoenix-info">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td class="align-middle text-center">
                  <div class="d-flex justify-content-center gap-3">
                    @php 
                      $currentStatus = $attendance[$staff->id]->status ?? 'Present';
                    @endphp
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="attendance[{{ $staff->id }}]" id="p{{ $staff->id }}" value="Present" {{ $currentStatus == 'Present' ? 'checked' : '' }}>
                      <label class="form-check-label text-success fw-bold" for="p{{ $staff->id }}">Present</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="attendance[{{ $staff->id }}]" id="a{{ $staff->id }}" value="Absent" {{ $currentStatus == 'Absent' ? 'checked' : '' }}>
                      <label class="form-check-label text-danger fw-bold" for="a{{ $staff->id }}">Absent</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="attendance[{{ $staff->id }}]" id="l{{ $staff->id }}" value="Late" {{ $currentStatus == 'Late' ? 'checked' : '' }}>
                      <label class="form-check-label text-warning fw-bold" for="l{{ $staff->id }}">Late</label>
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
        <button class="btn btn-primary px-5" type="submit">Update Staff Attendance</button>
      </div>
    </div>
  </form>
</div>
@endsection
