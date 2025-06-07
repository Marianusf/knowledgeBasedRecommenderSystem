<h2>Data HP</h2>
<a href="{{ route('admin.phones.create') }}">+ Tambah HP</a>

@foreach ($phones as $hp)
    <div>
        <b>{{ $hp->company_name }} {{ $hp->model }}</b>
        ({{ $hp->launched_year }})
        - {{ $hp->ram }}GB / {{ $hp->battery_capacity }}mAh
        <a href="{{ route('admin.phones.edit', $hp) }}">Edit</a>
        <form action="{{ route('admin.phones.destroy', $hp) }}" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit">Hapus</button>
        </form>
    </div>
@endforeach
