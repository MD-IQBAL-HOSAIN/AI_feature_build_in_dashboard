@extends('backend.master')

@push('styles-top')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Admin User</a></li>
                        <li class="breadcrumb-item active">Create Admin User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form method="post"
        action="{{ @$system_user ? route('system-user.update', @$system_user->id) : route('system-user.store') }}"
        class="row" enctype="multipart/form-data">
        @csrf
        @if (@$system_user)
            @method('PATCH')
        @endif
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div>
                                <label class="form-label" for="project-title-input">Name</label>
                                <input type="text" name="name" value="{{ old('name', @$system_user->name) }}"
                                    class="form-control @error('name') is-invalid @enderror" placeholder="Enter user name">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label class="form-label" for="username">Username</label>
                                <input type="text" id="username" name="username"
                                    value="{{ old('username', @$system_user->username) }}"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Enter username ( will be unique)">
                                @error('username')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <input type="text" name="is_admin_user" hidden value="1">

                    <div class="row g-3 mt-1">
                        <div class="col-lg-6">
                            <div>
                                <label class="form-label" for="role">Role</label>
                                <select name="role" id="role"
                                    class="form-select @error('role') is-invalid @enderror">
                                    <option value="">Select role</option>
                                    @foreach ($roles ?? collect() as $role)
                                        <option value="{{ $role }}"
                                            {{ old('role', @$system_user->role) === $role ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" {{ @$system_user ? 'disabled' : '' }}
                                    value="{{ old('email', @$system_user->email) }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter email your valid email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-lg-12">
                            <div>
                                <label for="password" class="form-label">Password</label>
                                <div class="position-relative">
                                    <input type="password" id="password" name="password"
                                        class="form-control pe-5 @error('password') is-invalid @enderror"
                                        placeholder="Enter password (minimum 8 characters)">
                                    <button type="button" id="togglePassword"
                                        class="btn btn-sm btn-link position-absolute top-50 end-0 translate-middle-y text-decoration-none text-muted">
                                        <i class="ri-eye-line" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-12">
                            <div>
                                <label for="avatar" class="form-label">Profile Image</label>
                                <input type="file" id="avatar" name="avatar" accept="image/*"
                                    class="dropify @error('avatar') is-invalid @enderror" data-height="160"
                                    @if (!empty(@$system_user->avatar)) data-default-file="{{ asset($system_user->avatar) }}" @endif>
                                @error('avatar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->


            <!-- end card -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('system-user.index') }}" class="btn btn-danger w-sm">Cancel</a>
                {{-- <button type="submit" class="btn btn-secondary w-sm">Draft</button> --}}
                <button type="submit" class="btn btn-success w-sm">{{ @$system_user ? 'Update' : 'Create' }}</button>
            </div>
        </div>
        <!-- end col -->
    </form>
    <!-- end row -->
@endsection

@push('scripts-bottom')
    <script>
        $(document).ready(function() {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput && passwordIcon) {
                toggleBtn.addEventListener('click', function() {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    passwordIcon.classList.toggle('ri-eye-line', !isPassword);
                    passwordIcon.classList.toggle('ri-eye-off-line', isPassword);
                });
            }
        });
    </script>
@endpush
