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
        <h1 class="text-4xl mb-4">Cashier Concessions</h1>


        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="flex mb-4">
            <form method="GET" action="{{ route('toCashierConcessions') }}">
              <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Name" value="{{ request('search') }}">
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
              <th class="p-3 border-b text-left">Image</th>
              <th class="p-3 border-b text-left">Name</th>
              <th class="p-3 border-b text-left">Category</th>
              <th class="p-3 border-b text-left">Price</th>
              <th class="p-3 border-b text-left">Stock</th>
              <th class="p-3 border-b text-left">Add Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($concessions as $concession)
                <tr class="hover:bg-gray-100 transition duration-300 ease-in-out hover:bg-gray-200">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left"><img src="{{ asset('storage/' . $concession['image']) }}" alt="{{ $concession['name'] }} Image" style="width: 80px"></td>
                    <td class="p-3 border-b text-left">{{ $concession['name'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['category'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['price'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['stock'] }}</td>
                    <form action="{{ route('addToCart', $concession['id']) }}" method="get">
                      <td class="p-3 border-b text-left">
                        <input type="number" name="amount_to_add" class="p-2 border rounded focus:outline-none focus:border-blue-500" value="1">
                      </td>
                      <td class="p-3 border-b text-left">
                        <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Add to Cart</button>
                      </td>
                    </form>
                  </tr>
                  @endforeach
          </tbody>
        </table>

        {{ $concessions->links() }} 

        <h1 class="text-4xl mb-4">Cart</h1>

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
            @foreach (Session::get('cashier') ?? [] as $concession)
                <tr class="hover:bg-gray-100 transition duration-300 ease-in-out hover:bg-gray-200">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left"><img src="{{ asset('storage/' . $concession['image']) }}" alt="{{ $concession['name'] }} Image" style="width: 80px"></td>
                    <td class="p-3 border-b text-left">{{ $concession['name'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['price'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['qty'] }}</td>
                    <td class="p-3 border-b text-left">{{ $concession['price'] * $concession['qty'] }}</td>
                    <td class="p-3 border-b text-left">
                      <a href="{{ route('removeFromCart', $concession['id']) }}" class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Remove</a>
                    </td>
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
                    {{$total}}
                  </td>
                </tr>
          </tbody>
        </table>

        @if (Session::get('cashier'))
          <div class="mb-4">
              <a href="{{ route('clearCart') }}" class="ml-auto bg-gray-500 text-white p-2 rounded hover:bg-gray-700">Clear</a>
              <a href="{{ route('checkoutConcessions') }}" class="ml-auto bg-green-500 text-white p-2 rounded hover:bg-green-700">Checkout</a>
          </div>
          
        @endif


      </div>
    </div>
  </div>

</body>


@endsection
