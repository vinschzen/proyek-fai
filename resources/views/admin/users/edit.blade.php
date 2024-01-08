@extends('layout.main')

@section('title')
@endsection

@section('content')
<body class="font-sans bg-gray-100">
  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      <div class="container mx-auto p-8">
        <nav class="mb-4">
          <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
              <a href="{{ route('toMasterUser') }}" class="text-blue-500">Master User</a>
              <span class="mx-2">/</span>
            </li>
            <li class="flex items-center">
              <span class="text-gray-700">Edit User</span>
            </li>
          </ol>
        </nav>
        
        <h1 class="text-4xl mb-4">Edit User</h1>

        <div class="flex">

          <form action="{{ route('users.changeusername', $user->uid) }}" method="POST" class="flex-1 max-w-lg bg-white p-6 rounded-lg shadow-md mr-4">
            @csrf
            <div class="mb-4">
              <label for="editEmail" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
              <input type="text" name="email" id="editUsername" class="w-full p-2 border rounded-md" value="{{ $user->email }}" disabled>
            </div>
            <div class="mb-4">
              <label for="saldo" class="block text-gray-700 text-sm font-bold mb-2">Saldo:</label>
              <input type="text" name="saldo" id="saldo" class="w-full p-2 border rounded-md" value="{{ $user->customClaims['saldo'] }}" disabled>
            </div>
            <div class="mb-4">
              <label for="editUsername" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
              <input type="text" name="username" id="editUsername" class="w-full p-2 border rounded-md" value="{{ $user->displayName }}">
              @error('username') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div class="text-center">
              <button type="submit" class="bg-yellow-500 text-white p-2 rounded-md">Edit User</button>
            </div>
          </form>

          <form action="{{ route('users.changepassword', $user->uid) }}" method="POST" class="flex-1 max-w-lg bg-white p-6 rounded-lg shadow-md mr-4">
            @csrf
            <div class="mb-4">
              <label for="newPassword" class="block text-gray-700 text-sm font-bold mb-2">New Password:</label>
              <input type="password" name="new_password" id="newPassword" class="w-full p-2 border rounded-md" required>
              @error('new_password') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div class="text-center">
              <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Change Password</button>
            </div>
          </form>

          <form action="{{ route('users.changerole', $user->uid) }}" method="POST" class="flex-1 max-w-lg bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
              <label for=c"newRole" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
              <select name="new_role" id="newRole" class="w-full p-2 border rounded-md">
                <option value='0' @if ($user->customClaims['role'] == 0) selected @endif>User</option>
                <option value='1' @if ($user->customClaims['role'] == 1) selected @endif>Staff</option>
                <option value='2' @if ($user->customClaims['role'] == 2) selected @endif>Admin</option>
              </select>
              @error('new_role') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div class="text-center">
              <button type="submit" class="bg-green-500 text-white p-2 rounded-md">Change Role</button>
            </div>
          </form>

        </div>

      </div>
    </div>
  </div>

</body>

@endsection

