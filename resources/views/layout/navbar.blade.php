
  <header class="bg-blue-500 text-white p-4">
      <div class="container mx-auto flex justify-between items-center">
          <h1 class="text-2xl font-semibold">TXT.com</h1>
          <nav>
              <ul class="flex space-x-4">
                  <li><a href="{{ route('toHome')}}" class="text-white">Home</a></li>
                  <li><a href="{{ route('toConcessions')}}" class="text-white">Concessions</a></li>
                  @if (Session::get('user'))
                  <li><a href="{{ route('toSaldo')}}" class="text-white">Saldo : <b>{{ Session::get('user')->customClaims['saldo'] }}</b></a></li>
                  <li><a href="{{ route('toProfile')}}" class="text-white">{{ Session::get('user')->displayName }}</a></li>
                  <form action="{{ route('logout')}}" method="post">
                    @csrf
                    <button type="submit"><a class="text-white">Logout</a></button>
                  </form>
                  <a href="{{ route('toDashboard')}}" class="text-white">Admin</a>
                  @else
                  <li><a href="{{ route('toLogin')}}" class="text-white">Login</a></li>
                  @endif
              </ul>
          </nav>
      </div>
  </header>