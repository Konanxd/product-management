<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Organization;
use App\Models\OrganizationInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationInvitationController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'orgId' => 'required|string|exists:organization,id',
        ]);

        $code = Str::upper(Str::random(8));

        return Organization::create([
            'organization_id' => $request->orgId,
            'code' => $code
        ]);
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $invite = OrganizationInvitation::where('code', $request->code)
            ->where('used', false)
            ->first();

        if (!$invite) {
            return response()->json([
                'messsage' => 'kode sudah digunakan'
            ]);
        }

        $user = auth('api')->user();

        $user->organizations->attach($invite->organization_id);

        $invite->update(['used' => true]);

        return response()->json([
            'message' => 'Anda telah bergabung ke organisasi'
        ]);
    }
}
