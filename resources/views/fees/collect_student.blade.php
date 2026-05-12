@extends('layouts.phoenix')

@section('title', 'Collect Fee - ' . $student->user->name)

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Collect Fee: {{ $student->user->name }}</h2>
      <p class="text-700">Class: {{ $student->class->name }} | Admission No: {{ $student->admission_no }}</p>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12 col-xl-4">
      <div class="card shadow-none border border-300">
        <div class="card-header border-bottom border-300">
          <h4 class="mb-0">New Payment</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('fees.payment.store') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <div class="mb-3">
              <label class="form-label">Fee Type</label>
              <select class="form-select select2 @error('fee_id') is-invalid @enderror" name="fee_id" required>
                <option value="">Select Fee</option>
                @foreach($availableFees as $fee)
                <option value="{{ $fee->id }}" {{ old('fee_id') == $fee->id ? 'selected' : '' }}>{{ $fee->category->name }} - ${{ $fee->amount }}</option>
                @endforeach
              </select>
              @error('fee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Amount Paid</label>
              <input class="form-control @error('amount_paid') is-invalid @enderror" name="amount_paid" type="number" step="0.01" value="{{ old('amount_paid') }}" required>
              @error('amount_paid') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Payment Date</label>
              <input class="form-control @error('payment_date') is-invalid @enderror" name="payment_date" type="date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
              @error('payment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Payment Method</label>
              <select class="form-select select2 @error('payment_method') is-invalid @enderror" name="payment_method" required>
                <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                <option value="Online" {{ old('payment_method') == 'Online' ? 'selected' : '' }}>Online</option>
              </select>
              @error('payment_method') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Remarks</label>
              <textarea class="form-control @error('remarks') is-invalid @enderror" name="remarks" rows="2">{{ old('remarks') }}</textarea>
              @error('remarks') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary w-100" type="submit">Record Payment</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-8">
      <div class="card shadow-none border border-300">
        <div class="card-header border-bottom border-300">
          <h4 class="mb-0">Payment History</h4>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm fs--1 mb-0 datatable">
              <thead>
                <tr>
                  <th class="align-middle">RECEIPT NO</th>
                  <th class="align-middle">FEE TYPE</th>
                  <th class="align-middle">AMOUNT</th>
                  <th class="align-middle">DATE</th>
                  <th class="align-middle">METHOD</th>
                  <th class="align-middle text-end">ACTION</th>
                </tr>
              </thead>
              <tbody>
                @foreach($payments as $payment)
                <tr>
                  <td class="align-middle">{{ $payment->receipt_no }}</td>
                  <td class="align-middle fw-bold">{{ $payment->fee->category->name }}</td>
                  <td class="align-middle text-primary fw-bold">${{ $payment->amount_paid }}</td>
                  <td class="align-middle">{{ $payment->payment_date }}</td>
                  <td class="align-middle">{{ $payment->payment_method }}</td>
                  <td class="align-middle text-end">
                    <a href="{{ route('fees.receipt.download', $payment->id) }}" class="btn btn-link text-info p-0">
                      <span class="fas fa-print me-1"></span>Print
                    </a>
                  </td>
                </tr>
                @endforeach
                @if($payments->isEmpty())
                <tr>
                  <td colspan="6" class="text-center py-4 text-700">No payment records found.</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
