@extends('layout.main')

@section('title')
@endsection

@section('content')

<body class="font-sans bg-gray-100">

    <div class="flex">
        @include('layout.admin-side')
        <div class="flex-1">

            <div class="container mx-auto p-8">
                <h1 class="text-4xl mb-4">Welcome, Admin</h1>
            </div>

        </div>
    </div>

</body>
@endsection
