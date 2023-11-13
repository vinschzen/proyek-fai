@extends('layout.main')

@section('title')
@endsection

@section('content')

<main class="container mx-auto mt-8">
    <div class="container mx-auto mt-8">
      <h2 class="text-2xl font-semibold mb-4">Register</h2>
      <form class="max-w-md bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('register') }}">
          @csrf
          <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                  Name
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Your Name"  name="username" >
          </div>
          <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                  Email
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email" name="email">
          </div>
          <div class="mb-6">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                  Password
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************"  name="password">
          </div>
          <div class="mb-6">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                  Confirm Password
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************"  name="password_confirmation">
          </div>
          @if (Session::has('msg'))
              <span style="color='red'">{{ Session::get('msg'); }}</span>
          @endif
          <div class="flex items-center justify-between">
              <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                  Register
              </button>
          </div>
      </form>
  </div>

  <!-- Login Form -->
  <div class="container mx-auto mt-8">
      <h2 class="text-2xl font-semibold mb-4">Login</h2>
      <form class="max-w-md bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="{{ route('login') }}">
          @csrf
          <!-- Email Field -->
          <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                  Email
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" placeholder="Email">
          </div>
          <!-- Password Field -->
          <div class="mb-6">
              <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                  Password
              </label>
              <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" placeholder="******************">
          </div>
          <!-- Login Button -->
          <div class="flex items-center justify-between">
              <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                  Login
              </button>
          </div>
      </form>
  </div></main>

@endsection
