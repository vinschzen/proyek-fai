@extends('layout.main')

@section('title')
@endsection

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css" rel="stylesheet">

<main class="container mx-auto mt-8">
    <section class="gradient-form h-full bg-neutral-200 dark:bg-neutral-700">
        <div class="container h-full p-10">
        <div
            class="g-6 flex h-full flex-wrap items-center justify-center text-neutral-800 dark:text-neutral-200">
            <div class="w-full">
            <div
                class="block rounded-lg bg-white shadow-lg dark:bg-neutral-800">
                <div class="g-0 lg:flex lg:flex-wrap">
                <div class="px-4 md:px-0 lg:w-6/12">
                    <div class="md:mx-6 md:p-12">

                    <div class="text-center">
                        <img
                        class="mx-auto w-48"
                        src="https://firebasestorage.googleapis.com/v0/b/proyek-fai-98bc0.appspot.com/o/assets%2Flogo-blue.png?alt=media&token=758787cc-e82d-4fb7-ae9d-563a88be4f28"
                        alt="logo" />
                        <h4 class="mb-12 mt-12 pb-1 text-xl font-semibold">
                        Welcome to TXT.com
                        </h4>
                    </div>
    
                    <form method="POST" action="{{ route('login') }}" >
                        @csrf
                        <p class="mb-4">Please login to your account</p>
                        <div class="relative mb-4" data-te-input-wrapper-init>
                        <input
                            type="text"
                            name="email"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="exampleFormControlInput1"
                            placeholder="Username" />
                        </div>
    
                        <div class="relative mb-4" data-te-input-wrapper-init>
                        <input
                            type="password"
                            name="password"
                            class="peer block min-h-[auto] w-full rounded border-0 bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:placeholder:text-neutral-200 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            id="exampleFormControlInput11"
                            placeholder="Password" />
                        </div>
    
                        <!--Submit button-->
                        <div class="mb-12 pb-1 pt-1 text-center">
                        <button
                            class="mb-3 inline-block w-full rounded px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_rgba(0,0,0,0.2)] transition duration-150 ease-in-out hover:shadow-[0_8px_9px_-4px_rgba(0,0,0,0.1),0_4px_18px_0_rgba(0,0,0,0.2)] focus:shadow-[0_8px_9px_-4px_rgba(0,0,0,0.1),0_4px_18px_0_rgba(0,0,0,0.2)] focus:outline-none focus:ring-0 active:shadow-[0_8px_9px_-4px_rgba(0,0,0,0.1),0_4px_18px_0_rgba(0,0,0,0.2)]
                            bg-gradient-to-r from-green-400 to-blue-500 hover:to-yellow-500
                            "
                            type="submit"
                            data-te-ripple-init
                            data-te-ripple-color="light"
                            >
                            Log in
                        </button>
    
                        </div>
    
                        <!--Register button-->
                        <div class="flex items-center justify-between pb-6">
                        Dont Have an Account? <a href="/register" style="color: blue">Register Here!</a>
                        </div>
                        <br>
                        Dont Have an Account? <a href="/register" style="color: blue">Register Here!</a>
                    </form>
                    </div>
                </div>
    
                <div
                    class="flex items-center rounded-b-lg lg:w-6/12 lg:rounded-r-lg lg:rounded-bl-none
                    "
                    style="background-image: url('https://firebasestorage.googleapis.com/v0/b/proyek-fai-98bc0.appspot.com/o/assets%2Flogin-poster.png?alt=media&token=7629ad66-bf2a-496a-b478-c52bf0f8d238');">
                    >
                    <div class="px-4 py-6 text-white md:mx-6 md:p-12" style="z: 10">
                        <h4 class="mb-6 text-2xl font-bold">
                            Your home for the latest in theatre !
                        </h4>
                        <p class="text-lg">
                            Discover the latest and greatest in theatre at your fingertips! Secure your tickets now for an unforgettable theatrical experience.
                            Whether you're a theatre enthusiast or just looking for a night out, we've got you covered. Log in to explore our diverse selection,
                            check your account details, and make your theatre nights extraordinary.
                        </p>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </section>
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
