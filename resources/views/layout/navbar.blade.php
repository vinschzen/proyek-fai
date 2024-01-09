<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />

@if (Session::get('user'))
    @if (Session::get('user')->customClaims['role'] != 0)
    <a href="{{ route('toDashboard')}}">
        <div style="display: flex; justify-content: flex-end; align-items: center; padding-right: 30px">
            <img src="https://cdn0.iconfinder.com/data/icons/octicons/1024/dashboard-512.png" style="width: 20px; margin-right: 5px;">
            Dashboard
        </div>
    </a>
    @endif
@endif

<header class="bg-blue-700 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-semibold">TXT.com</h1>
        <nav>
            <ul class="flex space-x-4 items-center">
                <a href="{{ route('toHome')}}" class="text-white transition duration-300 ease-in-out hover:font-bold">
                    Home
                </a>
                @if (Session::get('user'))
                <li><a href="{{ route('toSaldo')}}" class="text-white">Saldo : <b> @rupiah(
                            Session::get('user')->customClaims['saldo'] ) </b></a></li>
                <li><a href="{{ route('toProfile')}}" class="text-white">Profile</a>
                </li>
                <form action="{{ route('logout')}}" method="post">
                    @csrf
                    <button type="submit"><a class="text-white">Logout</a></button>
                </form>
                @else
                <li><a href="{{ route('toLogin')}}" class="text-white">Login</a></li>
                @endif
            </ul>
        </nav>
    </div>
</header>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
