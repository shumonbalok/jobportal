@extends('layouts.dashboard')
@section('content')
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">

            @if (request()->routeIs('role.index'))
                @can('role_permission.create')
                <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">
                                Add New Role
                            </h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="{{ route('role.assign') }}" class="btn btn-light-primary font-weight-bolder mr-2">
                                <i class="ki ki-long-arrow-back icon-sm"></i>
                                Back
                            </a>
                            <div class="btn-group">
                                <button type="submit" form="kt_form" class="btn btn-primary font-weight-bolder">
                                    <i class="ki ki-check icon-sm"></i>
                                    Save Form
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Form-->
                        <form class="form" id="kt_form" method="post" action="{{ route('role.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="my-5">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label" for="name">Role Name <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <input name="name" id="name" value="{{ old('name') }}" class="form-control form-control-solid @error('name') is-invalid @enderror" type="text">
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label" for="category_id">Select Permission <span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select class="form-control select2_multiple" id="category_id" name="permissions[]" multiple>
                                                    @foreach($permissions as $permission)
                                                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2"></div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
                @endcan
            @elseif(request()->routeIs('role.edit'))
                @can('role_permission.edit')
                <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">
                                Edit Role
                            </h3>
                        </div>
                        <div class="card-toolbar">

                            <div class="btn-group">
                                <button type="submit" form="kt_form" class="btn btn-primary font-weight-bolder">
                                    <i class="ki ki-check icon-sm"></i>
                                    Update Form
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Form-->
                        <form class="form" id="kt_form" action="{{ route('role.update',$role->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="my-5">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label" for="name">Role Name <span class="text-danger">*</span></label>
                                            <div class="col-md-9">
                                                <input id="name" name="name" value="{{ $role->name }}" class="form-control form-control-solid @error('name') is-invalid @enderror" type="text">
                                                @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label" for="category_id">Select Permission<span class="text-danger">*</span></label>
                                            <div class="col-lg-9">
                                                <select class="form-control select2_multiple" id="category_id" name="permissions[]" multiple>
                                                    @foreach ($permissions as $permission)
                                                        <option value="{{ $permission->id }}" {{ $role->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                                                            {{ $permission->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2"></div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
                @endcan
            @endif

            <div class="card card-custom mt-5">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Role List</h3>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-separate table-head-custom table-checkable" id="kt_datatable">
                        <thead>
                        <tr>
                            <th>Role</th>
                            <th>Permissions</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    <div>
                                        @foreach ($role->getPermissionNames() as $item)
                                            <span class="badge badge-secondary mb-1">{{ $item }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td nowrap="nowrap">
                                    @can('role_permission.edit')
                                        <a href="{{ route('role.edit', $role->id) }}" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3">
                                          <span class="svg-icon svg-icon-md svg-icon-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                              <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path
                                                    d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                                                    fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)"></path>
                                                <path
                                                    d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                              </g>
                                            </svg>
                                          </span>
                                        </a>
                                    @endcan

                                    @can('role_permission.delete')
                                        <form method="post" action="{{ route('role.destroy', $role->id) }}" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-icon btn-light btn-hover-danger btn-sm">
                                              <span class="svg-icon svg-icon-md svg-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"></path>
                                                    <path
                                                        d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                        fill="#000000" opacity="0.3"></path>
                                                  </g>
                                                </svg>
                                              </span>
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
