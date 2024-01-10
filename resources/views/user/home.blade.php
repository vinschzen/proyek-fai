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
                    <br> <br>
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

    <div class="text-center">
        <h1 class="text-3xl font-semibold mb-4">Now Playing</h1>
    </div>
    <br>

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
                <p class="text-center">{{$play['title']}}</p>
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
