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

        <div class="bg-white rounded p-8 shadow-md">
          <h3 class="text-2xl font-semibold mb-4">Title</h3>
          <p class="text-gray-600 mb-4">Date: DD MM YY</p>
          <p class="text-gray-700">details</p>

        </div>

      </div>

    </div>
  </div>

</body>
@endsection
