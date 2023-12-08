@extends('layout.main')

@section('title')
@endsection

@section('content')
<style>
    @layer utilities {
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  }
</style>

<main class="container mx-auto mt-8">

    <h1 class="text-3xl font-semibold mb-4">Concessions</h1>

    <div class="flex mb-4">
        <form method="GET" action="{{ route('toConcessions') }}">
          <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Name" value="{{ request('search') }}">
          <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
      </form>
    
        </div>

    <h1 class="text-2xl font-semibold mb-4">Food</h1>

    <div class="grid grid-cols-5 gap-4 m-4">
        @foreach ($concessions as $concession)
            @if (isset($concession['deleted_at']))
                @continue
            @endif
            @if ($concession['category'] == "Food")
                <div class="text-center cursor-pointer bg-gray-0 hover:bg-gray-100">
                    <label for="">{{$concession['name']}} - @rupiah($concession['price']) </label>

                    <div class="object-none object-center p-3 border-b"><img src="{{ $concession['image'] }}" alt="{{ $concession['name'] }} Image" style="height: 150px"></div>    
                    
                    <form action="{{ route('addToUsersCart', $concession['id']) }}" method="get">
                        <div class="grid grid-cols-2 p-5">
                            <div class="flex items-center border-gray-100">
                                <span class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-blue-500 hover:text-blue-50" onclick="decrement('{{$concession['id']}}')"> - </span>
                                <input name="amount_to_add" id="input-{{$concession['id']}}" class="h-8 w-8 border bg-white text-center text-xs outline-none" type="number" value="1" min="1" />
                                <span class="cursor-pointer rounded-r bg-gray-100 py-1 px-3 duration-100 hover:bg-blue-500 hover:text-blue-50" onclick="increment('{{$concession['id']}}')"> + </span>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Add to Cart</button>
                        </div>
                    </form>

                </div>
            @endif
        @endforeach
    </div>

    <h1 class="text-2xl font-semibold mb-4">Beverage</h1>

    <div class="grid grid-cols-5 gap-4 m-4">
        @foreach ($concessions as $concession)
            @if (isset($concession['deleted_at']))
                @continue
            @endif
            @if ($concession['category'] == "Beverage")
                <div class="text-center cursor-pointer bg-gray-0 hover:bg-gray-100">
                    <label for="">{{$concession['name']}} - @rupiah($concession['price']) </label>

                    <div class="object-none object-center p-3 border-b"><img src="{{ $concession['image'] }}" alt="{{ $concession['name'] }} Image" style="height: 150px"></div>    
                    
                    <form action="{{ route('addToUsersCart', $concession['id']) }}" method="get">
                        <div class="grid grid-cols-2 p-5">
                            <div class="flex items-center border-gray-100">
                                <span class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-blue-500 hover:text-blue-50" onclick="decrement('{{$concession['id']}}')"> - </span>
                                <input name="amount_to_add" id="input-{{$concession['id']}}" class="h-8 w-8 border bg-white text-center text-xs outline-none" type="number" value="1" min="1" />
                                <span class="cursor-pointer rounded-r bg-gray-100 py-1 px-3 duration-100 hover:bg-blue-500 hover:text-blue-50" onclick="increment('{{$concession['id']}}')"> + </span>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Add to Cart</button>
                        </div>
                    </form>

                </div>
            @endif
        @endforeach
    </div>

    <h1 class="text-2xl font-semibold mb-4">Merchandise</h1>

    <div class="grid grid-cols-5 gap-4 m-4">
        @foreach ($concessions as $concession)
            @if (isset($concession['deleted_at']))
                @continue
            @endif
            @if ($concession['category'] == "Merchandise")
                <div class="text-center cursor-pointer bg-gray-0 hover:bg-gray-100">
                    <label for="">{{$concession['name']}} - @rupiah($concession['price']) </label>

                    <div class="object-none object-center p-3 border-b"><img src="{{ $concession['image'] }}" alt="{{ $concession['name'] }} Image" style="height: 150px"></div>    
                    
                    <form action="{{ route('addToUsersCart', $concession['id']) }}" method="get">
                        <div class="grid grid-cols-2 p-5">
                            <div class="flex items-center border-gray-100">
                                <span class="cursor-pointer rounded-l bg-gray-100 py-1 px-3.5 duration-100 hover:bg-blue-500 hover:text-blue-50" onclick="decrement('{{$concession['id']}}')"> - </span>
                                <input name="amount_to_add" id="input-{{$concession['id']}}" class="h-8 w-8 border bg-white text-center text-xs outline-none" type="number" value="1" min="1" />
                                <span class="cursor-pointer rounded-r bg-gray-100 py-1 px-3 duration-100 hover:bg-blue-500 hover:text-blue-50" onclick="increment('{{$concession['id']}}')"> + </span>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Add to Cart</button>
                        </div>
                    </form>

                </div>
            @endif
        @endforeach
    </div>


    {{-- {{ $concessions->links() }}  --}}

    
</main>

<script>

    function increment(id)
    {
        var input = document.getElementById("input-" + id);
        input.value++;
    }

    function decrement(id)
    {
        var input = document.getElementById("input-" + id);
        if (input.value > 0)
        {
            input.value--;
        }
    }
</script>

@endsection
