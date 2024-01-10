<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet" />

<style>
     a {
        font-weight: medium;
        transition: all 0.2s ease-in-out; 
        letter-spacing: 0.3px; 
    }

    a:hover {
        /* text-decoration: underline; */
        font-weight: bold;
        /* letter-spacing: 2px;  */
    }
</style>


<header class="bg-blue-700 text-white p-4">

    <div class="container mx-auto flex justify-between">
        <div class="flex items-center">
            <img src="https://firebasestorage.googleapis.com/v0/b/proyek-fai-98bc0.appspot.com/o/assets%2Flogo-tiket.png?alt=media&token=a0ece034-392a-40ed-b110-b608c0432f03" alt="" style="width: 2vw">
            <h1 class="text-2xl font-semibold" style="padding-left: 2vw">
                TXT.com
            </h1>
        </div>

        <nav class="flex items-center">
            <ul class="flex space-x-4 items-center">
                <li>
                    <a href="{{ route('toHome')}}"  class="text-white group hover:font-bold">
                        Home
                    </a>
                </li>
                @if (Session::get('user'))
                <li><a href="{{ route('toSaldo')}}" class="text-white group hover:font-bold">Saldo : <b>@rupiah(
                            Session::get('user')->customClaims['saldo']) </b></a></li>
                <li><a href="{{ route('toProfile')}}" class="text-white group hover:font-bold">Profile</a></li>
                <form action="{{ route('logout')}}" method="post">
                    @csrf
                    <button type="submit"><a class="text-white group hover:font-bold">Logout</a></button>
                </form>
                @if (Session::get('user')->customClaims['role'] != 0)
                <a href="{{ route('toDashboard')}}">
                    <div style="display: flex; justify-content: flex-end; align-items: center; padding-right: 30px">
                        <img src="https://cdn0.iconfinder.com/data/icons/octicons/1024/dashboard-512.png" style="width: 20px; margin-right: 5px;">
                        Dashboard
                    </div>
                </a>
                @endif

                @else
                <li><a href="{{ route('toLogin')}}" class="hover:text-white group hover:font-bold">Login</a></li>
                @endif
            </ul>
        </nav>


    </div>
</header>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
