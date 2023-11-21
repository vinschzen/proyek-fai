@extends('layout.main')

@section('title', 'Admin Dashboard')

@section('content')

<link rel="stylesheet" href="{{ asset('css/print.css') }}" media="print">


<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      
      <div class="container mx-auto p-8">
        <h1 class="text-4xl mb-4">Welcome, Admin</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div class="bg-white p-4 rounded-md shadow-md">
            <h2 class="text-lg font-semibold mb-2">Total Users</h2>
            <p class="text-2xl font-bold">1500</p>
          </div>

          <div class="bg-white p-4 rounded-md shadow-md">
            <h2 class="text-lg font-semibold mb-2">Total Orders</h2>
            <p class="text-2xl font-bold">1200</p>
          </div>

          <div class="bg-white p-4 rounded-md shadow-md">
            <h2 class="text-lg font-semibold mb-2">Revenue</h2>
            <p class="text-2xl font-bold">$50,000</p>
          </div>

          <div class="bg-white p-4 rounded-md shadow-md">
            <h2 class="text-lg font-semibold mb-2">Average Order Value</h2>
            <p class="text-2xl font-bold">$42.00</p>
          </div>

        </div>

        <div class="grid grid-cols-3 gap-5 p-4">
          <div class="bg-white p-4 rounded-md shadow-md p-5">
            @include('layout.chart-1')
          </div>
          <div class="bg-white p-4 rounded-md shadow-md p-5">
            @include('layout.chart-2')
          </div>
          <div class="bg-white p-4 rounded-md shadow-md p-5">
            @include('layout.chart-3')
          </div>
        </div>


        <button onclick="printPage()" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-800 mt-4">Print Page</button>

      </div>

    </div>
  </div>

  <script>
    function printPage() {
      var elementsOutsideBody = document.head.children;

// Iterate through each element outside the body
      for (var i = 0; i < elementsOutsideBody.length; i++) {
        var element = elementsOutsideBody[i];

        // Set the display property to none
        element.style.display = 'none';
      }
        window.print();
    }
  </script>
</body>
@endsection
