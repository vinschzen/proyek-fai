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
              <span class="text-gray-700">Edit Voucher</span>
            </li>
          </ol>
        </nav>

        <h1 class="text-4xl mb-4">Edit Voucher</h1>

        <div class="grid grid-cols-2 gap-4">
              <form action="{{ route('voucher.edit', $voucher['id']) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded-lg shadow-md">
                @csrf
                <div class="mb-4">
                  <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                  <input type="text" name="name"class="w-full p-2 border rounded-md" placeholder="Enter Name" value="{{ old('name') ?? $voucher['name'] }}">
                  @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>
    
                <div class="mb-4">
                  <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type :</label>
                  <select name="type" class="w-full p-2 border rounded-md" disabled>
                    <option value="Ticket" @if (old('type') ?? $voucher['type'] == "Ticket") selected @endif>Ticket</option>
                    <option value="Concession" @if (old('type') ?? $voucher['type'] == "Concession") selected @endif>Concession</option>
                  </select>
                </div>

                <div class="mb-4">
                  <div class="border p-4 rounded-md">
                      <label class="block text-gray-700 text-sm font-bold mb-2">Validity Period:</label>
                      <div class="mb-2">
                          <label for="validity_from" class="block text-gray-700 text-sm font-bold mb-1">From:</label>
                          <input type="date" name="validity_from" class="w-full p-2 border rounded-md" value="{{ old('validity_from') ?? $voucher['validity_from']}}">
                          @error('validity_from') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

                      </div>
                      <div>
                          <label for="validity_until" class="block text-gray-700 text-sm font-bold mb-1">Until:</label>
                          <input type="date" name="validity_until" class="w-full p-2 border rounded-md" value="{{ old('validity_until') ?? $voucher['validity_until']}}">
                          @error('validity_until') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

                      </div>
                  </div>
                </div>
    
                  <div class="text-center">
                    <button type="submit" class="bg-yellow-500 text-white p-2 rounded-md">Edit Voucher</button>
                  </div>
              </form>

              <div class="col-2">
                <div class="mb-4">
                  <div class="container mx-auto mt-4 p-6 bg-white rounded-lg shadow-md">

                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Details :</label>
                    @if ($voucher["type"] == 'Ticket')
                    <p class="text-blue-500 font-semibold mb-2">Ticket Amount: {{ $voucher["ticket_amount"] }}</p>
                    <p class="text-green-500 font-semibold mb-2">Specific Play: {{ $voucher["specific_play_title"] }}</p>
                    <p class="text-yellow-500 font-semibold mb-2">discount: {{ $voucher["discount"] }}%</p>
                  @else
                    <p class="text-blue-500 font-semibold mb-2">If Bought :</p>
                      <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                          <tr>
                            <th class="py-2 px-4 border-b">Image</th>
                            <th class="py-2 px-4 border-b">Name</th>
                            <th class="py-2 px-4 border-b">Category</th>
                            <th class="py-2 px-4 border-b">Amount</th>
                          </tr>
                        </thead>
                            @foreach ($voucher["if_bought_data"] as $key => $value)
                                  <tr>
                                    <td class="py-2 px-4 border-b"><img src="{{ $value['image'] }}" alt="{{ $value['name'] }} Image" style="width: 80px"></td>
                                    <td class="py-2 px-4 border-b">{{$value['name']}}</td>
                                    <td class="py-2 px-4 border-b">{{$value['category']}}</td>
                                    <td class="py-2 px-4 border-b">{{$value['amount']}}</td>
                                  </tr>
                                  @endforeach
                          </tbody>
                        </table>
                    <p class="text-green-500 font-semibold mb-2">Then Get :</p>
                    <table class="min-w-full bg-white border border-gray-300">
                      <thead>
                        <tr>
                          <th class="py-2 px-4 border-b">Image</th>
                          <th class="py-2 px-4 border-b">Name</th>
                          <th class="py-2 px-4 border-b">Category</th>
                          <th class="py-2 px-4 border-b">Amount</th>
                        </tr>
                      </thead>
                      @foreach ($voucher["then_get_data"] as $key => $value)
                        <tr>
                          <td class="py-2 px-4 border-b"><img src="{{ $value['image'] }}" alt="{{ $value['name'] }} Image" style="width: 80px"></td>
                          <td class="py-2 px-4 border-b">{{$value['name']}}</td>
                          <td class="py-2 px-4 border-b">{{$value['category']}}</td>
                          <td class="py-2 px-4 border-b">{{$value['amount']}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                  <p class="text-yellow-500 font-semibold mb-2">discount: {{ $voucher["discount"] }}%</p>

                  @endif
                </div>
              </div>
             
    

              </div>


        </div>

      </div>

    </div>
  </div>

</body>
@endsection
