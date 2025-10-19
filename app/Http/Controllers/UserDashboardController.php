<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Check;
use App\Models\User;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Uzmi sve provere korisnika, paginacija po 10
        $checks = $user->checks();

        return view('dashboard', compact('user', 'checks'));
    }
}

