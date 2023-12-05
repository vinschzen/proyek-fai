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

        <form method="GET" class="m-5">
          From :
          <input type="date" class="p-2 border border-gray-300 rounded" name="date-from" value="{{ request('date-from') }}">
          To :
          <input type="date" class="p-2 border border-gray-300 rounded" name="date-until" value="{{ request('date-until') }}">

          <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div class="bg-white p-4 rounded-md shadow-md">
            <h2 class="text-lg font-semibold mb-2 cursor-default ">Total Users</h2>
            <p class="text-2xl font-bold cursor-default transition duration-300 ease-in-ease-out hover:text-blue-400">5</p>
          </div>

          <div class="bg-white p-4 rounded-md shadow-md">
            <h2 class="text-lg font-semibold mb-2 cursor-default ">Total Orders</h2>
            <p class="text-2xl font-bold cursor-default transition duration-300 ease-in-ease-out hover:text-blue-400">30</p>
          </div>

          <div class="bg-white p-4 rounded-md shadow-md">
            <h2 class="text-lg font-semibold mb-2 cursor-default">Revenue</h2>
            <p class="text-2xl font-bold cursor-default transition duration-300 ease-in-ease-out hover:text-blue-400">Rp. 300.000</p>
          </div>

          <div class="bg-white p-4 rounded-md shadow-md">
            <h2 class="text-lg font-semibold mb-2 cursor-default ">Average Order Value</h2>
            <p class="text-2xl font-bold cursor-default transition duration-300 ease-in-ease-out hover:text-blue-400" >Rp. 25.000</p>
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


        <button onclick="printPage()" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-800 mt-4"><i class="fa-solid fa-print"></i> Print</button>

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
