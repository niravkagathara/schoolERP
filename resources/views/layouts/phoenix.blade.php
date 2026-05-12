<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'School ERP') | Phoenix</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicons/favicon-16x16.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicons/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/favicons/mstile-150x150.png') }}">
    <meta name="theme-color" content="#ffffff">
    
    <script src="{{ asset('vendors/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('vendors/simplebar/simplebar.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="{{ asset('assets/css/theme-rtl.min.css') }}" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/css/theme.min.css') }}" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/css/user-rtl.min.css') }}" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('assets/css/user.min.css') }}" type="text/css" rel="stylesheet" id="user-style-default">
    
    <!-- DataTables & Select2 CDNs -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

    <style>
        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #d8e2ef;
            border-radius: 0.5rem;
            padding: 0.5rem;
            height: auto;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0;
            margin-left: 2px;
        }
        .dropdown-menu-end {
            right: 0 !important;
            left: auto !important;
        }
        /* Select2 Invalid State */
        .is-invalid + .select2-container--bootstrap-5 .select2-selection {
            border-color: #f64e60 !important;
        }
        .invalid-feedback {
            display: block;
        }
        @media (min-width: 992px) {
            .content {
                padding-top: 5rem !important;
            }
        }
    </style>

    <script>
      var phoenixIsRTL = window.config.config.phoenixIsRTL;
      if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
      } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
      }
    </script>
    @stack('styles')
  </head>

  <body>
    <main class="main" id="top">
      <nav class="navbar navbar-vertical navbar-expand-lg">
        <script>
          var navbarStyle = window.config.config.phoenixNavbarVerticalStyle;
          if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('body').classList.add(`navbar-vertical-${navbarStyle}`);
          }
        </script>
        <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
          <div class="navbar-vertical-content">
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
              <li class="nav-item">
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('dashboard') }}">
                    <div class="d-flex align-items-center">
                      <span class="nav-link-icon"><span data-feather="pie-chart"></span></span>
                      <span class="nav-link-text">Dashboard</span>
                    </div>
                  </a>
                </div>
              </li>
              
              @if(auth()->user()->can('manage-academics') || auth()->user()->hasAnyRole(['Teacher', 'Staff', 'Student']))
              <li class="nav-item">
                <p class="navbar-vertical-label">Academic</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('academic.classes.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="grid"></span></span><span class="nav-link-text">Classes</span></div>
                  </a>
                </div>
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('academic.sections.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="columns"></span></span><span class="nav-link-text">Sections</span></div>
                  </a>
                </div>
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('academic.subjects.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="book"></span></span><span class="nav-link-text">Subjects</span></div>
                  </a>
                </div>
              </li>
              @endif

              @if(auth()->user()->can('manage-students') || auth()->user()->hasAnyRole(['Teacher', 'Staff', 'Student']))
              <li class="nav-item">
                <p class="navbar-vertical-label">Students</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  @if(auth()->user()->hasRole('Student') && auth()->user()->student)
                  <a class="nav-link label-1" href="{{ route('students.show', auth()->user()->student->id) }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="user"></span></span><span class="nav-link-text">My Profile</span></div>
                  </a>
                  @else
                  <a class="nav-link label-1" href="{{ route('students.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text">Student List</span></div>
                  </a>
                  @endif
                </div>
              </li>
              @endif

              @canany(['mark-attendance', 'view-attendance'])
              <li class="nav-item">
                <p class="navbar-vertical-label">Attendance</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  @if(auth()->user()->hasRole('Student') && auth()->user()->student)
                  <a class="nav-link label-1" href="{{ route('attendance.student.calendar', auth()->user()->student->id) }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="user-check"></span></span><span class="nav-link-text">My Attendance</span></div>
                  </a>
                  @else
                  <a class="nav-link label-1" href="{{ route('attendance.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="user-check"></span></span><span class="nav-link-text">Student Attendance</span></div>
                  </a>
                  @endif
                </div>
                @can('mark-attendance')
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('attendance.staff.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text">Staff Attendance</span></div>
                  </a>
                </div>
                @endcan
              </li>
              @endcanany

              @canany(['manage-fees', 'view-fees'])
              <li class="nav-item">
                <p class="navbar-vertical-label">Finance</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  @if(auth()->user()->hasRole('Student') && auth()->user()->student)
                  <a class="nav-link label-1" href="{{ route('fees.collect.student', auth()->user()->student->id) }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="dollar-sign"></span></span><span class="nav-link-text">My Fees</span></div>
                  </a>
                  @else
                  <a class="nav-link label-1" href="{{ route('fees.collect.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="dollar-sign"></span></span><span class="nav-link-text">Fee Collection</span></div>
                  </a>
                  @endif
                </div>
              </li>
              @endcanany

              @canany(['manage-exams', 'view-results'])
              <li class="nav-item">
                <p class="navbar-vertical-label">Examination</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('exams.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="clipboard"></span></span><span class="nav-link-text">Exams</span></div>
                  </a>
                </div>
              </li>
              @endcanany

              @can('manage-staff')
              <li class="nav-item">
                <p class="navbar-vertical-label">Human Resource</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('staff.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="briefcase"></span></span><span class="nav-link-text">Staff Management</span></div>
                  </a>
                </div>
              </li>
              @endcan

              <li class="nav-item">
                <p class="navbar-vertical-label">Learning</p>
                <hr class="navbar-vertical-line">
                @canany(['manage-homework', 'view-homework'])
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('homework.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="edit-3"></span></span><span class="nav-link-text">Homework</span></div>
                  </a>
                </div>
                @endcanany
              </li>

              @can('view-reports')
              <li class="nav-item">
                <p class="navbar-vertical-label">Reports</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('reports.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="bar-chart-2"></span></span><span class="nav-link-text">System Reports</span></div>
                  </a>
                </div>
              </li>
              @endcan

              @can('manage-users')
              <li class="nav-item">
                <p class="navbar-vertical-label">System</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('super_admin.users.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="users"></span></span><span class="nav-link-text">User Management</span></div>
                  </a>
                </div>
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('super_admin.permissions.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="lock"></span></span><span class="nav-link-text">Role Permissions</span></div>
                  </a>
                </div>
              </li>
              @endcan

              <li class="nav-item">
                <p class="navbar-vertical-label">Finance</p>
                <hr class="navbar-vertical-line">
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('fees.collect.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="dollar-sign"></span></span><span class="nav-link-text">Fees Collection</span></div>
                  </a>
                </div>
                <div class="nav-item-wrapper">
                  <a class="nav-link label-1" href="{{ route('reports.index') }}">
                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="bar-chart-2"></span></span><span class="nav-link-text">Reports</span></div>
                  </a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
        <div class="collapse navbar-collapse justify-content-between">
          <div class="navbar-logo">
            <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation">
                <span data-feather="menu" style="height:20px;width:20px;"></span>
            </button>
            <a class="navbar-brand me-1 me-sm-3" href="{{ url('/') }}">
              <div class="d-flex align-items-center">
                <div class="d-flex align-items-center"><img src="{{ asset('assets/img/icons/logo.png') }}" alt="phoenix" width="27">
                  <p class="logo-text ms-2 d-none d-sm-block">SchoolERP</p>
                </div>
              </div>
            </a>
          </div>
          
          <ul class="navbar-nav navbar-nav-icons flex-row">
            <li class="nav-item">
              <a class="nav-link px-2" href="#!" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span data-feather="bell" style="height:20px;width:20px;"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-end py-0 shadow border border-300" style="min-width: 20rem;">
                <div class="card position-relative border-0">
                  <div class="card-header border-bottom border-300 bg-light py-2">
                    <h6 class="mb-0">Notifications</h6>
                  </div>
                  <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                      <div class="list-group-item px-3 py-2 border-0">
                        <p class="mb-0 fs--1">No new notifications.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-l ">
                        <img class="rounded-circle " src="{{ asset('assets/img/team/72x72/57.webp') }}" alt="">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0 shadow border border-300" aria-labelledby="navbarDropdownUser" style="min-width: 15rem;">
                    <div class="card position-relative border-0">
                        <div class="card-body p-0">
                            <div class="text-center pt-4 pb-3">
                                <div class="avatar avatar-xl ">
                                    <img class="rounded-circle " src="{{ asset('assets/img/team/72x72/57.webp') }}" alt="">
                                </div>
                                <h6 class="mt-2 text-black">{{ auth()->user()->name ?? 'User' }}</h6>
                            </div>
                        </div>
                        <div class="card-footer p-0 border-top">
                            <div class="px-3">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-phoenix-secondary d-flex flex-center w-100 my-3">
                                        <span class="me-2" data-feather="log-out"> </span>Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="content">
        @yield('content')
        
        <footer class="footer position-absolute">
          <div class="row g-0 justify-content-between align-items-center h-100">
            <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 mt-2 mt-sm-0 text-900">Developed by school erp<span class="d-none d-sm-inline-block"></span><span class="d-none d-sm-inline-block mx-1">|</span><br class="d-sm-none">2026 &copy; <a class="mx-1" href="#">SchoolERP</a></p>
            </div>
            <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 text-600">v1.0.0</p>
            </div>
          </div>
        </footer>
      </div>
    </main>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        @if(session('success'))
            <div class="toast show align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">{{ session('success') }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error') || $errors->any())
            <div class="toast show align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">{{ session('error') ?? 'Please fix the errors below.' }}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('vendors/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendors/anchorjs/anchor.min.js') }}"></script>
    <script src="{{ asset('vendors/is/is.min.js') }}"></script>
    <script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('vendors/lodash/lodash.min.js') }}"></script>
    <script src="{{ asset('vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('vendors/dayjs/dayjs.min.js') }}"></script>
    <script src="{{ asset('assets/js/phoenix.js') }}"></script>
    
    <!-- DataTables & Select2 JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('.datatable').DataTable({
                "pageLength": 10,
                "order": [],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search..."
                }
            });

            // Initialize Select2
            function initSelect2() {
                $('.select2').each(function() {
                    $(this).select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: $(this).closest('.modal').length ? $(this).closest('.modal') : null
                    });
                });
            }
            initSelect2();

            // Re-initialize for dynamic content
            $(document).on('shown.bs.modal', function() {
                initSelect2();
            });

            // Sidebar Toggle Fix
            $('.navbar-toggler-humburger-icon').on('click', function(e) {
                e.preventDefault();
                $('body').toggleClass('navbar-vertical-collapsed');
                // Persist state in localStorage if possible
                const isCollapsed = $('body').hasClass('navbar-vertical-collapsed');
                localStorage.setItem('phoenixNavbarVerticalCollapsed', isCollapsed);
            });

            // Initial state from localStorage
            if (localStorage.getItem('phoenixNavbarVerticalCollapsed') === 'true') {
                $('body').addClass('navbar-vertical-collapsed');
            }

            // Feather Icons
            feather.replace();

            // Auto-hide toasts
            setTimeout(function() {
                $('.toast').fadeOut();
            }, 5000);
        });
    </script>
    @stack('scripts')
  </body>
</html>
