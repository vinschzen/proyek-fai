<div class="bg-gray-800 text-white w-1/5 p-8" id="admin-sidebar">
  <h2 class="text-2xl mb-4">Dashboard</h2>
  <ul>
      <li><a href="{{ route('toDashboard') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Home</a></li>
      <li><a href="{{ route('toCashierTickets') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Cashier Tickets</a></li>
      <li><a href="{{ route('toCashierConcessions') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Cashier Concessions</a></li>
  </ul>
  @if (Session::get('user')->customClaims['role'] > 1)
    <h2 class="text-2xl mb-4"><hr></h2>
    <h2 class="text-2xl mb-4">Masters</h2>
    <ul>
        <li><a href="{{ route('toMasterUser') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Master User</a></li>
        <li><a href="{{ route('toMasterPlay') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Master Play</a></li>
        <li><a href="{{ route('toMasterSchedule') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Master Schedule</a></li>
        <li><a href="{{ route('toMasterConcession') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Master Concession</a></li>
        <li><a href="{{ route('toMasterVoucher') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Master Voucher</a></li>
    </ul>
    <h2 class="text-2xl mb-4"><hr></h2>
    <h2 class="text-2xl mb-4">History</h2>
    <ul>
        <li><a href="{{ route('viewtickets') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Tickets</a></li>
        <li><a href="{{ route('viewseatings') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Seatings</a></li>
        <li><a href="{{ route('viewconcessions') }}" class="block py-2 transition duration-300 ease-in-out hover:bg-gray-700">Concessions</a></li>
    </ul>
    @endif
    </div>
