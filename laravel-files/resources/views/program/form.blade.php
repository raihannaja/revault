@extends('layouts.app')

{{-- Satu form dipakai dua kali: tambah (program baru) dan edit (program sudah ada). --}}
@php($edit = $program->exists)

@section('judul', $edit ? 'Edit Program' : 'Tambah Program')

@section('isi')
  <a href="{{ route('program.index') }}" class="text-sm text-forest hover:underline">&larr; Kembali ke daftar program</a>
  <h1 class="font-display text-3xl font-bold mt-2 mb-6">{{ $edit ? 'Edit Program' : 'Tambah Program' }}</h1>

  <form action="{{ $edit ? route('program.update', $program) : route('program.store') }}" method="POST"
        class="rounded-xl bg-white p-5 sm:p-6 shadow-sm space-y-5 max-w-2xl">
    @csrf
    @if ($edit)
      @method('PUT')
    @endif

    <div>
      <label for="nama" class="block text-sm font-bold mb-1">Nama Program</label>
      <input id="nama" name="nama" type="text" value="{{ old('nama', $program->nama) }}" required maxlength="100"
             class="w-full rounded-lg border border-ink/20 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-forest">
      @error('nama') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>

    <div>
      <label for="deskripsi" class="block text-sm font-bold mb-1">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi" rows="4" required
                class="w-full rounded-lg border border-ink/20 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-forest">{{ old('deskripsi', $program->deskripsi) }}</textarea>
      @error('deskripsi') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
      <div>
        <label for="kategori" class="block text-sm font-bold mb-1">Kategori</label>
        <select id="kategori" name="kategori"
                class="w-full rounded-lg border border-ink/20 px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-forest">
          @foreach ($kategori as $k)
            <option value="{{ $k }}" @selected(old('kategori', $program->kategori) === $k)>{{ $k }}</option>
          @endforeach
        </select>
        @error('kategori') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
      </div>
      <div>
        <label for="lokasi" class="block text-sm font-bold mb-1">Lokasi</label>
        <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi', $program->lokasi) }}" required maxlength="50"
               class="w-full rounded-lg border border-ink/20 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-forest">
        @error('lokasi') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
      </div>
      <div>
        <label for="target" class="block text-sm font-bold mb-1">Target (barang)</label>
        <input id="target" name="target" type="number" min="0" value="{{ old('target', $program->target) }}" required
               class="w-full rounded-lg border border-ink/20 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-forest">
        @error('target') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
      </div>
      <div>
        <label for="tercapai" class="block text-sm font-bold mb-1">Sudah tersalurkan (barang)</label>
        <input id="tercapai" name="tercapai" type="number" min="0" value="{{ old('tercapai', $program->tercapai) }}" required
               class="w-full rounded-lg border border-ink/20 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-forest">
        <p class="mt-1 text-xs text-ink/60">Tidak boleh lebih besar dari target. Status program dihitung otomatis dari dua angka ini.</p>
        @error('tercapai') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
      </div>
    </div>

    <div>
      <label for="foto" class="block text-sm font-bold mb-1">Foto</label>
      <div class="flex items-center gap-4">
        <select id="foto" name="foto"
                class="flex-1 rounded-lg border border-ink/20 px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-forest">
          <option value="">(tanpa foto)</option>
          @foreach ($fotos as $f)
            <option value="{{ $f }}" @selected(old('foto', $program->foto) === $f)>{{ $f }}</option>
          @endforeach
        </select>
        <img id="previewFoto" alt="Pratinjau foto" class="h-16 w-16 rounded-lg object-cover bg-mist hidden">
      </div>
      @error('foto') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
      <button type="submit" class="rounded-lg bg-forest px-5 py-2.5 text-sm font-bold text-white hover:bg-forest-dark transition">
        {{ $edit ? 'Update' : 'Simpan' }}
      </button>
      <a href="{{ route('program.index') }}" class="rounded-lg bg-ink/10 px-5 py-2.5 text-sm font-bold hover:bg-ink/15 transition">Batal</a>
    </div>
  </form>
@endsection

@push('script')
<script>
  // Pratinjau foto: event "change" pada <select> mengubah atribut src gambar (DOM)
  var pilih = document.getElementById('foto');
  var gambar = document.getElementById('previewFoto');
  var basis = @json(asset(''));
  function tampilkan() {
    if (pilih.value) {
      gambar.src = basis.replace(/\/$/, '') + '/' + pilih.value;
      gambar.classList.remove('hidden');
    } else {
      gambar.classList.add('hidden');
    }
  }
  pilih.addEventListener('change', tampilkan);
  tampilkan();
</script>
@endpush
