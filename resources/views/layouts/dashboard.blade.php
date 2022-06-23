@php($company = company())

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <title>{{ $company->name }}</title>
  <meta name="description" content="" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
  <!--end::Fonts-->
  <!--end::Page Vendors Styles-->
  <!--begin::Global Theme Styles(used by all pages)-->
  <link href="{{ asset('assets/dashboard/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/dashboard/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/dashboard/css/app.css') }}" rel="stylesheet" type="text/css" />
  <!--end::Global Theme Styles-->
  <!--begin::Layout Themes(used by all pages)-->
  <link href="{{ asset('assets/dashboard/css/themes/layout/header/base/light.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/dashboard/css/themes/layout/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/dashboard/css/themes/layout/brand/light.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/dashboard/css/themes/layout/aside/light.css') }}" rel="stylesheet" type="text/css" />

  <style>
    .select2-container .select2-search--inline .select2-search__field {
      margin-bottom: 5px !important;
    }

    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
      border-color: #E4E6EF !important;
    }

    .aside-minimize .logo-alt {
      display: none !important;
    }

    .aside-minimize.aside-minimize-hover .logo-alt {
      display: flex !important;
    }
    .model.show{
      opacity: 0 !important;
    }

  </style>

  <!--end::Layout Themes-->
  @stack('style')
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
  <!--begin::Main-->
  <!--begin::Header Mobile-->
  @include('layouts.includes.dashboard.header_mobile')
  <!--end::Header Mobile-->
  <div class="d-flex flex-column flex-root">
    <!--begin::Page-->
    <div class="d-flex flex-row flex-column-fluid page">
      <!--begin::Aside-->
      @include('layouts.includes.dashboard.aside')
      <!--end::Aside-->
      <!--begin::Wrapper-->
      <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
        <!--begin::Header-->
        @include('layouts.includes.dashboard.header')
        <!--end::Header-->
        <!--begin::Content-->
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
          <!--begin::Subheader-->
          <!--end::Subheader-->
          <!--begin::Entry-->
          <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
              <!--begin::Dashboard-->
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Launch demo modal
              </button>

              <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      ...
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
              @yield('content')
              <!--end::Dashboard-->
            </div>
            <!--end::Container-->
          </div>
          <!--end::Entry-->
        </div>
        <!--end::Content-->
        <!--begin::Footer-->
        @include('layouts.includes.dashboard.footer')
        <!--end::Footer-->
      </div>
      <!--end::Wrapper-->
    </div>
    <!--end::Page-->
  </div>
  <!--end::Main-->
  @include('layouts.includes.dashboard.externals')
  <script>
    var KTAppSettings = {
      "breakpoints": {
        "sm": 576,
        "md": 768,
        "lg": 992,
        "xl": 1200,
        "xxl": 1400
      },
      "colors": {
        "theme": {
          "base": {
            "white": "#ffffff",
            "primary": "#3699FF",
            "secondary": "#E5EAEE",
            "success": "#1BC5BD",
            "info": "#8950FC",
            "warning": "#FFA800",
            "danger": "#F64E60",
            "light": "#E4E6EF",
            "dark": "#181C32"
          },
          "light": {
            "white": "#ffffff",
            "primary": "#E1F0FF",
            "secondary": "#EBEDF3",
            "success": "#C9F7F5",
            "info": "#EEE5FF",
            "warning": "#FFF4DE",
            "danger": "#FFE2E5",
            "light": "#F3F6F9",
            "dark": "#D6D6E0"
          },
          "inverse": {
            "white": "#ffffff",
            "primary": "#ffffff",
            "secondary": "#3F4254",
            "success": "#ffffff",
            "info": "#ffffff",
            "warning": "#ffffff",
            "danger": "#ffffff",
            "light": "#464E5F",
            "dark": "#ffffff"
          }
        },
        "gray": {
          "gray-100": "#F3F6F9",
          "gray-200": "#EBEDF3",
          "gray-300": "#E4E6EF",
          "gray-400": "#D1D3E0",
          "gray-500": "#B5B5C3",
          "gray-600": "#7E8299",
          "gray-700": "#5E6278",
          "gray-800": "#3F4254",
          "gray-900": "#181C32"
        }
      },
      "font-family": "Poppins"
    };
  </script>
  <!--end::Global Config-->
  <!--begin::Global Theme Bundle(used by all pages)-->
  <script src="{{ asset('assets/dashboard/plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ asset('assets/dashboard/js/scripts.bundle.js') }}"></script>
  <script>
    $('[data-tooltip="tooltip"]').tooltip();
    $('.alert').delay(4000).fadeOut();

    $('.select2').select2({
      placeholder: 'Select option',
    });
    $('.select2-withTag').select2({
      placeholder: 'Select option',
      tags: "true",
    });
    $('.select2_multiple').select2({
      placeholder: 'Select option',
      closeOnSelect: false
    });
  </script>
  <!--end::Global Theme Bundle-->
  @stack('script')
</body>
<!--end::Body-->

</html>
