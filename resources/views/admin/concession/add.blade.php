@extends('layout.main')

@section('title')
@endsection

@section('content')
<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      <div class="container mx-auto p-8">
        <nav class="mb-4">
          <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
              <a href="{{ route('toMasterConcession') }}" class="text-blue-500">Master Concession</a>
              <span class="mx-2">/</span>
            </li>
            <li class="flex items-center">
              <span class="text-gray-700">Add Concession</span>
            </li>
          </ol>
        </nav>

        <h1 class="text-4xl mb-4">Add Concession</h1>

        <form action="{{ route('concession.store') }}" method="POST" enctype="multipart/form-data" class="max-w-lg bg-white p-6 rounded-lg shadow-md">
          @csrf
          <div class="mb-4 dropzone" id="dropzone">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Image :</label>
            <input type="file" name="image" id="image" class="w-full p-2 border rounded-md" value="{{ old('image') }}">
            @error('image') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
          </div>

          <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" name="name"class="w-full p-2 border rounded-md" placeholder="Enter Name" value="{{ old('name') }}">
            @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
          </div>

          <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
            <textarea name="description" id="description" class="w-full p-2 border rounded-md" placeholder="Enter Description">{{ old('description') }}</textarea>
             @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
          </div>

          <div class="mb-4">
            <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category :</label>
            <select name="category" class="w-full p-2 border rounded-md">
              <option value="Food" @if (old('category') == "Food") selected @endif>Food</option>
              <option value="Beverage" @if (old('category') == "Beverage") selected @endif>Beverage</option>
              <option value="Merchandise" @if (old('category') == "Merchandise") selected @endif>Merchandise</option>
            </select>
          </div>

          <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
            <input type="number" name="price"class="w-full p-2 border rounded-md" placeholder="Enter Price" value="{{ old('price') }}">
            @error('price') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
          </div>

          <div class="mb-4">
            <label for="stock" class="block text-gray-700 text-sm font-bold mb-2">Stock:</label>
            <input type="number" name="stock"class="w-full p-2 border rounded-md" placeholder="Enter Stock" value="{{ old('stock') }}">
            @error('stock') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
          </div>
          

          <div class="text-center">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Add Concession</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>

@endsection
