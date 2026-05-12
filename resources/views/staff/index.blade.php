@extends('layouts.phoenix')

@section('title', 'Staff Management')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Staff & Teachers</h2>
    </div>
  </div>

  <div class="mb-4">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
      <span class="fas fa-plus me-2"></span>Add New Staff
    </button>
  </div>

  <div class="card shadow-none border border-300">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm fs--1 mb-0 datatable">
          <thead>
            <tr>
              <th class="align-middle">NAME</th>
              <th class="align-middle">ROLE</th>
              <th class="align-middle">DESIGNATION</th>
              <th class="align-middle">PHONE</th>
              <th class="align-middle">QUALIFICATION</th>
              <th class="align-middle text-end">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @foreach($staffMembers as $staff)
            <tr>
              <td class="align-middle fw-bold text-primary">{{ $staff->user->name }}</td>
              <td class="align-middle">
                @foreach($staff->user->roles as $role)
                <span class="badge badge-phoenix badge-phoenix-info">{{ $role->name }}</span>
                @endforeach
              </td>
              <td class="align-middle">{{ $staff->designation }}</td>
              <td class="align-middle">{{ $staff->phone }}</td>
              <td class="align-middle">{{ $staff->qualification }}</td>
              <td class="align-middle text-end">
                <button class="btn btn-sm btn-phoenix-primary me-2" data-bs-toggle="modal" data-bs-target="#editStaffModal{{ $staff->id }}">Edit</button>
                <form action="{{ route('staff.destroy', $staff->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-phoenix-danger">Remove</button>
                </form>
              </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editStaffModal{{ $staff->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content text-start">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Staff Member</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="{{ route('staff.update', $staff->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                      <div class="row g-3">
                        <div class="col-md-6">
                          <label class="form-label">Full Name</label>
                          <input class="form-control" name="name" type="text" value="{{ old('name', $staff->user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Email</label>
                          <input class="form-control" name="email" type="email" value="{{ old('email', $staff->user->email) }}" required>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Password (leave blank to keep current)</label>
                          <input class="form-control" name="password" type="password">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Role</label>
                          <select class="form-select" name="role" required>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $staff->user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Designation</label>
                          <input class="form-control" name="designation" type="text" value="{{ old('designation', $staff->designation) }}">
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Phone</label>
                          <input class="form-control" name="phone" type="text" value="{{ old('phone', $staff->phone) }}">
                        </div>
                        <div class="col-12">
                          <label class="form-label">Qualification</label>
                          <input class="form-control" name="qualification" type="text" value="{{ old('qualification', $staff->qualification) }}">
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-primary" type="submit">Update Staff</button>
                      <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Staff Member</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('staff.store') }}" method="POST" novalidate>
        @csrf
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" value="{{ old('name') }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input class="form-control @error('email') is-invalid @enderror" name="email" type="email" value="{{ old('email') }}" required>
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required>
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Role</label>
              <select class="form-select @error('role') is-invalid @enderror" name="role" required>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
              </select>
              @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Designation</label>
              <input class="form-control @error('designation') is-invalid @enderror" name="designation" type="text" value="{{ old('designation') }}" placeholder="e.g. Senior Teacher">
              @error('designation') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input class="form-control @error('phone') is-invalid @enderror" name="phone" type="text" value="{{ old('phone') }}">
              @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
              <label class="form-label">Qualification</label>
              <input class="form-control @error('qualification') is-invalid @enderror" name="qualification" type="text" value="{{ old('qualification') }}">
              @error('qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Create Staff</button>
          <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
