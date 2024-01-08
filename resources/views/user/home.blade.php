@extends('layout.main')

@section('title')
@endsection

@section('content')




<div class="relative w-full h-screen bg-center bg-cover">
    <div class="absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
        <div class="text-center">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <span class="text-gray-200 font-semibold uppercase tracking-widest">NEW UPCOMING THEATRE</span>
                    <h2 class="mt-8 mb-6 text-4xl lg:text-5xl font-bold text-gray-100">
                        Welcome to TXT.COM, find your favorite film and watch it now with your family.
                    </h2>
                    <p class="max-w-3xl mx-auto mb-10 text-lg text-gray-300">
                        Don't miss your favorite film. Also watch other latest films only at TXT.COM.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>




<main class="container mx-auto mt-8">

    <h1 class="text-3xl font-semibold mb-4">Now Playing</h1>

    <div class="grid grid-cols-4 gap-2">
        @foreach ($plays as $play)
        @if (isset($play['deleted_at']))
        @continue
        @endif
        <div>
            <a href="{{ route('toPlay', $play['id'] )}}">
                <img src="{{ $play['poster'] }}" alt="{{ $play['title'] }} Poster"
                    style="width: 200px" class="cursor-pointer image-modal-trigger"
                    data-image-url="{{ $play['poster'] }}">
            </a>
            {{$play['title']}}
        </div>
        @endforeach
    </div>
    <br>

</main>

@endsection
