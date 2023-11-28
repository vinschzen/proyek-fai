@extends('layout.main')

@section('title')
@endsection

@section('content')

<main class="container mx-auto mt-8">

    <h1>Concessions</h1>

    order 

    checkout

    <div class="flex mb-4">
        <form method="GET" action="{{ route('toConcessions') }}">
          <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Name" value="{{ request('search') }}">
          <select class="p-2 ml-2 border border-gray-300 rounded" name="filter">
            <option value="newest" @if(request('filter') === 'newest') selected @endif>Newest</option>
            <option value="oldest" @if(request('filter') === 'oldest') selected @endif>Oldest</option>
          </select>
          <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
      </form>
    
        </div>

    <div class="grid grid-cols-5 gap-4 m-4">
        @foreach ($concessions as $concession)
            <div class="text-center cursor-pointer bg-gray-0 hover:bg-gray-100">
                <label for="">{{$concession['name']}}</label>
                <div class="p-3 border-b text-left"><img src="{{ asset('storage/' . $concession['image']) }}" alt="{{ $concession['name'] }} Image" style="width: 80px"></div>    
                <input type="number" name="amount_to_add" class="p-2 border rounded focus:outline-none focus:border-blue-500" value="1">
            </div>
        @endforeach

    </div>

    {{ $concessions->links() }} 

    
</main>

@endsection
