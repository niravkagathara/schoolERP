@extends('layouts.phoenix')

@section('title', 'Classes')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Classes</h2>
    </div>
  </div>
  
  <div id="classes-table">
    <div class="mb-4">
      <div class="row g-3">
        <div class="col-auto">
          @can('manage-academics')
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
            <span class="fas fa-plus me-2"></span>Add Class
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
              <th class="align-middle" scope="col">CLASS NAME</th>
              <th class="align-middle" scope="col">SECTIONS</th>
              <th class="align-middle" scope="col">SUBJECTS</th>
              @can('manage-academics')
              <th class="align-middle text-end" scope="col">ACTIONS</th>
              @endcan
            </tr>
          </thead>
          <tbody class="list" id="classes-table-body">
            @foreach($classes as $class)
            <tr class="hover-actions-trigger btn-reveal-trigger position-static">
              <td class="id align-middle fw-semi-bold text-900">{{ $class->id }}</td>
              <td class="name align-middle white-space-nowrap fw-bold text-primary">{{ $class->name }}</td>
              <td class="sections align-middle">{{ $class->sections_count ?? 0 }}</td>
              <td class="subjects align-middle">{{ $class->subjects_count ?? 0 }}</td>
              @can('manage-academics')
              <td class="actions align-middle text-end white-space-nowrap">
                <div class="font-sans-serif btn-reveal-trigger position-static">
                  <button class="btn btn-sm btn-reveal dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="fas fa-ellipsis-h fs--2"></span>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end py-2">
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editClassModal{{ $class->id }}">Edit</button>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('academic.classes.destroy', $class->id) }}" method="POST">
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

@foreach($classes as $class)
<!-- Edit Modal -->
<div class="modal fade" id="editClassModal{{ $class->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Class</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('academic.classes.update', $class->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')
        <div class="modal-body text-start">
          <div class="mb-3">
            <label class="form-label">Class Name</label>
            <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ $class->name }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
<div class="modal fade" id="addClassModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Class</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('academic.classes.store') }}" method="POST" novalidate>
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" for="className">Class Name</label>
            <input class="form-control @error('name') is-invalid @enderror" id="className" name="name" type="text" value="{{ old('name') }}" placeholder="e.g. Class 10" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
