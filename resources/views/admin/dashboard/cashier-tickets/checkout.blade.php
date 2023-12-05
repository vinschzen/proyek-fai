@extends('layout.main')

@section('title', 'Theater Details')

@section('content')

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="">

      <div class="container mx-auto p-8">

        <ol class="list-none p-0 inline-flex">
          <li class="flex items-center">
            <a href="{{ route('toCashierTickets') }}" class="text-blue-500">Cashier Tickets</a>
            <span class="mx-2">/</span>
          </li>
          <li class="flex items-center">
            <span class="text-gray-700">Theater Details</span>
          </li>
        </ol>

        <h2 class="text-3xl font-semibold">Theater Details</h2>
          <p class="text-xl font-semibold">{{$play['title']}} {{" - Theater " . $schedule['theater']}}</p>
          <p class="text-gray-600 mb-4"> {{ $schedule['date'] }}   {{ $schedule['time_start'] . ' - ' . $schedule['time_end'] }}</p>

        <form action="{{ route('cashier.buytickets', $schedule['id']) }}" method="POST">
        <div class="grid grid-cols-2 gap-10">
          <div>
            <div class="grid grid-cols-12 gap-3">
                  @php
                      $seats = ['A','B','C','D','E', 'F', 'G', 'H']
                  @endphp
                  @foreach($seats as $seat)
                      @for ($i = 1; $i < 11; $i++)
                          @if (collect($seatings)->pluck('seat')->contains($seat . $i))
                            <div class="bg-red-200 p-2 rounded cursor-not-allowed w-10 h-10 hover:bg-red-300">
                              <p class="text-center text-gray-700">{{ $seat . $i }}</p>
                            </div>
                          @else
                            <div class="bg-gray-200 p-2 rounded cursor-pointer w-10 h-10 hover:bg-gray-300 seat">
                                <p class="text-center text-gray-700">{{ $seat . $i }}</p>
                                <input type="hidden" value name="seats[]">
                            </div>
                          @endif

                        @if ($i == 3 || $i == 7)
                          <div width="100px"></div>
                        @endif
                      @endfor
                  @endforeach
              </div>

            </div>


            <div>
              <div class="bg-white p-4 rounded-lg shadow-md">

                @csrf
                <h2 class="text-2xl font-semibold mb-8">User </h2>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username : </label>
                      <input
                          type="text"
                          name="username"
                          id="username"
                          class="w-full p-2 border rounded-md"
                          placeholder="Search for a User"
                          oninput="updateSuggestions(this.value)"
                          value="{{ old('username') }}"
                      >
                      <input type="hidden" name="specific_user" id="play-id" value="{{ old('specific_user') }}">
                      <div id="suggestions" class="absolute bg-white mt-1 w-full rounded-md shadow-lg" style="display: none">
                        <ul class="border border-gray-300 max-h-40 overflow-y-auto">
                              @foreach($tables as $user)
                                  <li class="cursor-pointer px-4 py-2 hover:bg-gray-100" onclick="selectSuggestion('{{ $user['displayName'] }} , {{ $user['uid']}}')">{{ $user['displayName'] }}</li>
                              @endforeach
                          </ul>
                      </div>
                      @error('specific_user') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                      @if (Session::has('msg'))
                          <span class="text-red-500 text-xs italic">{{ Session::get('msg'); }}</span>
                      @endif

                      <label class="block text-gray-700 text-sm font-bold mb-2 p-1">
                        <input type="checkbox" name="anonymous" id="anonymousCheckbox"> Anonymous
                      </label>
                </div>

                <div class="mb-4">
                  <div class="border p-4 rounded-md">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Voucher :</label>
                    <div class="mb-2" id="detail-list">
                      <input type="text" name="voucher"class="w-full p-2 border rounded-md" placeholder="Enter Voucher Code" value="{{ old('voucher') }}">
                      @if (Session::get('error'))
                      <p class="text-red-500 text-xs italic">{{ Session::get('error') }}</p>
                      @endif

                    </div>
                </div>
                </div>

                <div class="mb-4">
                  <div class="border p-4 rounded-md">
                      <label class="block text-gray-700 text-sm font-bold mb-2">Details :</label>
                      <div class="mb-2" id="detail-list">
                        <ul>
                          <li>
                          </li>
                        </ul>
                          <hr>
                          <label class="block text-gray-700 text-sm font-bold p-3">Total : <span  id="total-amount"></span></label>
                          <input type="hidden" name="total" id="total-amount-input">
                          @error('total') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

                          @error('seats')
                              <p class="text-red-500 text-xs italic">{{ $message }}</p>
                          @enderror
                      </div>
                  </div>
                </div>

                  <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-700">Order </button>
                  </div>
                </form>

              </div>

          </div>
        </div>


    </div>
  </div>

</body>

<script>

  $(document).ready(function () {
        $('.seat').on('click', function () {
            $(this).toggleClass('bg-yellow-300 bg-gray-200 hover:bg-yellow-500 hover:bg-gray-300');

            var seatValue = $(this).find('p').text();
            var hiddenInput = $(this).find('input[name="seats[]"]');
            hiddenInput.val(hiddenInput.val() === seatValue ? '' : seatValue);

            updateTakenSeatsList();
            updateTotalAmount();
        });

        function updateTakenSeatsList() {
            var takenSeats = [];
            $('.seat input[name="seats[]"]').each(function () {
                var seatValue = $(this).val();
                if (seatValue) {
                    takenSeats.push(seatValue);
                }
            });

            var takenSeatsList = takenSeats.map(function (seat) {
                return '<li>' + seat + ' - 5000 </li>';
            }).join('');

            $('#detail-list ul').html(takenSeatsList);
        }

        function updateTotalAmount() {
          var totalAmount = 0;

          $('.seat input[name="seats[]"]').each(function () {
              var seatValue = $(this).val();
              if (seatValue) {
                  totalAmount += 5000;
              }
          });

          // Update the total amount
          $('#total-amount').text( totalAmount);
          $('#total-amount-input').val(totalAmount);
      }
    });

  document.addEventListener('DOMContentLoaded', function () {
        const anyPlaysCheckbox = document.getElementById('anonymousCheckbox');

        const specificInput = document.getElementById('username');

        anyPlaysCheckbox.addEventListener('change', function () {
            if (anyPlaysCheckbox.checked) {
                specificInput.disabled = true;
            } else {
                specificInput.disabled = false;
            }
        });
    });

  function updateSuggestions(query) {
      const suggestionsContainer = document.getElementById('suggestions');
      const suggestionsList = suggestionsContainer.querySelector('ul');
      suggestionsList.innerHTML = '';

      const filteredSuggestions = @json($tables).filter(username =>
        username['displayName'].toLowerCase().includes(query.toLowerCase())
      ).slice(0, 5);

      filteredSuggestions.forEach(username => {
          const li = document.createElement('li');
          li.textContent = username["displayName"];
          li.className = 'cursor-pointer px-4 py-2 hover:bg-gray-100';
          li.onclick = function() {
              selectSuggestion(username["displayName"], username["uid"]);
          };
          suggestionsList.appendChild(li);
      });

      suggestionsContainer.style.display = filteredSuggestions.length > 0 ? 'block' : 'none';
  }

  function selectSuggestion(username, id) {
      document.getElementById('play-id').value = id;
      document.getElementById('username').value = username;
      document.getElementById('suggestions').style.display = 'none';
  }

  document.addEventListener('click', function(event) {
      const suggestionsContainer = document.getElementById('suggestions');
      if (event.target !== document.getElementById('username') && !suggestionsContainer.contains(event.target)) {
          suggestionsContainer.style.display = 'none';
      }
  });
</script>
@endsection
