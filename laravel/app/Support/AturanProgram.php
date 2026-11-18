<?php

namespace App\Support;

/*
 * TASK 02 - Aturan validasi Program (dipakai bersama oleh web dan API)
 *
 * Validation memastikan data dari pengguna memenuhi aturan SEBELUM disimpan ke database.
 * Aturan dan pesan ditulis di satu tempat ini supaya form web dan REST API
 * memakai aturan yang sama persis (tidak perlu menulis dua kali).
 *
 * Aturan yang dipakai:
 *   required  : wajib diisi
 *   string    : harus teks
 *   max:100   : maksimal 100 karakter (untuk teks) / nilai maksimal (untuk angka)
 *   integer   : harus bilangan bulat
 *   min:0     : tidak boleh negatif
 *   in:...    : harus salah satu dari daftar
 *   sometimes : hanya diperiksa kalau field-nya dikirim (supaya API boleh mengirim
 *               hanya "nama" dan "deskripsi" seperti contoh di materi; kolom lain memakai nilai bawaan)
 */
class AturanProgram
{
    public const KATEGORI = ['Pakaian', 'Kardus', 'Buku', 'Elektronik', 'Lainnya'];

    /**
     * @param array $input   data yang dikirim pengguna ($request->all())
     * @param int|null $targetLama target program yang sedang diedit (dipakai jika "target" tidak dikirim)
     */
    public static function rules(array $input = [], ?int $targetLama = null): array
    {
        // "tercapai" tidak boleh melebihi target
        $target = array_key_exists('target', $input) ? $input['target'] : $targetLama;
        $aturanTercapai = ['sometimes', 'required', 'integer', 'min:0'];
        if (is_numeric($target)) {
            $aturanTercapai[] = 'max:' . (int) $target;
        }

        return [
            'nama'      => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string'],
            'kategori'  => ['sometimes', 'required', 'in:' . implode(',', self::KATEGORI)],
            'lokasi'    => ['sometimes', 'required', 'string', 'max:50'],
            'foto'      => ['nullable', 'string', 'max:255'],
            'target'    => ['sometimes', 'required', 'integer', 'min:0'],
            'tercapai'  => $aturanTercapai,
        ];
    }

    // Pesan kesalahan dalam bahasa Indonesia
    public static function messages(): array
    {
        return [
            'nama.required'      => 'Nama wajib diisi.',
            'nama.string'        => 'Nama harus berupa teks.',
            'nama.max'           => 'Nama maksimal 100 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string'   => 'Deskripsi harus berupa teks.',
            'kategori.required'  => 'Kategori wajib dipilih.',
            'kategori.in'        => 'Kategori harus salah satu dari: ' . implode(', ', self::KATEGORI) . '.',
            'lokasi.required'    => 'Lokasi wajib diisi.',
            'lokasi.max'         => 'Lokasi maksimal 50 karakter.',
            'foto.max'           => 'Path foto maksimal 255 karakter.',
            'target.required'    => 'Target wajib diisi.',
            'target.integer'     => 'Target harus berupa angka bulat.',
            'target.min'         => 'Target tidak boleh negatif.',
            'tercapai.required'  => 'Jumlah tersalurkan wajib diisi.',
            'tercapai.integer'   => 'Jumlah tersalurkan harus berupa angka bulat.',
            'tercapai.min'       => 'Jumlah tersalurkan tidak boleh negatif.',
            'tercapai.max'       => 'Jumlah tersalurkan tidak boleh lebih besar dari target.',
        ];
    }
}
