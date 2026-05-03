@extends('backend.master')

@section('title', 'System Settings')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">System Settings</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                        <li class="breadcrumb-item active">System Settings</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-3 pb-1">
                    <div class="col-12">
                        <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                            <div class="mt-3 mt-lg-0">
                                <form action="{{ route('settings.system.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-3 mb-0 align-items-start">

                                        <!-- Submit -->
                                        <div class="col-12 mt-3 text-end">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="ri-save-line align-middle me-1"></i> Save Settings
                                            </button>
                                        </div>
                                        <!-- Logo -->
                                        <div class="col-md-4">
                                            <label class="form-label">Logo</label>
                                            <input type="file" name="logo"
                                                class="dropify @error('logo') is-invalid @enderror" data-height="100"
                                                @if(!empty($settings->logo))
                                                data-default-file="{{ asset($settings->logo) }}" @endif>
                                            @error('logo')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Mini Logo -->
                                        <div class="col-md-4">
                                            <label class="form-label">Mini Logo</label>
                                            <input type="file" name="mini_logo"
                                                class="dropify @error('mini_logo') is-invalid @enderror" data-height="100"
                                                @if(!empty($settings->mini_logo))
                                                data-default-file="{{ asset($settings->mini_logo) }}" @endif>
                                            @error('mini_logo')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Icon -->
                                        <div class="col-md-4">
                                            <label class="form-label">Favicon / Icon</label>
                                            <input type="file" name="icon"
                                                class="dropify @error('icon') is-invalid @enderror" data-height="100"
                                                @if(!empty($settings->icon))
                                                data-default-file="{{ asset($settings->icon) }}" @endif>
                                            @error('icon')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Title -->
                                        <div class="col-md-6">
                                            <label class="form-label">Website Title</label>
                                            <input type="text" name="site_title"
                                                class="form-control @error('site_title') is-invalid @enderror"
                                                placeholder="Enter Website Title"
                                                value="{{ old('site_title', $settings->site_title ?? '') }}">
                                            @error('site_title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- App Name -->
                                        <div class="col-md-6">
                                            <label class="form-label">App Name</label>
                                            <input type="text" name="app_name"
                                                class="form-control @error('app_name') is-invalid @enderror"
                                                placeholder="Enter App Name"
                                                value="{{ old('app_name', $settings->app_name ?? '') }}">
                                            @error('app_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Admin Dashboard Name -->
                                        <div class="col-md-12">
                                            <label class="form-label">Admin Dashboard Name</label>
                                            <input type="text" name="admin_name"
                                                class="form-control @error('admin_name') is-invalid @enderror"
                                                placeholder="Enter Dashboard Name"
                                                value="{{ old('admin_name', $settings->admin_name ?? '') }}">
                                            @error('admin_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Footer Settings -->
                                        <div class="col-md-12">
                                            <h5 class="mt-4">Footer Settings</h5>
                                        </div>

                                        <!-- Copyright -->
                                        <div class="col-md-4">
                                            <label class="form-label">Copyright Text</label>
                                            <input type="text" name="copyright"
                                                class="form-control @error('copyright') is-invalid @enderror"
                                                placeholder="© 2025 MyCompany. All rights reserved."
                                                value="{{ old('copyright', $settings->copyright ?? '') }}">
                                            @error('copyright')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Contact -->
                                        <div class="col-md-4">
                                            <label class="form-label">Contact Number</label>
                                            <input type="text" name="contact"
                                                class="form-control @error('contact') is-invalid @enderror"
                                                placeholder="+1234547890"
                                                value="{{ old('contact', $settings->contact ?? '') }}">
                                            @error('contact')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-4">
                                            <label class="form-label">Contact Email</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="contact@example.com"
                                                value="{{ old('email', $settings->email ?? '') }}">
                                            @error('email')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- About -->
                                        <div class="col-md-12">
                                            <label class="form-label">About Section</label>
                                            <textarea id="about-editor" name="about" rows="5" class="form-control ck-editor"
                                                placeholder="Write about your company or website...">{{ old('about', $settings->about ?? '') }}</textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div><!-- end card header -->
                    </div>
                    <!--end col-->
                </div>


            </div>
            <!-- end .h-100-->

        </div>
        <!-- end col -->

    </div>

@endsection


@push('style-bottom')
    <style>
        .dropify-wrapper .dropify-message p {
            line-height: 2;
            /* increase spacing */
            font-size: 16px;
            /* adjust font size if needed */
            color: #555;
            /* custom text color */
        }
    </style>
@endpush
@push('scripts-top')
@endpush


