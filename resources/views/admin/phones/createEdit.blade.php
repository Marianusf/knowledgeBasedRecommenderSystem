<form method="POST" action="{{ isset($phone) ? route('admin.phones.update', $phone) : route('admin.phones.store') }}"
    enctype="multipart/form-data">
    @csrf
    @if (isset($phone))
        @method('PUT')
    @endif

    <input name="model" value="{{ old('model', $phone->model ?? '') }}" placeholder="Model">
    <input name="company_name" value="{{ old('company_name', $phone->company_name ?? '') }}" placeholder="Brand">
    <input name="price" value="{{ old('price', $phone->price ?? '') }}" placeholder="Harga">
    <input name="launched_year" value="{{ old('launched_year', $phone->launched_year ?? '') }}"
        placeholder="Tahun Rilis">
    <input name="ram" value="{{ old('ram', $phone->ram ?? '') }}" placeholder="RAM">
    <input name="battery_capacity" value="{{ old('battery_capacity', $phone->battery_capacity ?? '') }}"
        placeholder="Baterai">
    <input type="file" name="image">

    <button type="submit">Simpan</button>
</form>
