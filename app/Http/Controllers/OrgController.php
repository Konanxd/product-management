<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrgController extends Controller
{
    public function choices()
    {
        return view('pages.auth.choices');
    }

    public function create()
    {
        return view('pages.auth.organization');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:organizations,name',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth('api')->user();

        $organization = Organization::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if (!$organization) {
            return response()->json([
                'success' => false,
                'message' => 'Organisasi sudah terdaftar'
            ], 409);
        }

        $addOwner = $user->organizations()->syncWithoutDetaching([
            $organization->id => ['role' => 'owner']
        ]);

        if (!$addOwner) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Organisasi berhasil terdaftar',
            'user' => $user,
            'organization' => $organization
        ], 201);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:organization,name',
        ]);

        if (!$validator) {
            return response()->json([
                'success' => false,
                'message' => 'Nama sudah tersedia'
            ], 500);
        }

        $user = auth('api')->user();

        DB::beginTransaction();

        try {
            $org = Organization::create([
                'name' => $request->name
            ]);

            $org->users()->attach($user->id, [
                'role' => 'owner'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan ke dalam organisasi Anda',
                'user' => $user,
                'organization' => $org
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan' . $e
            ], 500);
        }
    }

    public function storeMember(Request $request)
    {
        if (!$this->isOwner()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses'
            ], 409);
        }

        $user = auth('api')->user();
        $org = $user->organization;

        $validator = Validator::make($request->all(), [
            'id_user' => 'required|string',
            'role' => 'required|string'
        ]);

        if (!$validator) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan atau role tidak tersedia'
            ], 409);
        }

        $member = User::find($request->id_user);

        $member->update([
            'organization_id' => $org->id,
            'role' => $request->role
        ]);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan atau role tidak tersedia'
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan ke dalam organisasi Anda',
            'user' => $user,
            'organization' => $org
        ], 201);
    }

    public function isOwner(): Bool
    {
        $user = auth('api')->user();

        if ($user->role !== 'owner') {
            return false;
        }

        return true;
    }
}
