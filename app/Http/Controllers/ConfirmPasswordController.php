<?php
// app/Http/Controllers/ConfirmPasswordController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConfirmPasswordController extends Controller
{
    public function showConfirmationForm()
    {
        return view('auth.passwords.confirm');
    }

    public function confirm(Request $request)
    {
        $password = $request->input('password');

        if (Auth::attempt(['email' => Auth::user()->email, 'password' => $password])) {
            // Password is correct, proceed with deletion
            $deleteRequestUrl = Session::pull('delete_request_url');
            return redirect()->to($deleteRequestUrl);
        } else {
            // Password is incorrect, redirect back to previous page with error
            return redirect()->back()->with('error', 'Incorrect password');
        }
    }
}
