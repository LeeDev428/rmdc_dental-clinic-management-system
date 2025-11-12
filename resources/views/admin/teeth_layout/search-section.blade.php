<div class="content-card search-section">
    <label for="user-search" class="form-label">Search Patient:</label>
    <input type="text" id="user-search" class="form-control" placeholder="Search patient by name or ID..." oninput="filterUsers()">
    <ul id="user-list" class="list-group">
        @foreach($users as $user)
            <li class="list-group-item" onclick="selectUser({{ $user->id }}, '{{ $user->name }}')">
                <strong>{{ $user->name }}</strong> <span style="color: #6b7280;">(ID: {{ $user->id }})</span>
            </li>
        @endforeach
    </ul>
</div>
