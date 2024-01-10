@extends('layout.main')

@section('title', 'Transaction History')

@section('content')
<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      
      <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold mb-8">Search Ticket</h2>

        <div class="grid grid-cols-2 gap-4">
          <form action="{{ route('toSearchedTicket') }}" method="GET" class="bg-white p-4 rounded-lg shadow-md">
              @csrf
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Enter ID :</label>
                <input type="text" name="id" class="w-9/12 p-2 border rounded-md" placeholder="********" value="{{ old('id') }}">

                <button type="submit" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-700">Search</button>
                @if (Session::get('error'))
                <p class="text-red-500 text-xs italic">{{ Session::get('error') }}</p>
                @endif
      
            </form>
        </div>

        <h2 class="text-2xl font-semibold mb-12 m-2">History</h2>
        <div class="container mx-auto p-4">
          <table class="min-w-full bg-white border border-gray-300">
              <thead>
                  <tr>
                      <th class="py-2 px-4 border-b">Key</th>
                      <th class="py-2 px-4 border-b">Specific User</th>
                      <th class="py-2 px-4 border-b">Created Date</th>
                  </tr>
              </thead>
              <tbody>
                  @forelse ($htickets as $key => $h)
                      @php
                          $timestamp = $h['created_at'] / 1000;
                          $date = new DateTime("@$timestamp");
                          $formattedDate = $date->format('Y-m-d');
                      @endphp
                      <tr>
                          <td class="py-2 px-4 border-b">{{ $key }}</td>
                          <td class="py-2 px-4 border-b">{{ $h['specific_user'] }}</td>
                          <td class="py-2 px-4 border-b">{{ $formattedDate }}</td>
                      </tr>
                  @empty
                      <tr>
                          <td class="py-2 px-4 border-b" colspan="3">No records found</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
      
            </div>

          </div>


      </div>
      </div>
   

    </div>
  </div>

</body>
@endsection

