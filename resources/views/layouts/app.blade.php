<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-template="vertical-menu-template-free"
>
  @include('dashboard.shared.head')
  <body>
    <div style="display:none;" id="loadingOverlay">
        <div class="loading-overlay d-flex justify-content-center">
            <div class="spinner-border spinner-border-lg text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
    
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        @include('dashboard.shared.sidebar')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                  <!-- Place this tag where you want the button to render. -->
                  <!-- User -->
                  @auth('admin')
                  <span class="fw-semibold d-block">{{admin()->nama }}</span>
                  <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                        <img src="{{asset('img/user1.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                      </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="#">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar avatar-online">
                                <img src="{{asset('img/user1.png')}}" alt class="w-px-40 h-auto rounded-circle" />
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <span class="fw-semibold d-block">{{admin()->nama}}</span>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                          <i class="bx bx-power-off me-2"></i>
                          <span class="align-middle">Log Out</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <!--/ User -->
                  @endauth
                </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasLabel" class="offcanvas-title"></h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body mx-0 flex-grow-0">
                        <div id="offcanvasBody">
                        </div>
                        <button type="button" class="btn btn-outline-secondary d-grid w-100" data-bs-dismiss="offcanvas">
                            Batal
                        </button>
                    </div>
                </div>
              @yield('content')
            <!-- / Content -->
            </div>
            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  {{config('app.footer')}}
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    @include('dashboard.shared.script')
    
    @stack('scripts')

    <script>
      $('body').on('click', '#generatePassword', (e)=>{
        const pwd = randomString()
        $('#password').val(pwd)
      })

      $('.datepicker').daterangepicker({
          singleDatePicker: true,
          autoClose:true,
          showDropdowns: true,
          autoApply: true,
          autoUpdateInput: false,
          locale: {
              format: 'DD-MM-YYYY'
          },
          minYear: parseInt(moment().format('YYYY')) - 10,
          maxYear: parseInt(moment().format('YYYY')) + 10,
      }).on("apply.daterangepicker", function (e, picker) {
          picker.element.val(picker.startDate.format(picker.locale.format));
      });

      $('.daterange').daterangepicker({
          singleDatePicker: false,
          autoClose:true,
          showDropdowns: true,
          autoApply: true,
          autoUpdateInput: false,
          locale: {
              format: 'DD-MM-YYYY',
              separator: 'sd'
          },
          minYear: parseInt(moment().format('YYYY')) - 10,
          maxYear: parseInt(moment().format('YYYY')) + 10,
      }).on("apply.daterangepicker", function (e, picker) {
          const start = picker.startDate.format(picker.locale.format)
          const end = picker.endDate.format(picker.locale.format)
          const separator = picker.locale.separator
        
          picker.element.val(`${start} ${separator} ${end}`);
      });
    </script>
  </body>
</html>
