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
        <h1 class="text-4xl mb-4">Master Concession</h1>


        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="flex mb-4">
            <form method="GET" action="{{ route('toMasterConcession') }}">
              <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Name" value="{{ request('search') }}">
              <select class="p-2 ml-2 border border-gray-300 rounded" name="filter">
                <option value="newest" @if(request('filter') === 'newest') selected @endif>Newest</option>
                <option value="oldest" @if(request('filter') === 'oldest') selected @endif>Oldest</option>
              </select>
              <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
          </form>


            <a href="{{ route('toAddConcession') }}" class="ml-auto bg-green-500 text-white p-2 rounded hover:bg-green-700">Add Concession</a>
        </div>

        <table class="w-full border border-collapse border-gray-300 mb-4">
          <thead>
            <tr>
              <th class="p-3 border-b text-left">No</th>
              <th class="p-3 border-b text-left">Image</th>
              <th class="p-3 border-b text-left">Name</th>
              <th class="p-3 border-b text-left">Category</th>
              <th class="p-3 border-b text-left">Price</th>
              <th class="p-3 border-b text-left">Stock</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($concessions as $concession)
                <tr class="hover:bg-gray-100 ">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left"><img src="{{ asset('storage/' . $concession['image']) }}" alt="{{ $concession['name'] }} Image" style="width: 80px"></td>
                    <td class="p-3 border-b text-left">{{ $concession['name'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['category'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['price'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['stock'] }}</td>
                    <td class="p-3 border-b text-left">
                      <a href="{{ route('toEditConcession', $concession['id']) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                      <form id="deleteForm_{{ $concession['id'] }}" action="{{ route('concession.destroy', $concession['id']) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                      </form>
                      <button onclick="confirmDelete('{{ $concession['id'] }}')" class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Delete</button>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>

        {{ $concessions->links() }}

      </div>
    </div>
  </div>

</body>

<script>
  function confirmDelete(concessionId) {

          Swal.fire({
              title: 'Are you sure?',
              text: 'You won\'t be able to revert this!',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
              if (result.isConfirmed) {
                  document.getElementById('deleteForm_' + concessionId).submit();
              }
          });

  }
</script>

@endsection
