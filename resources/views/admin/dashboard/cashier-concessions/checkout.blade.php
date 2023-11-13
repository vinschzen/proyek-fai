@extends('layout.main')

@section('title', 'Theater Details')

@section('content')
<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      
      <div class="container mx-auto p-8">

        <ol class="list-none p-0 inline-flex">
          <li class="flex items-center">
            <a href="{{ route('toCashierConcessions') }}" class="text-blue-500">Cashier Concessions</a>
            <span class="mx-2">/</span>
          </li>
          <li class="flex items-center">
            <span class="text-gray-700">Concessions Checkout</span>
          </li>
        </ol>

        <h2 class="text-3xl font-semibold mb-8">Checkout</h2>

        <div class="grid grid-cols-5 gap-4">
            @php
                $seats = ['a','b','c','d','e']
            @endphp
            @foreach($seats as $seat)
                <div class="bg-gray-200 p-4 rounded cursor-pointer">
                    <p class="text-center text-gray-700">{{ $seat }}</p>
                </div>
            @endforeach
        </div>

      </div>

    </div>
  </div>

</body>
@endsection
