@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Left Panel: User List -->
        <div class="col-md-3">
            <h3 class="text-center">Users</h3>

            <!-- Search Bar -->
            <form method="GET" action="{{ route('admin.messages') }}" class="mb-2">
                <div class="form-group d-flex align-items-center">
                    <input type="text" name="search" class="form-control" placeholder="Search by ID or Name" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary ml-2">Enter</button>
                </div>
            </form>

            <div class="list-group">
                @foreach ($users as $user)
                    <a href="{{ route('admin.messages', ['user_id' => $user->id]) }}"
                       class="list-group-item list-group-item-action {{ $selectedUser && $selectedUser->id == $user->id ? 'active' : '' }}"
                       style="border-radius: 8px; display: flex; align-items: center; justify-content: space-between;
                              background-color: {{ $selectedUser && $selectedUser->id == $user->id ? '#ADD8E6' : '#FFFFFF' }};">
                        <!-- Profile Picture -->
                        <div style="display: flex; align-items: center;">
                            <img src="{{ $user->avatar ? Storage::url($user->avatar) : asset('img/defaultprofile.png') }}"
                                 alt="User Avatar"
                                 style="width: 26px; height: 26px; border-radius: 50%; margin-right: 12px;">
                            <div>
                                <strong style="color: black;">
                                    {{Str::limit($user->name, 8, '...') }}
                                </strong>
                                <p style="color: black; font-size: 11px; margin-bottom: 0;">
                                    User ID: {{Str::limit($user->id, 6, '...') }}
                                </p>
                            </div>
                        </div>
                        <!-- Message Preview and Timestamp -->
                        <div class="text-muted" style="font-size: 0.8rem; text-align: right;">
                            @if($user->messages->isNotEmpty())
                                <!-- Truncate message if longer than 8 characters -->
                                <span style="color: black; display: block;">
                                    ( {{ Str::limit($user->messages->first()->message, 7, '...') }} )
                                </span>
                                <span style="font-size: 11px; display: block; color: black;">
                                    • {{ $user->messages->first()->created_at->diffForHumans() }}
                                </span>
                            @else
                                <span style="color: black;">No messages yet</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Right Panel: Chat Box -->
        <div class="col-md-9">
            @if ($selectedUser)
                <h3 class="text-center mb-2">Messages with {{ $selectedUser->name }}</h3>

                <!-- Display Recent Messages for Admin -->
                <div class="chat-box border rounded p-3" style="height: 350px; overflow-y: scroll;">
                    <h4>Recent Messages</h4>
                    @php
                        $previousUserId = null;
                    @endphp
                    @foreach ($messages as $index => $message)
                        <div class="d-flex {{ $message->is_admin ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                            <div class="card" style="max-width: 60%; border-radius: 25px; padding: 5px; margin-bottom: 5px;
                                        {{ $message->is_admin ? 'background-color: #d1e7fd;' : 'background-color: #f8f9fa;' }}">

                                <div class="card-body" style="padding: 0 5px 5px 5px; display: flex; flex-direction: column; justify-content: flex-start; min-height: 40px; text-align: center;">
                                    <p style="color: black; font-size: 15px; white-space: pre-line; overflow-wrap: break-word; margin-bottom: 0; text-align: center;">
                                        {{ $message->message }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between" style="margin-top: 5px;">
                                        <div class="d-flex align-items-center">
                                            @if($message->is_admin)
                                                <img src="{{ $message->is_admin && auth()->user()->avatar ? Storage::url(auth()->user()->avatar) : asset('assets/img/defaultprofile.png') }}" alt="Admin Avatar" style="width: 28px; height: 28px; border-radius: 50%; margin-right: 8px;">
                                            @else
                                                <img src="{{ $selectedUser->avatar ? Storage::url($selectedUser->avatar) : asset('img/defaultprofile.png') }}" alt="User Avatar" style="width: 26px; height: 26px; border-radius: 50%; margin-right: 8px;">
                                            @endif
                                            <p style="color: black; font-size: 12px; margin-bottom: 0; font-size: 11px;">
                                                • {{ $message->created_at->format('M d, Y (h:i A)') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $previousUserId = $message->is_admin ? 'admin' : $message->user_id;
                        @endphp
                    @endforeach
                </div>

                <!-- Response Form -->
                <form action="{{ route('admin.messages.store') }}" method="POST" class="mt-3">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
                    <div class="form-group d-flex">
                        <input type="text" name="message" class="form-control" placeholder="Write your response..." required style="height: 40px; border-radius: 25px; margin-right: 10px;">
                        <button type="submit" class="btn btn-primary" style="border-radius: 50%; padding: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-paper-plane" viewBox="0 0 16 16">
                                <path d="M.146 8.854a.5.5 0 0 1 0-.708L13.5.146a.5.5 0 0 1 .707.707L3.207 8l11.001 7.146a.5.5 0 0 1-.707.707L.146 8.854z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            @else
                <h3 class="text-center">Select a user to view messages</h3>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var chatBox = document.querySelector(".chat-box");
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>
@endsection
