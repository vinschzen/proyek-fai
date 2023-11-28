@extends('layout.main')

@section('title', 'Saldo')

@section('content')

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<main class="container mx-auto mt-8">

    <h1 class="text-3xl font-semibold mb-4">Saldo</h1>

    <div class="mb-8">
        <p class="text-gray-700">Your current balance: {{ Session::get('user')->customClaims['saldo'] }} </p>
    </div>

    <div class="bg-gray-100 p-4 rounded shadow-md">
        <h2 class="text-xl font-semibold mb-4">Top-up Your Balance</h2>

        <form action="{{ route('topup', Session::get('user')->uid )}}" method="GET" class="flex items-center">
            @csrf
            <label for="amount" class="mr-4">Amount:</label>
            <input type="number" name="amount" id="amount" class="border rounded p-2" required>
            <button type="submit" class="bg-blue-500 text-white rounded p-2 ml-4">Top-up</button>
        </form>

        {{-- <form id="topupForm" action="{{ route('payment')}}" method="GET" class="flex items-center">
            @csrf
            <label for="amount" class="mr-4">Amount:</label>
            <input type="number" name="amount" id="amount" class="border rounded p-2" required> 
            <button type="submit" class="bg-blue-500 text-white rounded p-2 ml-4">Top-up</button> 
            @error('amount') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

        </form> --}}
    </div>

</main>

@if (Session::has('snapToken'))
<script>
        snap.pay('{{ Session::get('snapToken') }}');
    
</script>
@endif


@endsection
