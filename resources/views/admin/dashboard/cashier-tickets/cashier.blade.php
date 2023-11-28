@extends('layout.main')

@section('title')
@endsection

@section('content')
<body class="font-sans bg-gray-100">
  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      <div class="container mx-auto p-8">
        <h1 class="text-4xl mb-4">Cashier Tickets</h1>


        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="flex mb-4">
            <form method="GET" action="{{ route('toCashierTickets') }}">
              <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Title" value="{{ request('search') }}">
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
              <th class="p-3 border-b text-left">Title</th>
              <th class="p-3 border-b text-left">Theater</th>
              <th class="p-3 border-b text-left">Date</th>
              <th class="p-3 border-b text-left">Duration</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($schedules as $schedule)
                @if ( $schedule['date']  <= date("Y-m-d") ) @continue @endif
                <tr class="hover:bg-gray-100 transition duration-300 ease-in-out hover:bg-gray-200">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left">{{ $schedule['title'] }}</td>
                    <td class="p-3 border-b text-left">{{ $schedule['theater'] }}</td>
                    <td class="p-3 border-b text-left">{{ $schedule['date'] }}</td>
                    <td class="p-3 border-b text-left">{{ $schedule['time_start'] . '-' . $schedule['time_end']}}</td>
                    <td class="p-3 border-b text-left">
                      @if ( $schedule['date']  <= date("Y-m-d") )
                        <p class="text-red-500">OUT</p>
                      @else
                        <a href="{{ route('checkoutTickets', $schedule['id']) }}" class="bg-green-500 text-white p-2 rounded hover:bg-green-700">Tickets</a>
                      @endif
                    </td>


                </tr>
            @endforeach
          </tbody>
        </table>

        {{ $schedules->links() }} 

      </div>
    </div>
  </div>

</body>

</body>
@endsection
