@extends('layout.main')

@section('title')
@endsection

@section('content')
<style>
    .poster-slide {
  --slice-n: 6;

  width: 100%;
  height: 500px;
  max-height: 100%;
  display: flex;
  justify-content: space-between;
}

.poster-slide img {
  object-fit: cover;
  width: calc(100% / var(--slice-n));
  height: 100%;
  transition: width 1s;
}

.poster-slide.hovered img {
  width: calc( calc(100% - 35%) / calc(var(--slice-n) - 1) );
}

.poster-slide img:hover {
  width: calc(35%)
}

.container { position:relative; }
.container video {
    position:relative;
    z-index:0;
}
.overlay {
    position:absolute;
    top:0;
    left:0;
    z-index:1;
}
</style>
<div class="relative w-full h-screen bg-center bg-cover" >
    <div class="absolute inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center py-48">        
        <video autoplay loop muted class="absolute inset-0 w-full h-full object-cover">
            <source src="https://firebasestorage.googleapis.com/v0/b/proyek-fai-98bc0.appspot.com/o/assets%2Fpromo.mp4?alt=media&token=abeab7fa-8ea8-4d4a-8cb5-7fa18b28f84b" type="video/mp4">
        </video>

        <div class="text-center text-white">
            <div class="container px-4 mx-auto">
                <div class="max-w-4xl mx-auto">
                    {{-- <div id="indicators-carousel" class="relative w-full" data-carousel="static">
                        <!-- Carousel wrapper -->
                        <div class="relative h-96 overflow-hidden rounded-lg">
                            @php
                                $ctr = 0;
                            @endphp
                            @foreach ($plays as $play)
                                @if ($ctr < 5)
                                    @if (isset($play['deleted_at']))
                                        @continue
                                    @endif
                                    <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                                        <a href="{{ route('toPlay', $play['id'] )}}">
                                            <div class="h-full flex items-center justify-center">
                                                <img src="{{ $play['poster'] }}" alt="{{ $play['title'] }} Poster"
                                                    class="cursor-pointer image-modal-trigger object-cover h-full w-full" 
                                                    data-image-url="{{ $play['poster'] }}">
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @php
                                    $ctr++;
                                @endphp
                            @endforeach
                        </div>
                        <!-- Slider indicators -->
                        <div class="absolute z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-5 left-1/2">
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
                            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
                        </div>
                        <!-- Slider controls -->
                        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>
                                <span class="sr-only">Previous</span>
                            </span>
                        </button>
                        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Next</span>
                            </span>
                        </button>
                    </div> --}}
                    <div class="poster-slide">
                        @php
                            $ctr = 0;
                        @endphp
                        @foreach ($plays as $play)
                            @if ($ctr < 6)
                                @if (isset($play['deleted_at']))
                                    @continue
                                @endif
                                    <img src="{{ $play['poster'] }}" alt="{{ $play['title'] }} Poster" class="cursor-pointer" data-image-url="{{ $play['poster'] }}" onclick="window.location='{{ route('toPlay', $play['id'] )}}';">
                            @endif
                            @php
                                $ctr++;
                            @endphp
                        @endforeach
                    </div>
                    <br>
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
                <img src="{{ $play['poster'] }}" alt="{{ $play['title'] }} Poster" style="width: 200px"
                    class="cursor-pointer image-modal-trigger" data-image-url="{{ $play['poster'] }}">
            </a>
            <p style="padding-left: 2.2vw">{{$play['title']}}</p>
        </div>
        @endforeach
    </div>
    <br>

</main>

<script>
    window.onload = () => {
  var posterSlide = document.querySelector('.poster-slide')
  var posterSlideImgs = document.querySelectorAll('.poster-slide img')

  posterSlideImgs.forEach(img => {
    img.addEventListener('mouseout', () => posterSlide.className = "poster-slide")
  })
  posterSlideImgs.forEach(img => {
    img.addEventListener('mouseover', () => posterSlide.className = "poster-slide hovered")
  })
}
</script>
@endsection
