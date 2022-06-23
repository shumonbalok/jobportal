@extends('layouts.dashboard')

@section('content')

  <div class="d-flex flex-row">
    <!--begin::Aside-->
    <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
      <div class="card card-custom card-stretch">
        <div class="card-body pt-8">
          <div class="d-flex align-items-center mb-8">
            <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
              <div class="symbol-label" style="background-image:url('{{ setImage(auth()->user()->profile_photo_path) }}')"></div>
              <i class="symbol-badge bg-success"></i>
            </div>
            <div>
              <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{ auth()->user()->name }}</a>
              <div class="text-muted">@if (auth()->user()->hasRole('Buyer')) Buyer @elseif(auth()->user()->hasRole('Reseller')) Reseller @endif</div>
            </div>
          </div>
          <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
            <div class="navi-item mb-2">
              <a href="{{ route('user.edit', auth()->id()) }}" class="navi-link py-4">
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"></polygon>
                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero"
                              opacity="0.3"></path>
                        <path
                          d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                          fill="#000000" fill-rule="nonzero"></path>
                      </g>
                    </svg>
                  </span>
                </span>
                <span class="navi-text font-size-lg">Personal Information</span>
              </a>
            </div>
            <div class="navi-item mb-2">
              <a href="{{ route('change_password') }}" class="navi-link py-4 active">
                <span class="navi-icon mr-2">
                  <span class="svg-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path
                          d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z"
                          fill="#000000" opacity="0.3"></path>
                        <path d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z" fill="#000000"
                              opacity="0.3"></path>
                        <path
                          d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z"
                          fill="#000000" opacity="0.3"></path>
                      </g>
                    </svg>
                  </span>
                </span>
                <span class="navi-text font-size-lg">Change Password</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end::Aside-->

    <div class="flex-row-fluid ml-lg-8">
      <div class="card card-custom card-stretch">
        <div class="card-header py-3">
          <div class="card-title align-items-start flex-column">
            <h3 class="card-label font-weight-bolder text-dark">Change Password</h3>
            <span class="text-muted font-weight-bold font-size-sm mt-1">Change your account password</span>
          </div>
          <div class="card-toolbar">
            <button type="submit" class="btn btn-success mr-2" form="profile_update_form">Save Changes</button>
            <button type="reset" class="btn btn-secondary" form="profile_update_form">Cancel</button>
          </div>
        </div>

        <div class="px-8 mt-2">
          @if ($errors->updatePassword->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->updatePassword->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>

        <form class="form" action="{{ route('user-password.update') }}" id="profile_update_form" method="post">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label text-alert" for="current_password">Current Password</label>
              <div class="col-lg-9 col-xl-6">
                <input type="password" class="form-control form-control-lg form-control-solid mb-2" value="" id="current_password" name="current_password" placeholder="Current password">
{{--                <a href="#" class="text-sm font-weight-bold">Forgot password ?</a>--}}
              </div>
            </div>
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label text-alert" for="password">New Password</label>
              <div class="col-lg-9 col-xl-6">
                <input type="password" class="form-control form-control-lg form-control-solid" value="" id="password" name="password" placeholder="New password">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-xl-3 col-lg-3 col-form-label text-alert" for="password_confirmation">Confirm Password</label>
              <div class="col-lg-9 col-xl-6">
                <input type="password" class="form-control form-control-lg form-control-solid" value="" id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('script')
  <script src="{{ asset('assets/dashboard/js/pages/crud/file-upload/image-input.js') }}"></script>
  <script>
    let avatar5 = new KTImageInput('user_image');
  </script>
@endpush
