@extends('layouts.phoenix')

@section('title', 'Admit Student')

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Student Admission</h2>
    </div>
  </div>

  <div class="card shadow-none border border-300">
    <div class="card-body">
      <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <label class="form-label" for="name">Full Name</label>
            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" type="text" value="{{ old('name') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label" for="email">Email Address</label>
            <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label" for="password">Login Password</label>
            <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label" for="admission_no">Admission Number (Leave blank to auto-generate)</label>
            <input class="form-control @error('admission_no') is-invalid @enderror" id="admission_no" name="admission_no" type="text" value="{{ old('admission_no') }}">
            @error('admission_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-4">
            <label class="form-label" for="class_id">Class</label>
            <select class="form-select select2 @error('class_id') is-invalid @enderror" id="class_id" name="class_id" required>
              <option value="">Select Class</option>
              @foreach($classes as $class)
              <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
              @endforeach
            </select>
            @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-4">
            <label class="form-label" for="section_id">Section</label>
            <select class="form-select select2 @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
              <option value="">Select Section</option>
              @foreach($classes as $class)
                @foreach($class->sections as $section)
                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>{{ $class->name }} - {{ $section->name }}</option>
                @endforeach
              @endforeach
            </select>
            @error('section_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-4">
            <label class="form-label" for="dob">Date of Birth</label>
            <input class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" type="date" value="{{ old('dob') }}" required>
            @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-4">
            <label class="form-label" for="gender">Gender</label>
            <select class="form-select select2 @error('gender') is-invalid @enderror" id="gender" name="gender" required>
              <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
              <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
              <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-4">
            <label class="form-label" for="phone">Phone Number</label>
            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="text" value="{{ old('phone') }}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 col-md-4">
            <label class="form-label" for="document">Upload Document</label>
            <input class="form-control @error('document') is-invalid @enderror" id="document" name="document" type="file">
            @error('document') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12">
            <label class="form-label" for="address">Address</label>
            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-12 text-end">
            <button class="btn btn-primary px-5" type="submit">Submit Admission</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
