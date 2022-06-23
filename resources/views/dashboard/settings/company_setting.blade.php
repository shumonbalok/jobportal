@extends('layouts.dashboard')
@section('content')
    <div class="card card-custom card-sticky" id="kt_page_sticky_card">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Company Setting
                </h3>
            </div>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="submit" form="kt_form" class="btn btn-primary font-weight-bolder submit">
                        <i class="ki ki-check icon-sm"></i>
                        Save Form
                    </button>
                </div>
            </div>
        </div>
        <!--begin::Portlet-->
        <div class="card-body">
            <form class="form" id="kt_form" enctype="multipart/form-data" method="POST" action="{{ route('company.update') }}">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h3>Default Settings</h3>
                        <hr>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="d-block">{{ __('Logo') }}</label>
                                    <div class="image-input image-input-empty image-input-outline" id="logo" style="background-image: url({{ asset('storage/' . $company_setting->logo) }})">
                                        <div class="image-input-wrapper"></div>
                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title=""
                                            data-original-title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="logo" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="profile_avatar_remove" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="d-block">{{ __('Footer Logo') }}</label>
                                    <div class="image-input image-input-empty image-input-outline" id="footerLogo" style="background-image: url({{ asset('storage/' . $company_setting->footer_logo) }})">
                                        <div class="image-input-wrapper"></div>
                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title=""
                                            data-original-title="Change avatar">
                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                            <input type="file" name="footer_logo" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="profile_avatar_remove" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input name="name" id="name" value="{{ old('name') ?? $company_setting->name }}" class="form-control form-control-solid @error('name') is-invalid @enderror" type="text">
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile1">{{ __('Mobile') }}</label>
                                    <input name="mobile1" id="mobile1" value="{{ old('mobile1') ?? $company_setting->mobile1 }}" placeholder="Ex: 01xxxxxxxxx" type="number"
                                        class="form-control form-control-solid @error('mobile1') is-invalid @enderror">
                                    @error('mobile1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mobile1">{{ __('Mobile') }}</label>
                                    <input name="mobile2" id="mobile2" value="{{ old('mobile2') ?? $company_setting->mobile2 }}" placeholder="Ex: 01xxxxxxxxx" type="number"
                                        class="form-control form-control-solid @error('mobile2') is-invalid @enderror">
                                    @error('mobile2')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">{{ __('Email') }} </label>
                            <input name="email" id="email" value="{{ old('email') ?? $company_setting->email }}" class="form-control form-control-solid @error('email') is-invalid @enderror" type="email">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="location">{{ __('Location') }}</label>
                            <input name="location" id="location" min="0" value="{{ old('location') ?? $company_setting->location }}"
                                class="form-control form-control-solid @error('location') is-invalid @enderror" type="text">
                            @error('location')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="about">{{ __('About') }} </label>
                                <textarea name="about" id="about" rows="4"
                                    class="form-control form-control-solid @error('about') is-invalid @enderror">{{ old('about') ?? $company_setting->about }}</textarea>
                                @error('about')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="about_footer">{{ __('About Footer') }} </label>
                                <textarea name="about_footer" id="about_footer" rows="4"
                                    class="form-control form-control-solid @error('about_footer') is-invalid @enderror">{{ old('about_footer') ?? $company_setting->about_footer }}</textarea>
                                @error('about_footer')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h3 class="mt-16">Social Links</h3>
                        <hr>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook">{{ __('Facebook Link') }} </label>
                                    <input name="facebook" id="facebook" min="0" value="{{ old('facebook') ?? $company_setting->facebook }}"
                                        class="form-control form-control-solid @error('facebook') is-invalid @enderror" type="text">
                                    @error('facebook')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter">{{ __('Twitter Link') }} </label>
                                    <input name="twitter" id="twitter" min="0" value="{{ old('twitter') ?? $company_setting->twitter }}"
                                        class="form-control form-control-solid @error('facebook') is-invalid @enderror" type="text">
                                    @error('facebook')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="instagram">{{ __('Instagram Link') }} </label>
                                <input name="instagram" id="instagram" min="0" value="{{ old('instagram') ?? $company_setting->instagram }}"
                                    class="form-control form-control-solid @error('instagram') is-invalid @enderror" type="text">
                                @error('instagram')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="whatsapp">{{ __('WhatsApp') }}</label>
                                <input name="whatsapp" id="whatsapp" min="0" value="{{ old('whatsapp') ?? $company_setting->whatsapp }}"
                                    class="form-control form-control-solid @error('whatsapp') is-invalid @enderror" type="text">
                                @error('whatsapp')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h3 class="mt-16">Meta Settings</h3>
                        <hr>

                        <div class="form-group">
                            <label for="meta_title">{{ __('Meta Title') }} </label>
                            <input name="meta_title" id="meta_title" value="{{ old('meta_title') ?? $company_setting->meta_title }}"
                                class="form-control form-control-solid @error('meta_title') is-invalid @enderror" type="text">
                            @error('meta_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">{{ __('Meta Keywords') }} </label>
                            <input name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') ?? $company_setting->meta_keywords }}"
                                class="form-control form-control-solid @error('meta_keywords') is-invalid @enderror" type="text">
                            @error('meta_keywords')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="meta_description">{{ __('Meta Description') }} </label>
                            <textarea name="meta_description" id="meta_description" class="form-control form-control-solid @error('meta_description') is-invalid @enderror"
                                type="text">{{ old('meta_description') ?? $company_setting->meta_description }}</textarea>
                            @error('meta_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/dashboard/js/pages/crud/file-upload/image-input.js') }}"></script>
    <script>
        let avatar5 = new KTImageInput('logo');
        let avatar6 = new KTImageInput('footerLogo');
    </script>
@endpush
