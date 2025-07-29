<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class MembersController extends Controller
{
    public function index()
    {
        return view('pages.members');
    }

    public function data()
    {
        $auth = auth('api')->user();
        $user = User::find($auth['id']);

        $org = $user->organizations;

        $members = Organization::with('users')
            ->findOrFail($org[0]['id'])
            ->users;

        return response()->json([
            'user' => $user,
            'organizations' => $org,
            'members' => $members,
        ]);
    }
}
