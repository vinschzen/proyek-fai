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
              <span class="text-gray-700">Add User</span>
            </li>
          </ol>
        </nav>
        
        <h1 class="text-4xl mb-4">Add User</h1>

        <div class="flex">

          <form action="{{ route('users.store') }}" method="POST" class="flex-1 max-w-lg bg-white p-6 rounded-lg shadow-md mr-4">
            @csrf
            <div class="mb-4">
              <label for="editEmail" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
              <input type="text" name="email" id="editUsername" class="w-full p-2 border rounded-md" value="{{ old('email') }}" >
            </div>
            <div class="mb-4">
              <label for="editUsername" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
              <input type="text" name="username" id="editUsername" class="w-full p-2 border rounded-md" value="{{ old('username') }}">
              @error('username') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
              <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
              <select name="role" id="role" class="w-full p-2 border rounded-md">
                <option value='0' >User</option>
                <option value='1' >Staff</option>
                <option value='2' >Admin</option>
              </select>
              @error('new_role') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
              <label for="newPassword" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
              <input type="password" name="password" id="newPassword" class="w-full p-2 border rounded-md" required>
              @error('password') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

              @if (Session::has('error'))
                <p class="text-red-500 text-xs italic">{{ Session::get('error') }}</p>
              @endif
            </div>
            <div class="text-center">
              <button type="submit" class="bg-yellow-500 text-white p-2 rounded-md">Add User</button>
            </div>
          </form>

        

        </div>

      </div>
    </div>
  </div>

</body>

@endsection

