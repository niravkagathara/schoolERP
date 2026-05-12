@extends('layouts.phoenix')

@section('title', 'Homework')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Homework Assignments</h2>
    </div>
  </div>

  <div class="mb-4">
    @can('manage-homework')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHomeworkModal">
      <span class="fas fa-plus me-2"></span>Assign Homework
    </button>
    @endcan
  </div>

  <div class="card shadow-none border border-300">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm fs--1 mb-0 datatable">
          <thead>
            <tr>
              <th class="align-middle">TITLE</th>
              <th class="align-middle">CLASS</th>
              <th class="align-middle">SUBJECT</th>
              <th class="align-middle">DUE DATE</th>
              <th class="align-middle">ATTACHMENT</th>
              @can('manage-homework')
              <th class="align-middle text-end">ACTIONS</th>
              @endcan
            </tr>
          </thead>
          <tbody>
            @foreach($homeworks as $homework)
            <tr>
              <td class="align-middle fw-bold text-primary">{{ $homework->title }}</td>
              <td class="align-middle">{{ $homework->class->name }} ({{ $homework->section->name }})</td>
              <td class="align-middle">{{ $homework->subject->name }}</td>
              <td class="align-middle">{{ \Carbon\Carbon::parse($homework->due_date)->format('d M, Y') }}</td>
              <td class="align-middle">
                @if($homework->file_path)
                <a href="{{ asset('storage/' . $homework->file_path) }}" target="_blank" class="btn btn-link p-0"><span class="fas fa-file-download me-1"></span>Download</a>
                @else
                <span class="text-700">No file</span>
                @endif
              </td>
              @can('manage-homework')
              <td class="align-middle text-end">
                <form action="{{ route('homework.destroy', $homework->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-phoenix-danger">Delete</button>
                </form>
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
<div class="modal fade" id="addHomeworkModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Assign New Homework</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('homework.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Title</label>
              <input class="form-control @error('title') is-invalid @enderror" name="title" type="text" value="{{ old('title') }}" required>
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Class</label>
              <select class="form-select select2 @error('class_id') is-invalid @enderror" name="class_id" data-dropdown-parent="#addHomeworkModal" required>
                @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                @endforeach
              </select>
              @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Section</label>
              <select class="form-select select2 @error('section_id') is-invalid @enderror" name="section_id" data-dropdown-parent="#addHomeworkModal" required>
                @foreach($classes as $class)
                  @foreach($class->sections as $section)
                  <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $class->name }} - {{ $section->name }}</option>
                  @endforeach
                @endforeach
              </select>
              @error('section_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Subject</label>
              <select class="form-select select2 @error('subject_id') is-invalid @enderror" name="subject_id" data-dropdown-parent="#addHomeworkModal" required>
                @foreach($subjects as $subject)
                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
              </select>
              @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Due Date</label>
              <input class="form-control @error('due_date') is-invalid @enderror" name="due_date" type="date" value="{{ old('due_date') }}" required>
              @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Attachment</label>
              <input class="form-control @error('file') is-invalid @enderror" name="file" type="file">
              @error('file') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description') }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Assign</button>
          <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
