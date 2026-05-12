@extends('layouts.phoenix')

@section('title', 'Attendance Calendar - ' . $student->user->name)

@section('content')
<div class="mb-9">
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <h2 class="mb-0">Attendance Calendar</h2>
      <p class="text-700">{{ $student->user->name }} | {{ $student->admission_no }}</p>
    </div>
  </div>

  <div class="card shadow-none border border-300">
    <div class="card-body">
      <div id="attendanceCalendar"></div>
    </div>
  </div>
  
  <div class="mt-4">
      <div class="d-flex gap-3">
          <div class="d-flex align-items-center"><span class="badge bg-success me-1" style="width:12px; height:12px;"></span> Present</div>
          <div class="d-flex align-items-center"><span class="badge bg-danger me-1" style="width:12px; height:12px;"></span> Absent</div>
          <div class="d-flex align-items-center"><span class="badge bg-warning me-1" style="width:12px; height:12px;"></span> Late</div>
      </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendors/fullcalendar/index.global.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('attendanceCalendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap5',
            events: @json($events),
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            height: 'auto'
        });
        calendar.render();
    });
</script>
@endpush
