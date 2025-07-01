<?php

namespace App\Http\Controllers\API;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrganizationApiController extends Controller
{
    //
    public function index() { /* ... */ }
    public function show($id) { /* ... */ }

    /**
     *
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();

        try {
            $organization = Organization::create([
                'name'      => $request->name,
                'owner_id'  => $user->id,
            ]);

            $organization->users()->attach($user->id, ['role' => 'admin']);

            DB::commit();

            return response()->json([
                'message' => 'Organisasi berhasil dibuat.',
                'data' => $organization
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat organisasi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     *
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $organization = $user->organizations()->find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organisasi tidak ditemukan atau Anda tidak memiliki akses.'], 404);
        }

        $role = $organization->users()->find($user->id)->pivot->role;

        if ($role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak. Hanya admin yang dapat mengubah organisasi.'], 403);
        }

        $request->validate(['name' => 'required|string|max:255']);
        $organization->update(['name' => $request->name]);

        return response()->json([
            'message' => 'Organisasi berhasil diperbarui.',
            'data' => $organization
        ], 200);
    }

    /**
     *
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $organization = $user->organizations()->find($id);

        if (!$organization) {
            return response()->json(['message' => 'Organisasi tidak ditemukan atau Anda tidak memiliki akses.'], 404);
        }
        
        $role = $organization->users()->find($user->id)->pivot->role;

        if ($role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak. Hanya admin yang dapat menghapus organisasi.'], 403);
        }

        $organization->users()->detach();
        $organization->delete();

        return response()->json(['message' => 'Organisasi berhasil dihapus.'], 200);
    }
}