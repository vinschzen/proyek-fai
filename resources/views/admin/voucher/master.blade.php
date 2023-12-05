@extends('layout.main')

@section('title')
@endsection

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.9.0/dist/sweetalert2.min.css" rel="stylesheet">

<body class="font-sans bg-gray-100">
  <div class="flex">
    @include('layout.admin-side')
    <div class="flex-1">
      <div class="container mx-auto p-8">
        <h1 class="text-4xl mb-4">Master Voucher</h1>


        @if(Session::has('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="flex mb-4">
            <form method="GET" action="{{ route('toMasterVoucher') }}">
              <input type="text" class="p-2 border border-gray-300 rounded" name="search" placeholder="Search by Name" value="{{ request('search') }}">
              <select class="p-2 ml-2 border border-gray-300 rounded" name="filter">
                <option value="newest" @if(request('filter') === 'newest') selected @endif>Newest</option>
                <option value="oldest" @if(request('filter') === 'oldest') selected @endif>Oldest</option>
              </select>
              <button class="ml-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-700" type="submit">Apply Filters</button>
          </form>

            <a href="{{ route('toAddVoucher') }}" class="ml-auto bg-green-500 text-white p-2 rounded hover:bg-green-700">Add Voucher</a>
        </div>

        <table class="w-full border border-collapse border-gray-300 mb-4">
          <thead>
            <tr>
              <th class="p-3 border-b text-left">No</th>
              <th class="p-3 border-b text-left">Name</th>
              <th class="p-3 border-b text-left">Type</th>
              <th class="p-3 border-b text-left">Validity Period</th>
              <th class="p-3 border-b text-left">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($vouchers as $voucher)
                @if (isset($voucher['deleted_at']))
                    @continue
                @endif
                <tr class="hover:bg-gray-100 transition duration-300 ease-in-out hover:bg-gray-200">
                    <td class="p-3 border-b text-left">{{ $loop->iteration }}.</td>
                    <td class="p-3 border-b text-left">{{ $voucher['name'] }}</td>
                    <td class="p-3 border-b text-left">{{ $voucher['type'] }}</td>
                    <td class="p-3 border-b text-left">{{ $voucher['validity_from'] }} - {{ $voucher['validity_until'] }} </td>
                    <td class="p-3 border-b text-left">
                      <a href="{{ route('toEditVoucher', $voucher['id']) }}" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700" style="margin-right: 10px">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                      <form id="deleteForm_{{ $voucher['id'] }}" action="{{ route('voucher.destroy', $voucher['id']) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                      </form>
                      <button onclick="confirmDelete('{{ $voucher['id'] }}')" class="bg-red-500 text-white p-2 rounded hover:bg-red-700">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>

        {{ $vouchers->links() }}

      </div>
    </div>
  </div>

</body>

<script>
  function confirmDelete(voucherId) {

          Swal.fire({
              title: 'Are you sure?',
              text: 'You won\'t be able to revert this!',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
              if (result.isConfirmed) {
                  document.getElementById('deleteForm_' + voucherId).submit();
              }
          });

  }
</script>

@endsection
