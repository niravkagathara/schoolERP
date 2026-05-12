@extends('layouts.phoenix')

@section('title', 'Exams')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Examinations</h2>
    </div>
  </div>

  <div class="mb-4">
    @can('manage-exams')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExamModal">
      <span class="fas fa-plus me-2"></span>Create New Exam
    </button>
    @endcan
  </div>

  <div class="card shadow-none border border-300">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm fs--1 mb-0">
          <thead>
            <tr>
              <th class="align-middle">EXAM NAME</th>
              <th class="align-middle">CLASS</th>
              <th class="align-middle">DATE CREATED</th>
              @can('manage-exams')
              <th class="align-middle text-end">ACTIONS</th>
              @endcan
            </tr>
          </thead>
          <tbody>
            @foreach($exams as $exam)
            <tr>
              <td class="align-middle fw-bold text-primary">{{ $exam->name }}</td>
              <td class="align-middle">{{ $exam->class->name }}</td>
              <td class="align-middle">{{ $exam->created_at->format('d M, Y') }}</td>
              @can('manage-exams')
              <td class="align-middle text-end">
                <a href="{{ route('exams.marks.entry') }}?exam_id={{ $exam->id }}&class_id={{ $exam->class_id }}" class="btn btn-sm btn-phoenix-success">Enter Marks</a>
              </td>
              @endcan
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
@can('manage-exams')
<div class="modal fade" id="addExamModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create Exam</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('exams.store') }}" method="POST" novalidate>
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Exam Name</label>
            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Class</label>
            <select class="form-select select2 @error('class_id') is-invalid @enderror" name="class_id" data-dropdown-parent="#addExamModal" required>
              @foreach($classes as $class)
              <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
              @endforeach
            </select>
            @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Create</button>
          <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endcan
@endsection
