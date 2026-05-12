@extends('layouts.phoenix')

@section('title', 'Role Permissions')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Role Permissions Matrix</h2>
    </div>
  </div>

  @foreach($roles as $role)
  <div class="card shadow-none border border-300 mb-4">
    <div class="card-header border-bottom border-300 bg-light">
      <h4 class="mb-0">Permissions for: {{ $role->name }}</h4>
    </div>
    <div class="card-body">
      <form action="{{ route('super_admin.permissions.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
          @foreach($permissions as $permission)
          <div class="col-6 col-md-4 col-xl-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="p_{{ $role->id }}_{{ $permission->id }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
              <label class="form-check-label" for="p_{{ $role->id }}_{{ $permission->id }}">
                {{ str_replace('-', ' ', ucwords($permission->name, '-')) }}
              </label>
            </div>
          </div>
          @endforeach
        </div>
        <div class="mt-4 text-end">
          <button class="btn btn-primary" type="submit">Save {{ $role->name }} Permissions</button>
        </div>
      </form>
    </div>
  </div>
  @endforeach
</div>
@endsection
