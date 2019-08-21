<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * TODO: add auth bullshit
 */
class APIController extends Controller
{
    /**
     * 
     */
    public function profile(Request $request)
    {
        /**
         * {
         * "username": "rachel",
         * "email": "rachel@wetfish.net",
         * "permissions": ["volunteer", "admin", "ranger"],
         * "full_name": "Rachel Fish",
         * "burner_name": "BlubBlub",
         * "phone_number": "555-123-4567"
         * }
         */
        $user = Auth::user();
        // $user_data = $user->data;
        return response()->json([
            'username' => $user->name,
            'email' => $user->email,
            'permissions' => $user->getRoleNames(),
            'full_name' => $user->data->full_name ?? null,
            'burner_name' => $user->data->burner_name ?? null,
            'phone_number' => $user->data->phone ?? null,
        ]);
    }

    /**
     * 
     */
    public function events(Request $request)
    {
        // TODO
    }

    /**
     * 
     */
    public function departments(Request $request, $id)
    {
        // TODO
    }

    /**
     * 
     */
    public function roles(Request $request, $id)
    {
        // TODO
    }

    /**
     * 
     */
    public function shifts(Request $request, $id)
    {
        // TODO
    }

}
