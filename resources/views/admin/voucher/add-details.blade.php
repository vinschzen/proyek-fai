
@extends('layout.main')

@section('title')
@endsection

@section('content')
<body class="font-sans bg-gray-100">

  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">

      <div class="container mx-auto p-8">
        <nav class="mb-4">
          <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
              <a href="{{ route('toMasterVoucher') }}" class="text-blue-500">Master Voucher</a>
              <span class="mx-2">/</span>
            </li>
            <li class="flex items-center">
              <a href="{{ route('toAddVoucher') }}" class="text-blue-500">Add Voucher</a>
              <span class="mx-2">/</span>
            </li>
            <li class="flex items-center">
              <span class="text-gray-700">Add Voucher Details</span>
            </li>
          </ol>
        </nav>

        <h1 class="text-4xl mb-4">Add Voucher Details</h1>

        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2">Details :</label>
          <div class="border p-4 rounded-md">
              <div class="mb-2">
                  <label class="block text-gray-700 text-sm font-bold mb-2">Name : {{$vouchers['name']}}</label>
                  <label class="block text-gray-700 text-sm font-bold mb-2">Type : {{$vouchers['type']}}</label>
                  <label class="block text-gray-700 text-sm font-bold mb-2">Validation : {{$vouchers['validity_from']}} - {{$vouchers['validity_until']}}</label>
              </div>
          </div>
        </div>

        @if ( $vouchers['type'] == 'Ticket')
        <div class="grid grid-cols-2 gap-4">
              <form action="{{ route('voucher.store') }}" method="POST" class="bg-white p-4 rounded-lg shadow-md">
                @csrf
                <input type="hidden" name="name" value="{{$vouchers['name']}}">
                <input type="hidden" name="type" value="{{$vouchers['type']}}">
                <input type="hidden" name="validity_from" value="{{$vouchers['validity_from']}}">
                <input type="hidden" name="validity_until" value="{{$vouchers['validity_until']}}">
                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2">Criteria :</label>
                  <div class="border p-4 rounded-md">
                      <div class="mb-2">
                          <label class="block text-gray-700 text-sm font-bold mb-2">Ticket Amount Bought :</label>
                          <input type="number" name="ticket_amount" class="w-full p-2 border rounded-md" value="{{ old('ticket_amount') }}">
                          @error('ticket_amount') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                      </div>
                      <div>
                          <label class="block text-gray-700 text-sm font-bold mb-2">Specific Play : </label>
                          <input
                              type="text"
                              name="title"
                              id="title"
                              class="w-full p-2 border rounded-md"
                              placeholder="Search for a play"
                              oninput="updateSuggestions(this.value)"
                              value="{{ old('title') }}"
                          >
                          <input type="hidden" name="specific_play" id="play-id" value="{{ old('specific_play') }}">
                          <div id="suggestions" class="absolute bg-white mt-1 w-full rounded-md shadow-lg" style="display: none">
                            <ul class="border border-gray-300 max-h-40 overflow-y-auto">
                                  @foreach($tables as $title)
                                      <li class="cursor-pointer px-4 py-2 hover:bg-gray-100" onclick="selectSuggestion('{{ $title['title'] }} , {{ $title['id']}}')">{{ $title['title'] }}</li>
                                  @endforeach
                              </ul>
                          </div>
                          @error('specific_play') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

                          <label class="block text-gray-700 text-sm font-bold mb-2 p-1">
                            <input type="checkbox" name="any_play" id="anyPlaysCheckbox"> Any Play
                          </label>


                      </div>
                  </div>
                </div>

                <div class="mb-4">
                  <label for="discount" class="block text-gray-700 text-sm font-bold mb-2">discount %:</label>
                  <input type="number" name="discount" class="w-full p-2 border rounded-md" placeholder="Enter discount %" value="{{ old('discount') }}">
                  @error('discount') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                  <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Add Voucher</button>
                  </div>
              </form>
            </div>
          @else



          <div class="bg-white rounded p-4 shadow-md">
            <h3 class="text-xl font-semibold mb-2">Items</h3>

            <div class="col-2">
              <label class="block text-gray-700 text-sm font-bold mb-2">Select Concessions : </label>
                          <input
                              type="text"
                              name="title"
                              id="title"
                              class="w-full p-2 border rounded-md"
                              placeholder="Search for concession"
                              oninput="updateSuggestions(this.value)"
                              value="{{ old('title') }}"
                          >
                          <input type="hidden" name="specific_play" id="play-id" value="{{ old('specific_play') }}">
                          <div id="suggestions" class="absolute bg-white mt-1 w-full rounded-md shadow-lg" style="display: none">
                            <ul class="border border-gray-300 max-h-40 overflow-y-auto">
                                  @foreach($tables as $title)
                                      <li class="cursor-pointer px-4 py-2 hover:bg-gray-100" onclick="selectSuggestion('{{ $title['title'] }} , {{ $title['id']}}')">{{ $title['title'] }}</li>
                                  @endforeach
                              </ul>
                          </div>
                          @error('specific_play') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror

                          <button class="bg-purple-500 text-white p-2 m-2 rounded-md hover:bg-purple-800" onclick="addToTable('if-bought')">To If Bought</button>
                          <button class="bg-pink-500 text-white p-2 m-2 rounded-md hover:bg-pink-800" onclick="addToTable('then-get')"> To Then Get</button>
            </div>

            <form action="{{ route('voucher.store') }}" method="POST" class="bg-white p-4 rounded-lg shadow-md">
              @csrf
              <input type="hidden" name="name" value="{{$vouchers['name']}}">
              <input type="hidden" name="type" value="{{$vouchers['type']}}">
              <input type="hidden" name="validity_from" value="{{$vouchers['validity_from']}}">
              <input type="hidden" name="validity_until" value="{{$vouchers['validity_until']}}">

              <div class="grid grid-cols-2 gap-4">
                <div class="border p-4 rounded-md mb-4">
                  </table>
                  <label for="discount" class="text-gray-700 text-sm font-bold mb-2">If Bought :</label>
                  <ul id="if-bought">

                  </ul>
                  <button class="bg-gray-500 text-white p-2 rounded-md hover:bg-gray-800 float-right" onclick="clearTable('if-bought')" type="button"> Clear</button>

                </div>

                <div class="border p-4 rounded-md mb-4">
                  <label for="discount" class="block text-gray-700 text-sm font-bold mb-2">Then Get :</label>
                  <ul id="then-get">

                  </ul>
                  <button class="bg-gray-500 text-white p-2 rounded-md hover:bg-gray-800 float-right" onclick="clearTable('then-get')" type="button"> Clear</button>

                </div>
              </div>

              <div class="mb-4">
                <label for="discount" class="block text-gray-700 text-sm font-bold mb-2">discount %:</label>
                <input type="number" name="discount" class="w-full p-2 border rounded-md" placeholder="Enter discount %" value="{{ old('discount') }}">
                @error('discount') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
              </div>

              <div class="text-center">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Add Voucher</button>
              </div>
            </div>

          </form>

          @endif


          </div>

        </div>

      </div>

    </div>
  </div>

</body>

<script>
      document.addEventListener('DOMContentLoaded', function () {
        const anyPlaysCheckbox = document.getElementById('anyPlaysCheckbox');

        const specificInput = document.getElementById('title');

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

      const filteredSuggestions = @json($tables).filter(title =>
          title['title'].toLowerCase().includes(query.toLowerCase()) && !title['deleted_at']
      ).slice(0, 5);

      filteredSuggestions.forEach(title => {
          const li = document.createElement('li');
          li.textContent = title["title"];
          li.className = 'cursor-pointer px-4 py-2 hover:bg-gray-100';
          li.onclick = function() {
              selectSuggestion(title["title"], title["id"]);
          };
          suggestionsList.appendChild(li);
      });

      suggestionsContainer.style.display = filteredSuggestions.length > 0 ? 'block' : 'none';
  }

  function selectSuggestion(title, id) {
      document.getElementById('play-id').value = id;
      document.getElementById('title').value = title;
      document.getElementById('suggestions').style.display = 'none';
  }

  document.addEventListener('click', function(event) {
      const suggestionsContainer = document.getElementById('suggestions');
      if (event.target !== document.getElementById('title') && !suggestionsContainer.contains(event.target)) {
          suggestionsContainer.style.display = 'none';
      }
  });

  function addToTable(tablename) {
      const table = document.getElementById(tablename);

      const titleValue = document.getElementById('title').value;
      const idValue = document.getElementById('play-id').value;

      const existingLi = Array.from(table.getElementsByTagName('li')).find(li => li.dataset.id === idValue);

      if (existingLi) {
          const div = existingLi.querySelector('div');
          const span = existingLi.querySelector('span');
          const amountInput = div.querySelector('input[name="'+tablename+'-amount[]"]');
          const currentAmount = parseInt(amountInput.value, 10) || 0;
          amountInput.value = currentAmount + 1;

          span.textContent = titleValue + ' ' + amountInput.value + 'x';
      } else {
          const displayLi = document.createElement('li');
          const div = document.createElement('div');
          const span = document.createElement('span');

          const idInput = document.createElement('input');
          idInput.type = 'hidden';
          idInput.name = tablename + '-id[]';
          idInput.value = idValue;
          div.appendChild(idInput);

          const amountInput = document.createElement('input');
          amountInput.type = 'hidden';
          amountInput.name = tablename + '-amount[]';
          amountInput.value = '1';
          div.appendChild(amountInput);

          displayLi.dataset.id = idValue;
          span.textContent = titleValue + ' 1x';
          displayLi.appendChild(span);
          displayLi.appendChild(div);

          table.appendChild(displayLi);
      }
  }

  function clearTable(tablename) {
      const table = document.getElementById(tablename);

      table.innerHTML = "";
  }
</script>

@endsection
