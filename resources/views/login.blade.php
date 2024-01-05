@extends('layout.main')

@section('title')
@endsection

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css" rel="stylesheet">

<main class="container mx-auto mt-8">
    <div class="container mx-auto mt-8">
        <div class="flex items-center justify-center"> <!-- Centering the container -->
        <div>
            <h2 class="text-4xl font-semibold mb-4 text-center">Login</h2> <!-- Increased text size and centered -->
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-14" >
                    <form method="POST" action="{{ route('login') }}" style="width: 30vw">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                Email
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="email" type="email" name="email" placeholder="Email">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                                Password
                            </label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="password" type="password" name="password" placeholder="******************">
                        </div>
                        <div class="flex items-center justify-between">
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                type="submit">
                                Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@if (Session::has('msg'))
<script>
    Swal.fire({
        title: 'Error',
        text: '{{ Session::get('msg') }}',
        icon: 'warning',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    }).then((result) => {
    });  
</script>
@endif
@endsection
