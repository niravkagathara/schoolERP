@extends('layouts.phoenix')

@section('title', 'Subjects')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Subjects</h2>
    </div>
  </div>
  
  <div id="subjects-table">
    <div class="mb-4">
      <div class="row g-3">
        <div class="col-auto">
          @can('manage-academics')
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <span class="fas fa-plus me-2"></span>Add Subject
          </button>
          @endcan
        </div>
      </div>
    </div>
    
    <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-white border-top border-bottom border-200 position-relative top-1">
      <div class="table-responsive scrollbar-overlay mx-n1 px-1">
        <table class="table table-sm fs--1 mb-0 datatable">
          <thead>
            <tr>
              <th class="align-middle" scope="col">ID</th>
              <th class="align-middle" scope="col">SUBJECT NAME</th>
              <th class="align-middle" scope="col">CODE</th>
              <th class="align-middle" scope="col">CLASS</th>
              <th class="align-middle" scope="col">TEACHERS</th>
              @can('manage-academics')
              <th class="align-middle text-end" scope="col">ACTIONS</th>
              @endcan
            </tr>
          </thead>
          <tbody class="list" id="subjects-table-body">
            @foreach($subjects as $subject)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
              <td class="id align-middle fw-semi-bold text-900">{{ $subject->id }}</td>
              <td class="name align-middle white-space-nowrap fw-bold text-primary">{{ $subject->name }}</td>
              <td class="code align-middle">{{ $subject->code }}</td>
              <td class="class align-middle">
                @if($subject->class)
                  <span class="badge badge-phoenix badge-phoenix-info">{{ $subject->class->name }}</span>
                @else
                  <span class="text-500">Unassigned</span>
                @endif
              </td>
              <td class="teachers align-middle">
                @forelse($subject->teachers as $teacher)
                  <span class="badge badge-phoenix badge-phoenix-primary">{{ $teacher->user->name }}</span>
                @empty
                  <span class="text-500">None</span>
                @endforelse
              </td>
              @can('manage-academics')
              <td class="actions align-middle text-end white-space-nowrap">
                <div class="font-sans-serif btn-reveal-trigger position-static">
                  <button class="btn btn-sm btn-reveal dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fas fa-ellipsis-h fs--2"></span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end py-2">
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editSubjectModal{{ $subject->id }}">Edit</button>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('academic.subjects.destroy', $subject->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="dropdown-item text-danger">Delete</button>
                    </form>
                  </div>
                </div>
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

@foreach($subjects as $subject)
<!-- Edit Modal -->
<div class="modal fade" id="editSubjectModal{{ $subject->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-start">
      <div class="modal-header">
        <h5 class="modal-title">Edit Subject</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('academic.subjects.update', $subject->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ $subject->name }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Subject Code</label>
            <input class="form-control @error('code') is-invalid @enderror" name="code" type="text" value="{{ $subject->code }}" required>
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Assign to Class</label>
            <select class="form-select @error('class_id') is-invalid @enderror" name="class_id" required>
              <option value="">Select a Class</option>
              @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $subject->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
              @endforeach
            </select>
            @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Assign Teachers</label>
            <select class="form-select select2 @error('teachers') is-invalid @enderror" name="teachers[]" multiple="multiple" data-dropdown-parent="#editSubjectModal{{ $subject->id }}">
              @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ in_array($teacher->id, $subject->teachers->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $teacher->user->name }}</option>
              @endforeach
            </select>
            @error('teachers') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Update</button>
          <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<!-- Add Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Subject</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('academic.subjects.store') }}" method="POST" novalidate>
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}" placeholder="e.g. Mathematics" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Subject Code</label>
            <input class="form-control @error('code') is-invalid @enderror" name="code" type="text" value="{{ old('code') }}" placeholder="e.g. MATH101" required>
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Assign to Class</label>
            <select class="form-select @error('class_id') is-invalid @enderror" name="class_id" required>
              <option value="">Select a Class</option>
              @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
              @endforeach
            </select>
            @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Assign Teachers</label>
            <select class="form-select select2 @error('teachers') is-invalid @enderror" name="teachers[]" multiple="multiple" data-dropdown-parent="#addSubjectModal">
              @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" {{ in_array($teacher->id, old('teachers', [])) ? 'selected' : '' }}>{{ $teacher->user->name }}</option>
              @endforeach
            </select>
            @error('teachers') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
@endsection
