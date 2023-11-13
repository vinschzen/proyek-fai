@extends('layout.main')

@section('title')
@endsection

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css" rel="stylesheet">

<body class="font-sans bg-gray-100">
  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      <div class="container mx-auto p-8">
        <h1 class="text-4xl mb-4">Master User</h1>


        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="flex mb-4">
            <form method="GET" action="{{ route('toMasterUser') }}">
              <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Display Name" value="{{ request('search') }}">
              <select class="p-2 ml-2 border border-gray-300 rounded" name="filter">
                <option value="newest" @if(request('filter') === 'newest') selected @endif>Newest</option>
                <option value="oldest" @if(request('filter') === 'oldest') selected @endif>Oldest</option>
              </select>
              <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
          </form>
        
        
            {{-- <a href="{{ route('toAddUser') }}" class="ml-auto bg-green-500 text-white p-2 rounded hover:bg-green-700">Add User</a> --}}
        </div>

        <table class="w-full border border-collapse border-gray-300 mb-4">
          <thead>
            <tr>
              <th class="p-3 border-b text-left">No</th>
              <th class="p-3 border-b text-left">Email</th>
              <th class="p-3 border-b text-left">Display Name</th>
              <th class="p-3 border-b text-left">Role</th>
              <th class="p-3 border-b text-left">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $user)
                <tr class="hover:bg-gray-100">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left">{{ $user["email"] }}</td>
                    <td class="p-3 border-b text-left">{{ $user["displayName"] }}</td>
                    <td class="p-3 border-b text-left">{{ $user["role"] }}</td>
                    <td class="p-3 border-b text-left">{{ $user["status"] }}</td>
                    <td class="p-3 border-b text-left">
                      <a href="{{ route('toEditUser', $user['uid']) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Edit</a>

                      @if ( $user["status"] == "Disabled")
                        <a href="{{ route('users.toggle', $user['uid']) }}" class="bg-green-500 text-white p-2 rounded hover:bg-green-700">Enable</a>
                      @else
                        <a href="{{ route('users.toggle', $user['uid']) }}" class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Disable</a>
                      @endif

                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>

        {{ $users->links() }} 

      </div>
    </div>
  </div>

</body>


@endsection
