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
              <a href="{{ route('toMasterSchedule') }}" class="text-blue-500">Master Schedule</a>
              <span class="mx-2">/</span>
            </li>
            <li class="flex items-center">
              <span class="text-gray-700">Add Schedule</span>
            </li>
          </ol>
        </nav>

        <h1 class="text-4xl mb-4">Add Schedule</h1>

        <form action="{{ route('schedule.store') }}" method="POST" class="max-w-lg bg-white p-6 rounded-lg shadow-md">
          @csrf
          <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Play:</label>
            <input
                type="text"
                name="title"
                id="title"
                class="w-full p-2 border rounded-md"
                placeholder="Search for a play"
                oninput="updateSuggestions(this.value)"
                value="{{ old('title') }}"
            >
            <input type="hidden" name="playid" id="play-id" value="{{ old('playid') }}">
            <div id="suggestions" class="absolute bg-white mt-1 w-full rounded-md shadow-lg" style="display: none">
              <ul class="border border-gray-300 max-h-40 overflow-y-auto">
                    @foreach($plays as $title) 
                        <li class="cursor-pointer px-4 py-2 hover:bg-gray-100" onclick="selectSuggestion('{{ $title['title'] }} , {{ $title['id']}}')">{{ $title['title'] }}</li>
                    @endforeach
                </ul>
            </div>
            @error('play-id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
          </div>


          <div class="mb-4">
            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
            <input type="date" name="date" id="date" class="w-full p-2 border rounded-md" value="{{ old('date') }}">
            @error('date') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
        </div>
    
        <div class="mb-4">
            <label for="time" class="block text-gray-700 text-sm font-bold mb-2">Time:</label>
            <input type="text" name="time" id="time" class="w-full p-2 border rounded-md" placeholder="Enter Time (HH:MM)" pattern="^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$" value="{{ old('time') }}">
            @error('time') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            @if (Session::get('error'))
              <p class="text-red-500 text-xs italic">{{ Session::get('error') }}</p>
            @endif
        </div>

          <div class="mb-4">
            <label for="theater" class="block text-gray-700 text-sm font-bold mb-2">Theater:</label>
            <select name="theater" id="theater" class="w-full p-2 border rounded-md">
              <option value="A" @if (old('theater') == "A") selected @endif>Theater A</option>
              <option value="B" @if (old('theater') == "B") selected @endif>Theater B</option>
              <option value="C" @if (old('theater') == "C") selected @endif>Theater C</option>
            </select>
          </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded-md">Add Schedule</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>

<script>
    function updateSuggestions(query) {
        const suggestionsContainer = document.getElementById('suggestions');
        const suggestionsList = suggestionsContainer.querySelector('ul');
        suggestionsList.innerHTML = '';

        const filteredSuggestions = @json($plays).filter(title =>
            title['title'].toLowerCase().includes(query.toLowerCase())
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
</script>

@endsection
