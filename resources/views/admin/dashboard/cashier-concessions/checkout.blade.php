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
            <a href="{{ route('toCashierConcessions') }}" class="text-blue-500">Cashier Concessions</a>
            <span class="mx-2">/</span>
          </li>
          <li class="flex items-center">
            <span class="text-gray-700">Concessions Checkout</span>
          </li>
        </ol>

        <h2 class="text-3xl font-semibold">Concessions Checkout</h2>

        <form action="{{ route('cashier.buyconcessions')}}" method="POST">
        <div class="grid grid-cols-2">
              <div class="p-10">
                <table class="w-full border border-collapse border-gray-300 mb-4">
                  <thead>
                    <tr>
                      <th class="p-3 border-b text-left">No</th>
                      <th class="p-3 border-b text-left">Image</th>
                      <th class="p-3 border-b text-left">Name</th>
                      <th class="p-3 border-b text-left">Price</th>
                      <th class="p-3 border-b text-left">Qty</th>
                      <th class="p-3 border-b text-left">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $total = 0;
                    @endphp
                    @foreach (Session::get('cashier') ?? [] as $concession)
                        <tr class="hover:bg-gray-100 transition duration-300 ease-in-out hover:bg-gray-200">
                            <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                            <td class="p-3 border-b text-left"><img src="{{ asset('storage/' . $concession['image']) }}" alt="{{ $concession['name'] }} Image" style="width: 80px"></td>
                            <td class="p-3 border-b text-left">{{ $concession['name'] }}</td>
                            <td class="p-3 border-b text-left">{{ $concession['price'] }}</td>
                            <td class="p-3 border-b text-left">{{ $concession['qty'] }}</td>
                            <td class="p-3 border-b text-left">{{ $concession['price'] * $concession['qty'] }}</td>
                            <td class="p-3 border-b text-left">
                              <a href="{{ route('removeFromCart', $concession['id']) }}" class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Remove</a>
                            </td>
                            @php
                              $total += ($concession['price'] * $concession['qty']);
                            @endphp
                        </tr>
                    @endforeach
                        <tr>
                          <td colspan="3">
                          </td>
                          <td>
                            <td class="p-3 border-b font-bold text-left">
                              Total :
                            </td>
        
                          </td>
                          <td class="p-3 border-b font-bold text-left">
                            {{$total}}
                          </td>
                        </tr>
                  </tbody>
                </table>
              </div>
  

            <div class="p-10">
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
                  <input type="hidden" name="total" value="{{ $total }}">
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
