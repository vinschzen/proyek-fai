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
        <h1 class="text-4xl mb-4">Master Schedule</h1>


        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="flex mb-4">
            <form method="GET" action="{{ route('toMasterSchedule') }}">
              <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Title" value="{{ request('search') }}">
              <select class="p-2 ml-2 border border-gray-300 rounded" name="filter">
                <option value="newest" @if(request('filter') === 'newest') selected @endif>Newest</option>
                <option value="oldest" @if(request('filter') === 'oldest') selected @endif>Oldest</option>
              </select>
              <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
          </form>
        
        
            <a href="{{ route('toAddSchedule') }}" class="ml-auto bg-green-500 text-white p-2 rounded hover:bg-green-700">Add Schedule</a>
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
                @if (isset($schedule['deleted_at']))
                    @continue
                @endif
                <tr class="transition duration-300 ease-in-out  @if ( $schedule['date']  <= date("Y-m-d") ) bg-red-200 hover:bg-red-300 @else bg-gray-200 hover:bg-gray-300  @endif ">

                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left">{{ $schedule['title'] }}</td>
                    <td class="p-3 border-b text-left">{{ $schedule['theater'] }}</td>
                    <td class="p-3 border-b text-left">{{ $schedule['date'] }}</td>
                    <td class="p-3 border-b text-left" @if ( $schedule['date']  <= date("Y-m-d") && $schedule["time_end"] <= date("h:i") ) text-red-500 @endif >{{ $schedule['time_start'] . '-' . $schedule['time_end']}}</td>

                    <td class="p-3 border-b text-left">
                      <a href="{{ route('toEditSchedule', $schedule['id']) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Edit</a>
                      <form id="deleteForm_{{ $schedule['id'] }}" action="{{ route('schedule.destroy', $schedule['id']) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                      </form>
                      <button onclick="confirmDelete('{{ $schedule['id'] }}')" class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Delete</button>
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

<script>
  function confirmDelete(scheduleId) {
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
              document.getElementById('deleteForm_' + scheduleId).submit();
          }
      });
  }
</script>

@endsection
