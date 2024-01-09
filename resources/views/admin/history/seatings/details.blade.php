@extends('layout.main')

@section('title', 'Seating Details')

@section('content')
<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      
      <div class="container mx-auto p-8">

        <ol class="list-none p-0 inline-flex">
          <li class="flex items-center">
            <a href="{{ route('viewseatings') }}" class="text-blue-500">History Seatings</a>
            <span class="mx-2">/</span>
          </li>
          <li class="flex items-center">
            <span class="text-gray-700">Seating Details</span>
          </li>
        </ol>

        <h2 class="text-3xl font-semibold mb-8">Seating Details</h2>

        <div class="bg-white rounded p-4 shadow-md mb-4">
          <div class="grid grid-cols-7">
            <div class="col-span-1 m-5">
              <img src="{{ $hseating['poster'] }}" alt="{{ $hseating['title'] }} Poster" class="object-cover mb-4" width="150px">
            </div>
            <div class="col-span-6">
              <h3 class="text-2xl font-bold mb-4">{{ $hseating['title'] }}</h3>

              <p class="text-gray-600">Theater: {{ $hseating['theater'] }}</p>
              <p class="text-gray-600">Date: {{ $hseating['date'] }}</p>
              <p class="text-gray-600">Time: {{ $hseating['time_start']}} - {{ $hseating['time_end']}}</p>
              <p>Age Rating: {{ $hseating['age_rating'] }}</p>
              <hr class="m-4 font-bold">
              <p class="text-gray-600 font-semibold">Total Seats : {{ count($dseatings)}}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded p-8 shadow-md mb-4">
          <h3 class="text-2xl font-semibold mb-4">Seats</h3>
            <div class="grid grid-cols-2 gap-10">
              <div>
                <div class="grid grid-cols-12 gap-3">
                      @php
                          $seats = ['A','B','C','D','E', 'F', 'G', 'H']
                      @endphp
                      @foreach($seats as $seat)
                          @for ($i = 1; $i < 11; $i++)
                              @if (collect($dseatings)->pluck('seat')->contains($seat . $i))
                                <div class="bg-red-200 p-2 rounded cursor-pointer w-10 h-10 hover:bg-red-300">
                                  <p class="text-center text-gray-700">{{ $seat . $i }}</p>
                                </div>
                              @else
                                <div class="bg-gray-200 p-2 rounded cursor-pointer w-10 h-10 hover:bg-gray-300 seat">
                                    <p class="text-center text-gray-700">{{ $seat . $i }}</p>
                                    <input type="hidden" value name="seats[]">
                                </div>
                              @endif
                            
                            @if ($i == 3 || $i == 7)
                              <div width="100px"></div>
                            @endif
                          @endfor
                      @endforeach
                  </div>
    
                </div>
          </div>
          

      </div>

    </div>
  </div>

</body>
@endsection

