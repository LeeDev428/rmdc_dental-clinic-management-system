@extends('layouts.admin')

@section('content')
<h1 class="mt-4">Admin Profile</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Edit Profile</li>
</ol>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><i class="fas fa-check-circle me-2"></i>Success!</strong> {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-12 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-user me-2"></i>Profile Overview
            </div>
            <div class="card-body text-center py-4">
                @if ($admin->avatar)
                    <img src="{{ $admin->avatar_url }}" 
                         alt="Avatar" 
                         onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                         class="rounded-circle mb-3 border border-3 border-dark" 
                         style="width: 160px; height: 160px; object-fit: cover;">
                @else
                    <img src="{{ asset('img/default-dp.jpg') }}" 
                         alt="Default Avatar" 
                         class="rounded-circle mb-3 border border-3 border-dark" 
                         style="width: 160px; height: 160px; object-fit: cover;">
                @endif
                
                <h4 class="mb-2 fw-bold">{{ $admin->name }}</h4>
                <p class="text-muted mb-2">
                    <i class="fas fa-envelope me-2"></i>{{ $admin->email }}
                </p>
                <p class="mb-3">
                    <span class="badge bg-success px-3 py-2"><i class="fas fa-user-shield me-1"></i>Administrator</span>
                </p>
                
                @if($admin->bio)
                    <div class="alert alert-light border mb-0 text-start" role="alert">
                        <i class="fas fa-info-circle me-2 text-primary"></i>{{ $admin->bio }}
                    </div>
                @else
                    <div class="alert alert-light border mb-0" role="alert">
                        <i class="fas fa-info-circle me-2"></i>No bio added yet
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-8 col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <i class="fas fa-user-edit me-2"></i>Edit Profile Information
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">
                                <i class="fas fa-user me-2 text-primary"></i>Name
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $admin->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope me-2 text-primary"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $admin->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label fw-bold">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Bio
                        </label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" 
                                  name="bio" 
                                  rows="3"
                                  placeholder="Tell us about yourself...">{{ old('bio', $admin->bio) }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="avatar" class="form-label fw-bold">
                            <i class="fas fa-image me-2 text-primary"></i>Profile Picture
                        </label>
                        
                        <div class="d-flex align-items-center p-3 bg-light border rounded">
                            @if ($admin->avatar)
                                <img id="avatar-preview" 
                                     src="{{ $admin->avatar_url }}" 
                                     alt="Avatar Preview" 
                                     onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                                     class="rounded-circle border border-2 border-secondary me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <img id="avatar-preview" 
                                     src="{{ asset('img/default-dp.jpg') }}" 
                                     alt="Avatar Preview" 
                                     class="rounded-circle border border-2 border-secondary me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" 
                                       class="form-control @error('avatar') is-invalid @enderror" 
                                       id="avatar" 
                                       name="avatar" 
                                       accept="image/*"
                                       onchange="previewAvatar(event)">
                                <small class="form-text text-muted d-block mt-1">
                                    <i class="fas fa-check-circle me-1"></i>Accepted: JPG, JPEG, PNG, GIF (Max: 2MB)
                                </small>
                            </div>
                        </div>
                        @error('avatar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-lock me-2"></i>Update Password
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-bold">
                            <i class="fas fa-key me-2 text-warning"></i>Current Password
                        </label>
                        <input type="password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" 
                               name="current_password" 
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold">
                                <i class="fas fa-lock me-2 text-warning"></i>New Password
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-bold">
                                <i class="fas fa-lock me-2 text-warning"></i>Confirm Password
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-warning px-4">
                            <i class="fas fa-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewAvatar(event) {
        const preview = document.getElementById('avatar-preview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
