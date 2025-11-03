@extends('layouts.admin')

@section('content')
<style>
    body {
        background-color: #f8f9fa;
        color: #1a1a1a;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        font-size: 14px;
    }

    .content-wrapper {
        padding: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: #1a1a1a;
    }

    .breadcrumb {
        background: none;
        padding: 0;
        margin: 0;
        font-size: 14px;
    }

    .breadcrumb-item {
        color: #6b7280;
    }

    .breadcrumb-item a {
        color: #0084ff;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #4a4a4a;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        color: #9ca3af;
        padding: 0 8px;
    }

    .alert-success {
        background-color: #f0f9f4;
        border: 1px solid #d1f2e0;
        color: #16a34a;
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 20px;
        color: #16a34a;
        opacity: 0.7;
        cursor: pointer;
        margin-left: auto;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 24px;
    }

    .card-header {
        background-color: #f8f9fa;
        padding: 16px 24px;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 600;
        font-size: 16px;
        color: #1a1a1a;
        border-radius: 8px 8px 0 0;
    }

    .card-body {
        padding: 24px;
    }

    .profile-image {
        width: 160px;
        height: 160px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e0e0e0;
        margin-bottom: 16px;
    }

    .profile-name {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #1a1a1a;
    }

    .profile-email {
        color: #6b7280;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
    }

    .badge-success {
        background-color: #f0f9f4;
        color: #16a34a;
    }

    .bio-box {
        background-color: #fafafa;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 12px 16px;
        margin-top: 16px;
        color: #4a4a4a;
        font-size: 14px;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: #4a4a4a;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 14px;
        transition: all 0.2s;
        width: 100%;
    }

    .form-control:focus {
        border-color: #0084ff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,132,255,0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 13px;
        margin-top: 6px;
        display: block;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .avatar-preview-container {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background-color: #fafafa;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
    }

    .avatar-preview-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e0e0e0;
        flex-shrink: 0;
    }

    .form-text {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 6px;
        display: block;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background-color: #0084ff;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0070e0;
    }

    .btn-warning {
        background-color: #d97706;
        color: #fff;
    }

    .btn-warning:hover {
        background-color: #b45309;
    }

    .text-center {
        text-align: center;
    }

    .text-end {
        text-align: right;
    }

    .mb-2 { margin-bottom: 8px; }
    .mb-3 { margin-bottom: 16px; }
    .mb-4 { margin-bottom: 24px; }
    .mt-1 { margin-top: 4px; }

    .row {
        display: flex;
        margin: 0 -12px;
        flex-wrap: wrap;
    }

    .col-xl-4, .col-xl-8, .col-lg-4, .col-lg-8, .col-md-6, .col-md-12 {
        padding: 0 12px;
        width: 100%;
    }

    @media (min-width: 768px) {
        .col-md-6 { width: 50%; }
    }

    @media (min-width: 992px) {
        .col-lg-4 { width: 33.333%; }
        .col-lg-8 { width: 66.667%; }
    }

    @media (min-width: 1200px) {
        .col-xl-4 { width: 33.333%; }
        .col-xl-8 { width: 66.667%; }
    }

    .h-100 {
        height: 100%;
    }
</style>

<div class="content-wrapper">
    <div class="page-header">
        <h1 class="page-title">Admin Profile</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Edit Profile</li>
        </ol>
    </div>

    @if (session('status'))
        <div class="alert alert-success" id="successAlert">
            <i class="fas fa-check-circle"></i>
            <div><strong>Success!</strong> {{ session('status') }}</div>
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-user" style="margin-right: 8px;"></i>Profile Overview
                </div>
                <div class="card-body text-center">
                    @if ($admin->avatar)
                        <img src="{{ $admin->avatar_url }}" 
                             alt="Avatar" 
                             onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                             class="profile-image">
                    @else
                        <img src="{{ asset('img/default-dp.jpg') }}" 
                             alt="Default Avatar" 
                             class="profile-image">
                    @endif
                    
                    <h4 class="profile-name">{{ $admin->name }}</h4>
                    <p class="profile-email">
                        <i class="fas fa-envelope" style="margin-right: 6px;"></i>{{ $admin->email }}
                    </p>
                    <p class="mb-3">
                        <span class="badge badge-success">
                            <i class="fas fa-user-shield"></i>Administrator
                        </span>
                    </p>
                    
                    @if($admin->bio)
                        <div class="bio-box">
                            <i class="fas fa-info-circle" style="margin-right: 8px; color: #0084ff;"></i>{{ $admin->bio }}
                        </div>
                    @else
                        <div class="bio-box">
                            <i class="fas fa-info-circle" style="margin-right: 8px;"></i>No bio added yet
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8 col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-edit" style="margin-right: 8px;"></i>Edit Profile Information
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user" style="margin-right: 6px; color: #0084ff;"></i>Name
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
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope" style="margin-right: 6px; color: #0084ff;"></i>Email
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
                            <label for="bio" class="form-label">
                                <i class="fas fa-info-circle" style="margin-right: 6px; color: #0084ff;"></i>Bio
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
                            <label for="avatar" class="form-label">
                                <i class="fas fa-image" style="margin-right: 6px; color: #0084ff;"></i>Profile Picture
                            </label>
                            
                            <div class="avatar-preview-container">
                                @if ($admin->avatar)
                                    <img id="avatar-preview" 
                                         src="{{ $admin->avatar_url }}" 
                                         alt="Avatar Preview" 
                                         onerror="this.src='{{ asset('img/default-dp.jpg') }}'"
                                         class="avatar-preview-img">
                                @else
                                    <img id="avatar-preview" 
                                         src="{{ asset('img/default-dp.jpg') }}" 
                                         alt="Avatar Preview" 
                                         class="avatar-preview-img">
                                @endif
                                <div style="flex: 1;">
                                    <input type="file" 
                                           class="form-control @error('avatar') is-invalid @enderror" 
                                           id="avatar" 
                                           name="avatar" 
                                           accept="image/*"
                                           onchange="previewAvatar(event)">
                                    <small class="form-text">
                                        <i class="fas fa-check-circle" style="margin-right: 4px;"></i>Accepted: JPG, JPEG, PNG, GIF (Max: 2MB)
                                    </small>
                                </div>
                            </div>
                            @error('avatar')
                                <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-lock" style="margin-right: 8px;"></i>Update Password
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-key" style="margin-right: 6px; color: #d97706;"></i>Current Password
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
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock" style="margin-right: 6px; color: #d97706;"></i>New Password
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
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock" style="margin-right: 6px; color: #d97706;"></i>Confirm Password
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
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
