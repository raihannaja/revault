<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Support\AturanProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
 * PERTEMUAN 10 - Task 03: RESTful Program API
 *
 * Sama seperti ProgramController web, tetapi hasilnya JSON (bukan halaman HTML),
 * dan hasil request ditandai dengan HTTP status code:
 *
 *   GET    /api/programs        index    200 OK
 *   POST   /api/programs        store    201 Created  (422 jika tidak valid)
 *   GET    /api/programs/{id}   show     200 OK       (404 jika tidak ada)
 *   PUT    /api/programs/{id}   update   200 OK       (404 / 422)
 *   DELETE /api/programs/{id}   destroy  200 OK       (404 jika tidak ada)
 *
 * Alur: Client -> HTTP Request -> routes/api.php -> Controller -> Eloquent -> Database -> JSON Response
 *
 * Catatan: validasi memakai Validator::make() lalu mengembalikan JSON 422 secara manual,
 * supaya hasilnya selalu JSON walaupun client (Postman/Insomnia) tidak mengirim header
 * "Accept: application/json". Dengan $request->validate() biasa, validasi gagal bisa
 * berubah menjadi redirect (302) ke halaman HTML.
 */
class ProgramController extends Controller
{
    // GET /api/programs -> semua program
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Program::orderBy('id')->get(),
        ], 200);
    }

    // POST /api/programs -> buat program baru
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            AturanProgram::rules($request->all()),
            AturanProgram::messages()
        );

        if ($validator->fails()) {
            return $this->gagalValidasi($validator);
        }

        $program = Program::create($validator->validated());

        return response()->json([
            'message' => 'Program berhasil dibuat.',
            'data'    => $program,
        ], 201);
    }

    // GET /api/programs/{id} -> detail satu program
    public function show($id): JsonResponse
    {
        $program = Program::find($id);

        if (! $program) {
            return $this->tidakDitemukan();
        }

        return response()->json(['data' => $program], 200);
    }

    // PUT/PATCH /api/programs/{id} -> ubah program
    public function update(Request $request, $id): JsonResponse
    {
        $program = Program::find($id);

        if (! $program) {
            return $this->tidakDitemukan();
        }

        $validator = Validator::make(
            $request->all(),
            AturanProgram::rules($request->all(), $program->target),
            AturanProgram::messages()
        );

        if ($validator->fails()) {
            return $this->gagalValidasi($validator);
        }

        $program->update($validator->validated());

        return response()->json([
            'message' => 'Program berhasil diperbarui.',
            'data'    => $program,
        ], 200);
    }

    // DELETE /api/programs/{id} -> hapus program
    public function destroy($id): JsonResponse
    {
        $program = Program::find($id);

        if (! $program) {
            return $this->tidakDitemukan();
        }

        $program->delete();

        return response()->json(['message' => 'Program berhasil dihapus.'], 200);
    }

    // ---------------- bantuan (private) ----------------

    // 422 Unprocessable Content: data tidak memenuhi aturan validasi
    private function gagalValidasi($validator): JsonResponse
    {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors'  => $validator->errors(),
        ], 422);
    }

    // 404 Not Found: id tidak ada di database
    private function tidakDitemukan(): JsonResponse
    {
        return response()->json(['message' => 'Program tidak ditemukan.'], 404);
    }
}
