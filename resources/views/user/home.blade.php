@extends('layout.main')

@section('title')
@endsection

@section('content')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Edwardian+Script+ITC&display=swap">
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
                <div class="max-w-5xl mx-auto">
                    <span class="cursor-default text-gray-200 font-semibold uppercase tracking-widest">NEW UPCOMING THEATRE</span>
                    <h2 class="cursor-default mt-8 mb-6 text-6xl lg:text-5xl text-gray-100" style="font-family: 'Edwardian Script ITC', cursive;">
                        We delight you in the upmost elegance, be enthralled, wondered and astonished.
                    </h2>
                    <p class="cursor-default max-w-3xl mx-auto mb-10 text-lg text-gray-300">
                        Witness all your favorite plays and discover more.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<main class="container mx-auto mt-8">

    <div class="text-center m-12">
        <h1 class="text-5xl font-semibold mb-4">Look through our catalogue</h1>
        <p class="w-3xl text-center p-4">Our vast collection of plays beckons you to the stage of imagination. Explore timeless classics and contemporary gems, each script carefully selected to captivate and inspire. Embark on a theatrical journey through our catalogue and discover the power of storytelling that transcends time and genre.</p>
    </div>

    <div class="max-w-4xl mx-auto">
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
    </div>

    <div class="inline-flex items-center justify-center w-full mt-12">
        <hr class="w-64 h-1 my-8 bg-gray-200 border-0 rounded dark:bg-gray-700">
        <div class="absolute px-4 -translate-x-1/2 bg-white left-1/2 dark:bg-gray-900">
            <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 14">
        <path d="M6 0H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3H2a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Zm10 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3h-1a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Z"/>
      </svg>
        </div>
    </div>

    <div class="m-12">
        <h1 class="text-3xl font-semibold mb-4">Now Playing</h1>
    </div>

    <div class="grid grid-cols-4 gap-10 justify-center">
        @foreach ($plays as $play)
            @if (isset($play['deleted_at']))
                @continue
            @endif
            <div class="flex flex-col items-center">
                <a href="{{ route('toPlay', $play['id'] )}}">
                    <img src="{{ $play['poster'] }}" alt="{{ $play['title'] }} Poster" style="width: 200px"
                        class="cursor-pointer image-modal-trigger" data-image-url="{{ $play['poster'] }}">
                </a>
                <p class="cursor-default text-center text-sm m-2">{{$play['title']}}</p>
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
