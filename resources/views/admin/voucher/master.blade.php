@extends('layout.main')

@section('title')
@endsection

@section('content')


<body class="font-sans bg-gray-100">


  <div class="flex">

    @include('layout.admin-side')

    <div class="flex-1">
      <div class="container mx-auto p-8">
        <h1 class="text-4xl mb-4">Master Voucher</h1>

        <div class="flex mb-4">
          <input type="text" class="p-2 border border-gray-300 rounded" placeholder="Search...">
          <select class="p-2 ml-2 border border-gray-300 rounded">
            <option value="filter1">Newest</option>
            <option value="filter2">Older</option>
            <option disabled>─────────</option>
            <option value="filter3">Admin</option>
            <option value="filter4">Staff</option>
            <option value="filter5">User</option>
          </select>
        </div>

        <table class="w-full border border-collapse border-gray-300 mb-4">
          <thead>
            <tr>
              <th class="p-3 border-b text-left">No</th>
              <th class="p-3 border-b text-left">Username</th>
              <th class="p-3 border-b text-left">Email</th>
              <th class="p-3 border-b text-left">Role</th>
              {{-- <th class="p-3 border-b text-left">Actions</th> --}}
            </tr>
          </thead>
          <tbody>
            <tr class="hover:bg-gray-100">
              <td class="p-3 border-b text-left">1.</td>
              <td class="p-3 border-b text-left">User1</td>
              <td class="p-3 border-b text-left">email@email.com</td>
              <td class="p-3 border-b text-left">Admin</td>
              <td class="p-3 border-b text-left">
                <button class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Edit</button>
                <button class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Delete</button>
              </td>
            </tr>
            <tr class="hover:bg-gray-100">
              <td class="p-3 border-b text-left">2.</td>
              <td class="p-3 border-b text-left">User2</td>
              <td class="p-3 border-b text-left">email2@email.com</td>
              <td class="p-3 border-b text-left">Staff</td>
              <td class="p-3 border-b text-left">
                <button class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Edit</button>
                <button class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>

        <ul class="flex list-none">
          <li class="mr-2"><a href="#" class="p-2 border border-gray-300 rounded">1</a></li>
          <li class="mr-2"><a href="#" class="p-2 border border-gray-300 rounded">2</a></li>
          <li class="mr-2"><a href="#" class="p-2 border border-gray-300 rounded">3</a></li>
        </ul>
      </div>
    </div>
  </div>

</body>

@endsection
