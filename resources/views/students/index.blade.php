@extends('layouts.phoenix')

@section('title', 'Students')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Students</h2>
    </div>
  </div>
  
  <div id="students-table">
    <div class="mb-4">
      <div class="row g-3">
        <div class="col-auto">
          @can('manage-students')
          <a href="{{ route('students.create') }}" class="btn btn-primary">
            <span class="fas fa-plus me-2"></span>Admit Student
          </a>
          @endcan
        </div>
      </div>
    </div>
    
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-white border-top border-bottom border-200 position-relative top-1">
      <div class="table-responsive scrollbar-overlay mx-n1 px-1">
        <table class="table table-sm fs--1 mb-0 datatable">
          <thead>
            <tr>
              <th class="align-middle" scope="col">ADMISSION NO</th>
              <th class="align-middle" scope="col">NAME</th>
              <th class="align-middle" scope="col">CLASS</th>
              <th class="align-middle" scope="col">SECTION</th>
              <th class="align-middle" scope="col">GENDER</th>
              <th class="align-middle text-end" scope="col">ACTIONS</th>
            </tr>
          </thead>
          <tbody class="list">
            @foreach($students as $student)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
              <td class="admission_no align-middle fw-bold">{{ $student->admission_no }}</td>
              <td class="name align-middle white-space-nowrap fw-bold text-primary">{{ $student->user->name }}</td>
              <td class="class align-middle">{{ $student->class->name }}</td>
              <td class="section align-middle">{{ $student->section->name }}</td>
              <td class="gender align-middle">{{ $student->gender }}</td>
              <td class="actions align-middle text-end white-space-nowrap">
                <div class="font-sans-serif btn-reveal-trigger position-static">
                  <button class="btn btn-sm btn-reveal dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fas fa-ellipsis-h fs--2"></span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end py-2">
                    <a class="dropdown-item" href="{{ route('students.show', $student->id) }}">View Profile</a>
                    @can('view-attendance')
                    <a class="dropdown-item" href="{{ route('attendance.student.calendar', $student->id) }}">View Attendance</a>
                    @endcan
                    @can('manage-students')
                    <a class="dropdown-item" href="#!">Edit</a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="dropdown-item text-danger">Delete</button>
                    </form>
                    @endcan
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
