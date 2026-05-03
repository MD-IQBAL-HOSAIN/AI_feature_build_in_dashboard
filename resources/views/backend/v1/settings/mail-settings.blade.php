@extends('backend.master')

@section('title', 'Mail Settings')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Mail Settings</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                        <li class="breadcrumb-item active">Mail Settings</li>
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
                        <div class="d-flex justify-content-center mt-3 mt-lg-0">
                            <div class="col-lg-8"> <!-- you can change 8 to 6 or 10 depending on desired width -->
                                <form method="post" action="{{ route('settings.mail.update') }}"
                                    enctype="multipart/form-data" class="card p-4 shadow-sm">
                                    @csrf
                                    @method('PATCH')

                                    <div class="row mb-4">
                                        <label for="mail_mailer" class="col-md-3 form-label">MAIL MAILER</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('mail_mailer') is-invalid @enderror"
                                                id="mail_mailer" name="mail_mailer" placeholder="Enter your mail mailer"
                                                type="text" value="{{ env('MAIL_MAILER') }}">
                                            @error('mail_mailer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="mail_host" class="col-md-3 form-label">MAIL HOST</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('mail_host') is-invalid @enderror"
                                                id="mail_host" name="mail_host" placeholder="Enter your host" type="text"
                                                value="{{ env('MAIL_HOST') }}">
                                            @error('mail_host')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="mail_port" class="col-md-3 form-label">MAIL PORT</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('mail_port') is-invalid @enderror"
                                                id="mail_port" name="mail_port" placeholder="Enter your mail port"
                                                type="text" value="{{ env('MAIL_PORT') }}">
                                            @error('mail_port')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="mail_username" class="col-md-3 form-label">MAIL USERNAME</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('mail_username') is-invalid @enderror"
                                                id="mail_username" name="mail_username"
                                                placeholder="Enter your mail username" type="text"
                                                value="{{ env('MAIL_USERNAME') }}">
                                            @error('mail_username')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="mail_password" class="col-md-3 form-label">MAIL PASSWORD</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('mail_password') is-invalid @enderror"
                                                id="mail_password" name="mail_password"
                                                placeholder="Enter your mail password" type="text"
                                                value="{{ env('MAIL_PASSWORD') }}">
                                            @error('mail_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="mail_encryption" class="col-md-3 form-label">MAIL ENCRYPTION</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('mail_encryption') is-invalid @enderror"
                                                id="mail_encryption" name="mail_encryption"
                                                placeholder="Enter your mail encryption" type="text"
                                                value="{{ env('MAIL_ENCRYPTION') }}">
                                            @error('mail_encryption')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="mail_from_address" class="col-md-3 form-label">MAIL FROM ADDRESS</label>
                                        <div class="col-md-9">
                                            <input class="form-control @error('mail_from_address') is-invalid @enderror"
                                                id="mail_from_address" name="mail_from_address"
                                                placeholder="Enter mail from address" type="text"
                                                value="{{ env('MAIL_FROM_ADDRESS') }}">
                                            @error('mail_from_address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row justify-content-end">
                                        <div class="col-sm-9">
                                            <button class="btn btn-primary" type="submit">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!--end col-->
                </div>


            </div>
            <!-- end .h-100-->

        </div>
        <!-- end col -->

    </div>

@endsection


