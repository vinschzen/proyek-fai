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
              <a href="{{ route('toMasterVoucher') }}" class="text-blue-500">Master Voucher</a>
              <span class="mx-2">/</span>
            </li>
            <li class="flex items-center">
              <span class="text-gray-700">Add Voucher</span>
            </li>
          </ol>
        </nav>

        <h1 class="text-4xl mb-4">Add Voucher</h1>

        <div class="grid grid-cols-2 gap-4">
              <form action="{{ route('voucher.adddetails') }}" method="GET  " enctype="multipart/form-data" class="bg-white p-4 rounded-lg shadow-md">
                @csrf
                <div class="mb-4">
                  <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                  <input type="text" name="name"class="w-full p-2 border rounded-md" placeholder="Enter Name" value="{{ old('name') }}">
                  @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>
    
                <div class="mb-4">
                  <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type :</label>
                  <select name="type" class="w-full p-2 border rounded-md">
                    <option value="Ticket" @if (old('type') == "Ticket") selected @endif>Ticket</option>
                    <option value="Concession" @if (old('type') == "Concession") selected @endif>Concession</option>
                  </select>
                </div>

                <div class="mb-4">
                  <div class="border p-4 rounded-md">
                      <label class="block text-gray-700 text-sm font-bold mb-2">Validity Period:</label>
                      <div class="mb-2">
                          <label for="validity_from" class="block text-gray-700 text-sm font-bold mb-1">From:</label>
                          <input type="date" name="validity_from" class="w-full p-2 border rounded-md" value="{{ old('validity_from') }}">
                          @error('validity_from') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

                      </div>
                      <div>
                          <label for="validity_until" class="block text-gray-700 text-sm font-bold mb-1">Until:</label>
                          <input type="date" name="validity_until" class="w-full p-2 border rounded-md" value="{{ old('validity_until') }}">
                          @error('validity_until') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

                      </div>
                  </div>
                </div>
    
                  <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Add Voucher</button>
                  </div>
                </form>
            
          </form>

          </div>


        </div>

      </div>

    </div>
  </div>

</body>
@endsection
