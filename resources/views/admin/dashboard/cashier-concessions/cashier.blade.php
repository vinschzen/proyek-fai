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
        <h1 class="text-4xl mb-4">Cashier Concessions</h1>


        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="flex mb-4">
            <form method="GET" action="{{ route('toCashierConcessions') }}">
              <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Name" value="{{ request('search') }}">
              <select class="p-2 ml-2 border border-gray-300 rounded" name="filter">
                <option value="newest" @if(request('filter') === 'newest') selected @endif>Newest</option>
                <option value="oldest" @if(request('filter') === 'oldest') selected @endif>Oldest</option>
              </select>
              <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
          </form>
        
        </div>

        <table class="w-full border border-collapse border-gray-300 mb-4">
          <thead>
            <tr>
              <th class="p-3 border-b text-left">No</th>
              <th class="p-3 border-b text-left">Name</th>
              <th class="p-3 border-b text-left">Category</th>
              <th class="p-3 border-b text-left">Price</th>
              <th class="p-3 border-b text-left">Stock</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($concessions as $concession)
                <tr class="hover:bg-gray-100">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left">{{ $concession['name'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['category'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['price'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['stock'] }}</td>
                    <td class="p-3 border-b text-left">
                      <a href="{{ route('toEditConcession', $concession['id']) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Add to Cart</a>
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


@endsection
