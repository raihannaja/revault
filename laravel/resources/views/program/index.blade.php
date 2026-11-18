@extends('layouts.app')

@section('judul', 'Program Revault')

@section('isi')
  {{-- VIEW: hanya menampilkan data yang dikirim Controller ($programs, $totalTarget, $totalTercapai) --}}
  <div class="flex flex-wrap items-end justify-between gap-4 mb-6">
    <div>
      <h1 class="font-display text-3xl font-bold">Program Revault</h1>
      <p class="text-ink/70 mt-1">Semua data di bawah dibaca dari tabel <code class="bg-white px-1.5 py-0.5 rounded">programs</code> di database.</p>
    </div>
    <a href="{{ route('program.create') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-forest px-4 py-2.5 text-sm font-bold text-white hover:bg-forest-dark transition">
      + Tambah Program
    </a>
  </div>

  @if (session('sukses'))
    <div role="status" class="mb-6 rounded-lg border border-forest/20 bg-forest-soft px-4 py-3 text-forest font-medium">
      {{ session('sukses') }}
    </div>
  @endif

  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="rounded-xl bg-white p-4 shadow-sm">
      <p class="text-sm text-ink/60">Jumlah program</p>
      <p class="font-display text-2xl font-bold">{{ $programs->count() }}</p>
    </div>
    <div class="rounded-xl bg-white p-4 shadow-sm">
      <p class="text-sm text-ink/60">Barang tersalurkan</p>
      <p class="font-display text-2xl font-bold">{{ $totalTercapai }}</p>
    </div>
    <div class="rounded-xl bg-white p-4 shadow-sm">
      <p class="text-sm text-ink/60">Total target</p>
      <p class="font-display text-2xl font-bold">{{ $totalTarget }}</p>
    </div>
  </div>

  <div class="rounded-xl bg-white shadow-sm overflow-x-auto">
    <table class="w-full text-left text-sm">
      <thead class="bg-forest-soft text-forest">
        <tr>
          <th class="px-4 py-3 font-bold">No</th>
          <th class="px-4 py-3 font-bold">Program</th>
          <th class="px-4 py-3 font-bold">Deskripsi</th>
          <th class="px-4 py-3 font-bold">Progres</th>
          <th class="px-4 py-3 font-bold text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-ink/10">
        {{-- LOOP: satu baris untuk setiap program dari database --}}
        @forelse ($programs as $program)
          <tr class="align-top">
            <td class="px-4 py-4 text-ink/60">{{ $loop->iteration }}</td>
            <td class="px-4 py-4 min-w-[14rem]">
              <div class="flex items-center gap-3">
                @if ($program->foto)
                  <img src="{{ asset($program->foto) }}" alt="Foto {{ $program->nama }}"
                       class="h-12 w-12 rounded-lg object-cover bg-mist" loading="lazy">
                @endif
                <div>
                  <p class="font-bold">{{ $program->nama }}</p>
                  <p class="text-xs text-ink/60">{{ $program->kategori }} · {{ $program->lokasi }}</p>
                </div>
              </div>
            </td>
            <td class="px-4 py-4 min-w-[16rem] text-ink/80">{{ \Illuminate\Support\Str::limit($program->deskripsi, 90) }}</td>
            <td class="px-4 py-4 min-w-[11rem]">
              {{-- CONDITIONAL: badge dan warna bar ditentukan oleh status dari Model --}}
              <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-bold {{ $program->badge[0] }}">{{ $program->badge[1] }}</span>
              <div class="mt-2 h-2 w-full rounded-full bg-ink/10" role="progressbar"
                   aria-valuenow="{{ $program->persen }}" aria-valuemin="0" aria-valuemax="100">
                <div class="h-2 rounded-full {{ $program->badge[2] }}" style="width: {{ $program->persen }}%"></div>
              </div>
              <p class="mt-1 text-xs text-ink/60">{{ $program->tercapai }} / {{ $program->target }} barang ({{ $program->persen }}%)</p>
            </td>
            <td class="px-4 py-4">
              <div class="flex justify-end gap-2">
                <a href="{{ route('program.edit', $program->id) }}"
                   class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-blue-700">Edit</a>
                <form action="{{ route('program.destroy', $program->id) }}" method="POST"
                      class="form-hapus" data-nama="{{ $program->nama }}">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-red-700">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-10 text-center text-ink/60">
              Belum ada program. Klik <strong>+ Tambah Program</strong> untuk membuat yang pertama.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection

@push('script')
<script>
  // Konfirmasi sebelum menghapus (event "submit" pada form hapus)
  document.querySelectorAll('.form-hapus').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!confirm('Yakin ingin menghapus program "' + form.dataset.nama + '"?')) {
        e.preventDefault();
      }
    });
  });
</script>
@endpush
