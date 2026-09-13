<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AllCity;
use App\Models\AllState;
use App\Models\Candidate;
use App\Models\FeDocument;
use App\Models\RaDocument;
use App\Models\UserPassport;

class AccountController extends Controller
{
    public function account()
    {
        return view('frontend.guest.account');
    }

    public function changeAccountPassword(Request $request){
        $validatedData = $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        if (Hash::check($validatedData['old_password'], $user->password)) {
            // Update the password
            $user->password = Hash::make($validatedData['password']);
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!',

            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The provided password does not match your current password.',

            ]);
        }
    }

    public function updateAccount(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|numeric|digits:10',
        ]);

        $user = auth()->user();
        $user->name = $validatedData['name'];
        $user->mobile = $validatedData['mobile'];
        $user->save();
        return response()->json([
            'success' => true,
            'message' => 'Account updated successfully!',

        ]);
    }
}