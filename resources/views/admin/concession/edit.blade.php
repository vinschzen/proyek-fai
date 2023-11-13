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
              <a href="{{ route('toMasterPlay') }}" class="text-blue-500">Master Play</a>
              <span class="mx-2">/</span>
            </li>
            <li class="flex items-center">
              <span class="text-gray-700">Edit Play</span>
            </li>
          </ol>
        </nav>
        
        <h1 class="text-4xl mb-4">Edit Play</h1>

          <form action="{{ route('plays.edit', $play['id']) }}" method="POST" class="max-w-lg bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
              <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
              <input type="text" name="title" id="title" class="w-full p-2 border rounded-md" placeholder="Enter Title" value="{{ old('title') ?? $play['title'] }}">
              @error('title') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
  
            <div class="mb-4">
              <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
              <textarea name="description" id="description" class="w-full p-2 border rounded-md" placeholder="Enter Description">{{ old('description') ?? $play['description']  }}</textarea>
               @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
  
            <div class="mb-4">
              <label for="duration" class="block text-gray-700 text-sm font-bold mb-2">Duration: (minutes)</label>
              <input type="text" name="duration" id="duration" class="w-full p-2 border rounded-md" placeholder="Enter Duration" value="{{ old('duration') ?? $play['duration']}}">
              @error('duration') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
  
            <div class="mb-4">
              <label for="age_rating" class="block text-gray-700 text-sm font-bold mb-2">Age Rating:</label>
              <select name="age_rating" id="age_rating" class="w-full p-2 border rounded-md">
                <option value="G" @if (old('age_rating') ?? $play['age_rating'] == "G") selected @endif>General (G)</option>
                <option value="PG" @if (old('age_rating') ?? $play['age_rating'] == "PG") selected @endif>Parental Guidance (PG)</option>
                <option value="PG-13" @if (old('age_rating') ?? $play['age_rating'] == "PG-13") selected @endif>Parents Strongly Cautioned (PG-13)</option>
                <option value="R" @if (old('age_rating') ?? $play['age_rating'] == "R") selected @endif>Restricted (R)</option>
                <option value="NC-17" @if (old('age_rating') ?? $play['age_rating'] == "NC-17") selected @endif>Adults Only (NC-17)</option>
              </select>
            </div>
  
            <div class="mb-4">
              <h2 class="text-2xl mb-2">Cast Details</h2>
              <div class="mb-2">
                <label for="director" class="block text-gray-700 text-sm font-bold mb-2">Director:</label>
                <input type="text" name="director" id="director" class="w-full p-2 border rounded-md" placeholder="Enter Director's Name" value="{{ old('director') ?? $play['director']}}"">
                @error('director') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
              </div>
  
              <div id="casts-container">
                <div class="mb-2">
                  <label for="casts[]" class="block text-gray-700 text-sm font-bold mb-2">Cast Member:</label>
                      @php
                          $casts = old('casts') ?? $play['casts'];
                      @endphp
                      
                      @if ($casts)
                        @foreach($casts as $index => $cast)
                            <div class="flex items-center mb-2" name="container-cast-inputs">
                                <input type="text" name="casts[]" class="w-full p-2 border rounded-md" placeholder="Enter Cast Member's Name" value="{{ $cast }}">
                                
                                @if (count($casts) > 1)
                                    <button type="button" onclick="removeCastInput(this)" class="ml-2 bg-red-500 text-white p-2 rounded-md" name="remove-button">Remove</button>
                                @endif
                                
                              </div>
                              @error('casts.' . $index)
                                  <p class="text-red-500 text-xs italic">{{ $message }}</p>
                              @enderror
                        @endforeach
                    @else
                      <input type="text" name="casts[]" class="w-full p-2 border rounded-md" placeholder="Enter Cast Member's Name" >
                    @endif
                </div>
              </div>

            <button type="button" onclick="addCastInput()" class="bg-blue-500 text-white p-2 rounded-md mt-2">Add Cast Member</button>
          </div>

          <div class="text-center">
            <button type="submit" class="bg-yellow-500 text-white p-2 rounded-md">Edit Play</button>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>
<script>
  function addCastInput() {
    
      const castsContainer = document.getElementById('casts-container');
      const newCastInput = document.createElement('div');
      newCastInput.classList.add('flex');
      newCastInput.classList.add('items-center');
      newCastInput.classList.add('mb-2');
      newCastInput.innerHTML = `
        <input type="text" name="casts[]" class="w-full p-2 border rounded-md" placeholder="Enter Cast Member's Name">
        <button type="button" onclick="removeCastInput(this)" class="ml-2 bg-red-500 text-white p-2 rounded-md" name="remove-button">Remove</button>
      `;

      newCastInput.setAttribute("name", "container-cast-inputs"); 
      castsContainer.appendChild(newCastInput);

      
      var divsLeft = document.querySelectorAll('[name="container-cast-inputs"]');
      if (divsLeft.length == 2){
        document.querySelector('[name="container-cast-inputs"]').innerHTML += `<button type="button" onclick="removeCastInput(this)" class="ml-2 bg-red-500 text-white p-2 rounded-md" name="remove-button">Remove</button>`;
      }
  }

  function removeCastInput(button) {
        const parentDiv = button.parentNode;
        parentDiv.parentNode.removeChild(parentDiv);

        var divsLeft = document.querySelectorAll('[name="container-cast-inputs"]');
        if (divsLeft.length == 1) {
            document.querySelector('[name="remove-button"]').remove();
        }
     
    }
</script>
@endsection
