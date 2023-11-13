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
        <h1 class="text-4xl mb-4">Master Play</h1>


        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="flex mb-4">
            <form method="GET" action="{{ route('toMasterPlay') }}">
              <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Title" value="{{ request('search') }}">
              <select class="p-2 ml-2 border border-gray-300 rounded" name="filter">
                <option value="newest" @if(request('filter') === 'newest') selected @endif>Newest</option>
                <option value="oldest" @if(request('filter') === 'oldest') selected @endif>Oldest</option>
              </select>
              <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
          </form>
        
        
            <a href="{{ route('toAddPlay') }}" class="ml-auto bg-green-500 text-white p-2 rounded hover:bg-green-700">Add Play</a>
        </div>

        <table class="w-full border border-collapse border-gray-300 mb-4">
          <thead>
            <tr>
              <th class="p-3 border-b text-left">No</th>
              <th class="p-3 border-b text-left">Poster</th>
              <th class="p-3 border-b text-left">Title</th>
              <th class="p-3 border-b text-left">Duration</th>
              <th class="p-3 border-b text-left">Age Ratings</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($plays as $play)
                <tr class="hover:bg-gray-100">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left"><img src="{{ asset('storage/' . $play['poster']) }}" alt="{{ $play['title'] }} Poster" style="width: 50px">                    </td>
                    <td class="p-3 border-b text-left">{{ $play['title'] }}</td>
                    <td class="p-3 border-b text-left">{{ $play['duration'] }}</td>
                    <td class="p-3 border-b text-left">{{ $play['age_rating'] }}</td>
                    <td class="p-3 border-b text-left">
                      <a href="{{ route('toEditPlay', $play['id']) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Edit</a>
                      <form id="deleteForm_{{ $play['id'] }}" action="{{ route('plays.destroy', $play['id']) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                      </form>
                      <button onclick="confirmDelete('{{ $play['id'] }}')" class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Delete</button>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>

        {{ $plays->links() }} 

      </div>
    </div>
  </div>

</body>

<script>
  function confirmDelete(playId) {

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
                  document.getElementById('deleteForm_' + playId).submit();
              }
          });

  }
</script>

@endsection
