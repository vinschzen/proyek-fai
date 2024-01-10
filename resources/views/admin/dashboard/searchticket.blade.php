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
            </div>

            </div>


      </div>
      </div>
   

    </div>
  </div>

</body>
@endsection

