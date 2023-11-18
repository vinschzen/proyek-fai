@extends('layout.main')

@section('title', 'Transaction History')

@section('content')
<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      
      <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold mb-8">Tickets History</h2>

        <div class="flex mb-4">
          <form method="GET" action="{{ route('viewtickets') }}">
            From :
            <input type="date" class="p-2 border border-gray-300 rounded" name="date-from" value="{{ request('date-from') }}">
            To :
            <input type="date" class="p-2 border border-gray-300 rounded" name="date-until" value="{{ request('date-until') }}">

            <select class="p-2 ml-2 border border-gray-300 rounded" name="filter">
              <option value="newest" @if(request('filter') === 'newest') selected @endif>Newest</option>
              <option value="oldest" @if(request('filter') === 'oldest') selected @endif>Oldest</option>
            </select>

            <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
        </form>
      
      
      </div>

        <div class="grid grid-cols-3 gap-4">
          @foreach($htickets as $t)
            <div class="bg-white rounded p-4 shadow-md cursor-pointer transition duration-300 ease-in-out hover:bg-gray-200" onclick="window.location='{{ route('detailtickets', $t['id'] ) }}'">
              <div class="grid grid-cols-4">
                <div>
                  <p class="text-6xl font-bold mb-8 m-6">{{ $t['theater'] }}</p>
                </div>
                <div class="col-span-3">
                  <h3 class="text-xl font-semibold mb-2">{{ $t['title'] }}</h3> <i>{{$t['created_at']}}</i>
                  <p class="text-gray-600">{{ $t['date'] }} : {{ $t['time_start']}} - {{ $t['time_end']}}</p>
                  <p class="text-gray-600">{{ $t['specific_user'] }}</p>
                  <p class="text-gray-600 font-semibold">Total : {{ $t['total'] }}</p>
                </div>
              </div>
            </div>
          @endforeach
            
        </div>
        
        {{ $htickets->links() }} 
      </div>

    </div>
  </div>

</body>
@endsection
