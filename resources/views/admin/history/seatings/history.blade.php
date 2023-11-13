@extends('layout.main')

@section('title', 'Transaction History')

@section('content')
<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      
      <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold mb-8">Seatings History</h2>

        <div class="grid grid-cols-3 gap-4">
          {{-- @foreach($seatings as $t)
            <div class="bg-white rounded p-4 shadow-md cursor-pointer" onclick="window.location='{{ route('transaction.details', $transaction->id) }}'">
              <h3 class="text-xl font-semibold mb-2">{{ $transaction->title }}</h3>
              <p class="text-gray-600">{{ $transaction->created_at->format('M d, Y H:i A') }}</p>
            </div>
          @endforeach --}}
            <div class="bg-white rounded p-4 shadow-md cursor-pointer" onclick="window.location='{{ route('detailseatings') }}'"> 
              <h3 class="text-xl font-semibold mb-2">Title</h3>
              <p class="text-gray-600">dd - mm - yy</p>
            </div>
            <div class="bg-white rounded p-4 shadow-md cursor-pointer" onclick="window.location='{{ route('detailseatings') }}'">
              <h3 class="text-xl font-semibold mb-2">Title</h3>
              <p class="text-gray-600">dd - mm - yy</p>
            </div>
            <div class="bg-white rounded p-4 shadow-md cursor-pointer" onclick="window.location='{{ route('detailseatings') }}'">
              <h3 class="text-xl font-semibold mb-2">Title</h3>
              <p class="text-gray-600">dd - mm - yy</p>
            </div>
        </div>

      </div>

    </div>
  </div>

</body>
@endsection
