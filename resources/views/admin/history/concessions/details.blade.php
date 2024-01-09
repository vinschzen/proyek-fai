@extends('layout.main')

@section('title', 'Transaction Details')

@section('content')
<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      
      <div class="container mx-auto p-8">

        <ol class="list-none p-0 inline-flex">
          <li class="flex items-center">
            <a href="{{ route('viewconcessions') }}" class="text-blue-500">History Concessions</a>
            <span class="mx-2">/</span>
          </li>
          <li class="flex items-center">
            <span class="text-gray-700">Concessions Details</span>
          </li>
        </ol>

        <h2 class="text-3xl font-semibold mb-8">Concessions Details</h2>

        {{-- {{dd($horder)}} --}}
        <div class="bg-white rounded p-4 shadow-md cursor-pointer transition duration-300 ease-in-out mb-8 hover:bg-gray-200">
          <div class="grid grid-cols-4 gap-3">
            <div class="col-span-2">
              <p class="text-gray-600">{{ $horder['created_at'] }} </p>
              <p class="text-gray-600">Buyer : {{ $horder['specific_user'] }}</p>
              <p class="text-gray-600 font-semibold">Total : @rupiah( $horder['total'] ) </p>

              @if (isset($horder['voucher']))
                <p class="text-gray-600">{{ $horder['voucher']['name'] }} </p>
                <p class="text-gray-600">Discount : {{ $horder['voucher']['discount']  }} %</p>
              @endif

            </div>
            <div class="col-span-2">
              @php
                  $concatenatedString = '';
              @endphp

              @foreach ($dorders as $d)
                  @php
                      $concatenatedString .= $d['name'] . ' x' . $d['qty'] . ', ';
                  @endphp
              @endforeach

              {{ Str::limit($concatenatedString, 40) }}
            </div>
          </div>
        </div>

        <table class="w-full border border-collapse border-gray-300 mb-4">
          <thead>
            <tr>
              <th class="p-3 border-b text-left">No</th>
              <th class="p-3 border-b text-left">Image</th>
              <th class="p-3 border-b text-left">Name</th>
              <th class="p-3 border-b text-left">Price</th>
              <th class="p-3 border-b text-left">Qty</th>
              <th class="p-3 border-b text-left">Total</th>
            </tr>
          </thead>
          <tbody>
            @php
              $total = 0;
            @endphp
            @foreach ($dorders as $concession)
                <tr class="hover:bg-gray-100 transition duration-300 ease-in-out hover:bg-gray-200">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left"><img src="{{ $concession['image'] }}" alt="{{ $concession['name'] }} Image" style="width: 80px"></td>
                    <td class="p-3 border-b text-left">{{ $concession['name'] }}</td>
                    <td class="p-3 border-b text-left"> @rupiah ( $concession['price'] ) </td>
                    <td class="p-3 border-b text-left">{{ $concession['qty'] }}</td>
                    <td class="p-3 border-b text-left">@rupiah (  $concession['price'] * $concession['qty'] )</td>
                    @php
                      $total += ($concession['price'] * $concession['qty']);
                    @endphp
                </tr>
            @endforeach
                <tr>
                  <td colspan="3">
                  </td>
                  <td>
                    <td class="p-3 border-b font-bold text-left">
                      Total :
                    </td>

                  </td>
                  <td class="p-3 border-b font-bold text-left">
                    @rupiah( $total )
                  </td>
                </tr>
          </tbody>
        </table>

        @if (isset($horder['voucher']))
          <h2 class="text-3xl font-semibold mb-8">Bonus</h2>

          <table class="w-full border border-collapse border-gray-300 mb-4">
            <thead>
              <tr>
                <th class="p-3 border-b text-left">No</th>
                <th class="p-3 border-b text-left">Image</th>
                <th class="p-3 border-b text-left">Name</th>
                <th class="p-3 border-b text-left">Qty</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($horder['then_get'] as $concession)
                  @php
                    $image = $concession['image'];
                  @endphp
                  <tr class="hover:bg-gray-100 transition duration-300 ease-in-out hover:bg-gray-200">
                      <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                      <td class="p-3 border-b text-left"><img src="{{ $image }}" alt="{{ $concession['name'] }} Image" style="width: 80px"></td>
                      <td class="p-3 border-b text-left">{{ $concession['name'] }}</td>
                      <td class="p-3 border-b text-left">{{ $concession['amount'] }}</td>
                  </tr>
              @endforeach
            </tbody>
          </table>
   
        @endif

      </div>

    </div>
  </div>

</body>
@endsection

