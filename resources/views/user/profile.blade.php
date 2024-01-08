@extends('layout.main')

@section('title')
@endsection

@section('content')


<main class="container mx-auto mt-8">
        <div class="container mx-auto mt-8 p-4">
                <div class="bg-white p-8 m-5 rounded shadow-md">
                    <div class="flex items-center justify-center p-5">
                        <svg class="absolute w-20 h-20 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="mt-4 text-center">
                        <h1 class="text-2xl font-bold"> {{ Session::get('user')->displayName }}</h1>
                        <p class="text-gray-600">
                        @switch(Session::get('user')->customClaims['role'])
                                @case(0)
                                        <span>User</span>
                                        @break
                                @case(1)
                                        <span>Staff</span>
                                        @break
                                @case(2)
                                        <span>Admin</span>
                                        @break
                        @endswitch
                      </p>
                    </div>
                    <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700"><a href="{{ route('toPassword') }}">Change Password</a></button>
                    @if (Session::has('msg'))
                        <span class="text-green-300">{{Session::get('msg')}}</span>
                    @endif
                    <div class="mt-6 align-items: flex-end;">
                        <h2 class="text-lg font-semibold mb-2">Description</h2>
                        <p class="text-gray-700">Write a brief description about yourself.</p>
                      </div>
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold mb-2">Information</h2>
                        <p class="text-gray-700">Email: {{ Session::get('user')->email }}</p>
                    </div>
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold mb-2">Tickets</h2>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                      @foreach($htickets as $t)
                        <div class="bg-white rounded p-4 shadow-md cursor-pointer transition duration-300 ease-in-out hover:bg-gray-200" onclick="window.location='{{ route('toTicket', $t['id'] ) }}'">
                          <div class="grid grid-cols-4">
                            <div>
                              <p class="text-6xl font-bold mb-8 m-6">{{ $t['theater'] }}</p>
                            </div>
                            <div class="col-span-3">
                              <h3 class="text-xl font-semibold mb-2">{{ $t['title'] }}</h3> <i>{{$t['created_at']}}</i>
                              <p class="text-gray-600">{{ $t['date'] }} : {{ $t['time_start']}} - {{ $t['time_end']}}</p>
                              <p class="text-gray-600">{{ $t['specific_user'] }}</p>
                              <p class="text-gray-600 font-semibold">Total : {{ $t['total'] }}</p>
                            </div>
                          </div>
                        </div>
                      @endforeach
                        
                    </div>
                    
                    {{ $htickets->links() }} 
                  </div>
                    </div>
                </div>
            </div>


</main>

@endsection
