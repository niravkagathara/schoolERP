@extends('layouts.phoenix')

@section('title', 'User Management')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">User Management</h2>
    </div>
  </div>

  <div class="card shadow-none border border-300">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm fs--1 mb-0 datatable">
          <thead>
            <tr>
              <th class="align-middle">NAME</th>
              <th class="align-middle">EMAIL</th>
              <th class="align-middle">CURRENT ROLE</th>
              <th class="align-middle text-end">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $user)
            <tr>
              <td class="align-middle fw-bold text-primary">{{ $user->name }}</td>
              <td class="align-middle">{{ $user->email }}</td>
              <td class="align-middle">
                @foreach($user->roles as $role)
                <span class="badge badge-phoenix badge-phoenix-primary">{{ $role->name }}</span>
                @endforeach
              </td>
              <td class="align-middle text-end">
                <button class="btn btn-sm btn-phoenix-primary" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $user->id }}">Change Role</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@foreach($users as $user)
<div class="modal fade" id="editRoleModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Role for {{ $user->name }}</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('super_admin.users.update_role', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Select Role</label>
            <select class="form-select select2" name="role" required>
              @foreach($roles as $role)
              <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" type="submit">Update Role</button>
          <button class="btn btn-outline-primary" type="button" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
