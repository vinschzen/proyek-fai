@extends('layout.main')

@section('title', 'Saldo')

@section('content')

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<main class="container mx-auto mt-8">

    <h1 class="text-3xl font-semibold mb-4">Saldo</h1>

    <div class="mb-8">
        <p class="text-gray-700">Your current account balance is: @rupiah( Session::get('user')->customClaims['saldo'] ) </p>
        <small class="text-gray-500">This balance reflects the total amount of funds currently available in your account. Feel free to explore the options below to manage and top up your account.</small>
    </div>
    
    <div class="bg-gray-100 p-8 m-8 rounded shadow-md">
        <h2 class="text-xl font-semibold mb-4">Midtrans</h2>


        <form id="topupForm" action="{{ route('payment')}}" method="GET" class="flex items-center">
            @csrf
            <label for="amount" class="mr-4">Amount:</label>
            <input type="number" name="amount" id="amount" class="border rounded p-2" required> 
            <button type="submit" class="bg-blue-500 text-white rounded p-2 ml-4 hover:bg-blue-800">Top-up</button> 
            @error('amount') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

            
        </form>
        <br>
        <div class="flex ml-2">
            <button type="button" class="ml-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 rounded-full bg-white text-gray-700 border transition duration-300 ease-in-out hover:bg-gray-200"
                onclick="document.getElementById('amount').value = 1000">1000</button>
            <button type="button" class="ml-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 rounded-full bg-white text-gray-700 border transition duration-300 ease-in-out hover:bg-gray-200"
                onclick="document.getElementById('amount').value = 10000">10000</button>
            <button type="button" class="ml-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 rounded-full bg-white text-gray-700 border transition duration-300 ease-in-out hover:bg-gray-200"
                onclick="document.getElementById('amount').value = 50000">50000</button>
            <button type="button" class="ml-4 text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 rounded-full bg-white text-gray-700 border transition duration-300 ease-in-out hover:bg-gray-200"
                onclick="document.getElementById('amount').value = 100000">100000</button>
        </div>
    </div>

    <div class="bg-gray-100 p-8 m-8 rounded shadow-md">
        <h2 class="text-xl font-semibold mb-4">Credit Card</h2>
        <form class="flex flex-wrap gap-3 w-full p-4 m-4">
            <label class="relative w-full flex flex-col">
              <span class="font-bold mb-3">Card number</span>
              <input class="rounded-md peer pl-12 pr-2 py-2 border-2 border-gray-200 placeholder-gray-300" type="text" name="card_number" placeholder="0000 0000 0000" />
              <svg xmlns="http://www.w3.org/2000/svg" class="absolute bottom-0 left-0 -mb-0.5 transform translate-x-1/2 -translate-y-1/2 text-black peer-placeholder-shown:text-gray-300 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </label>
          
            <label class="relative flex-1 flex flex-col">
              <span class="font-bold mb-3">Expire date</span>
              <input class="rounded-md peer pl-12 pr-2 py-2 border-2 border-gray-200 placeholder-gray-300" type="text" name="expire_date" placeholder="MM/YY" />
              <svg xmlns="http://www.w3.org/2000/svg" class="absolute bottom-0 left-0 -mb-0.5 transform translate-x-1/2 -translate-y-1/2 text-black peer-placeholder-shown:text-gray-300 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </label>
          
            <label class="relative flex-1 flex flex-col">
              <span class="font-bold flex items-center gap-3 mb-3">
                CVC/CVV
                <span class="relative group">
                  <span class="hidden group-hover:flex justify-center items-center px-2 py-1 text-xs absolute -right-2 transform translate-x-full -translate-y-1/2 w-max top-1/2 bg-black text-white"> Hey ceci est une infobulle !</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </span>
              <input class="rounded-md peer pl-12 pr-2 py-2 border-2 border-gray-200 placeholder-gray-300" type="text" name="card_cvc" placeholder="&bull;&bull;&bull;" />
              <svg xmlns="http://www.w3.org/2000/svg" class="absolute bottom-0 left-0 -mb-0.5 transform translate-x-1/2 -translate-y-1/2 text-black peer-placeholder-shown:text-gray-300 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </label>
          </form>

        <form action="{{ route('topup', Session::get('user')->uid )}}" method="GET" class="flex items-center">
            @csrf
            <label for="amount" class="mr-4">Amount:</label>
            <input type="number" name="amount" id="amount" class="border rounded p-2" required>
            <button type="submit" class="bg-blue-500 text-white rounded p-2 ml-4 hover:bg-blue-800">Top-up</button> 
        </form>
    </div>

</main>

@if (Session::has('snapToken'))
<script>
        snap.pay('{{ Session::get('snapToken') }}');
    
</script>
@endif


@endsection
