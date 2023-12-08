@extends('layout.main')

@section('title', 'Seating Details')

@section('content')

<body class="font-sans bg-gray-100">

    <div class="flex">
        <div class="flex-1">

            <div class="container mx-auto p-8">

                <ol class="list-none p-0 inline-flex">
                    <li class="flex items-center">
                        <a href="{{ route('toHome') }}" class="text-blue-500">Home</a>
                        <span class="mx-2">/</span>
                    </li>
                    <li class="flex items-center">
                        <span class="text-gray-700">Play</span>
                    </li>
                </ol>

                <h2 class="text-3xl font-semibold mb-8">Play</h2>

                <div class="bg-white rounded p-4 shadow-md mb-4">
                    <div class="grid grid-cols-7">
                        <div class="col-span-1 p-5">
                            <img src="{{ $play['poster'] }}" alt="{{ $play['title'] }} Poster"
                                class="object-cover mb-4">
                        </div>
                        <div class="col-span-6 p-5">
                            <h2 class="text-3xl font-bold mb-4">{{ $play['title'] }}</h2>
                            <div class="mb-4">
                                <p class="text-gray-700">{{ $play['description'] }}</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-lg font-semibold">Duration:</p>
                                <p class="text-gray-700">{{ $play['duration'] }} minutes</p>
                            </div>
                            <div class="mb-4">
                                <p class="text-lg font-semibold">Age Rating:</p>
                                <p class="text-gray-700">{{ $play['age_rating'] }}</p>
                            </div>

                            <hr class="my-4">

                            <div class="mb-4">
                                <p class="text-lg font-semibold">Directed By:</p>
                                <p class="text-gray-700">{{$play['director']}}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-lg font-semibold">Starring:</p>
                                <ul class="list-disc list-inside">
                                    @foreach ($play['casts'] as $cast)
                                    <li class="text-gray-700">
                                        {{$cast}}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <hr class="my-4 border-t-2 border-gray-300">
                        </div>
                    </div>

                    <table class="w-full border border-collapse border-gray-300 mb-4">
                        <thead>
                            <tr>
                                <th class="p-3 border-b text-left">No</th>
                                <th class="p-3 border-b text-left">Title</th>
                                <th class="p-3 border-b text-left">Theater</th>
                                <th class="p-3 border-b text-left">Date</th>
                                <th class="p-3 border-b text-left">Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                            @if (isset($schedule['deleted_at']))
                            @continue
                            @endif
                            @if ( $schedule['date'] <= date("Y-m-d") ) @continue @endif <tr
                                class="hover:bg-gray-100 transition duration-300 ease-in-out hover:bg-gray-200">
                                <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                                <td class="p-3 border-b text-left">{{ $play['title'] }}</td>
                                <td class="p-3 border-b text-left">{{ $schedule['theater'] }}</td>
                                <td class="p-3 border-b text-left">{{ $schedule['date'] }}</td>
                                <td class="p-3 border-b text-left">
                                    {{ $schedule['time_start'] . '-' . $schedule['time_end']}}</td>
                                <td class="p-3 border-b text-left">
                                    @if ( $schedule['date'] <= date("Y-m-d") ) <p class="text-red-500">OUT</p>
                                        @else
                                        <a href="{{ route('toCheckout', $schedule['id']) }}"
                                            class="bg-green-500 text-white p-2 rounded hover:bg-green-700">Tickets</a>
                                        @endif
                                </td>


                                </tr>
                                @endforeach
                        </tbody>
                    </table>

                    {{ $schedules->links() }}

                </div>




            </div>
        </div>

</body>
@endsection
