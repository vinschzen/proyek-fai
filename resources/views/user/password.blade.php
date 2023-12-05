@extends('layout.main')

@section('title')
@endsection

@section('content')


<main class="container mx-auto mt-8">
        <div class="container mx-auto">
                <div class="bg-white rounded shadow-md grid h-screen place-items-center">
                    {{-- <div class="flex items-center justify-center p-5">
                        <svg class="absolute w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="mt-4 text-center">
                        <h1 class="text-2xl font-bold"> {{ Session::get('user')->displayName }}</h1>
                        <p class="text-gray-600"> Change your password
                      </p>
                    </div> --}}

                    <div class="bg-white p-8 rounded shadow-md w-96 m-2">
                        <h2 class="text-2xl font-semibold mb-6">Reset Password</h2>
                    
                        <form action="{{ route('user.resetpassword', Session::get('user')->uid) }}" method="GET" class="flex-1 max-w-lg bg-white p-6 rounded-lg shadow-md mr-4">
                    
                            <div class="mb-6">
                                <label for="new_password" class="block text-sm font-medium text-gray-600">Old Password</label>
                                <input type="password" id="old_password" name="old_password" autocomplete="new-old_password"
                                       class="mt-1 p-2 w-full border rounded-md">
                                @error('old_password') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="mb-6">
                                <label for="new_password" class="block text-sm font-medium text-gray-600">New Password</label>
                                <input type="password" id="new_password" name="new_password" autocomplete="new-password"
                                class="mt-1 p-2 w-full border rounded-md">
                                
                                <label for="new_password" class="block text-sm font-medium text-gray-600">Confirm New Password</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password_confirmation"
                                class="mt-1 p-2 w-full border rounded-md">

                                @error('new_password') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

                                @if (Session::has('error'))
                                    <p class="text-red-500 text-xs italic">{{ Session::get('error') }}</p>
                                @endif
                            </div>


                            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">
                                Reset Password
                            </button>
                        </form>
                    </div>
                    
                </div>
            </div>
</main>

@endsection
