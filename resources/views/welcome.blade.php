<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SchoolERP | Advanced School Management System</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet">
    
    <style>
      .hero-section {
        background: linear-gradient(135deg, #2a7be4 0%, #11448d 100%);
        color: white;
        padding: 100px 0;
      }
      .feature-card {
        transition: transform 0.3s;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      }
      .feature-card:hover {
        transform: translateY(-10px);
      }
    </style>
  </head>

  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
          <img src="{{ asset('assets/img/icons/logo.png') }}" alt="" width="30" class="d-inline-block align-text-top me-2">
          <span class="fw-bolder fs-2">SchoolERP</span>
        </a>
        <div class="ms-auto">
          @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary px-4">Dashboard</a>
          @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary px-4 me-2">Login</a>
            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="btn btn-primary px-4">Register</a>
            @endif
          @endauth
        </div>
      </div>
    </nav>

    <header class="hero-section text-center">
      <div class="container">
        <h1 class="display-3 fw-bolder mb-4 text-white">Next-Gen School Management</h1>
        <p class="lead mb-5 opacity-75">Streamline your academic, administrative, and financial workflows with our robust, modular ERP system.</p>
        <div class="d-flex justify-content-center gap-3">
          <a href="{{ route('login') }}" class="btn btn-lg btn-warning px-5 fw-bold">Get Started</a>
          <a href="#" class="btn btn-lg btn-outline-light px-5">Learn More</a>
        </div>
      </div>
    </header>

    <section class="py-9 bg-soft">
      <div class="container">
        <div class="text-center mb-7">
          <h2 class="fw-bolder mb-3">Core Features</h2>
          <p class="text-700">Everything you need to manage your institution efficiently.</p>
        </div>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card feature-card h-100 p-4">
              <div class="mb-3"><span class="fas fa-user-graduate fs-3 text-primary"></span></div>
              <h4>Student Management</h4>
              <p class="text-700">Comprehensive student profiles, admissions, and academic tracking.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card feature-card h-100 p-4">
              <div class="mb-3"><span class="fas fa-file-invoice-dollar fs-3 text-success"></span></div>
              <h4>Fee Collection</h4>
              <p class="text-700">Automated fee structures, collection, and receipt generation.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card feature-card h-100 p-4">
              <div class="mb-3"><span class="fas fa-calendar-check fs-3 text-warning"></span></div>
              <h4>Attendance & Exams</h4>
              <p class="text-700">Real-time attendance tracking and comprehensive examination modules.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="py-5 bg-white border-top">
      <div class="container text-center">
        <p class="mb-0 text-700">&copy; 2026 SchoolERP. All rights reserved.</p>
      </div>
    </footer>

    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
  </body>
</html>
