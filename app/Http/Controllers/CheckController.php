<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Check;
use App\Jobs\ProcessCheck;

class CheckController extends Controller
{
    // Prikaz svih provera korisnika (dashboard)
    public function index()
    {
        $checks = Auth::user()->checks()->latest()->paginate(10);
        return view('dashboard', compact('checks'));
    }

    // Kreiranje nove provere
    public function store(Request $request)
    {
        // 1. Validacija URL-a
        $validated = $request->validate([
            'url' => 'required|url|max:2000',
        ]);

        $user = Auth::user();

        // 2. Kreiranje Check zapisa sa statusom queued
        $check = Check::create([
            'user_id' => $user->id,
            'url' => $validated['url'],
            'status' => 'queued',
        ]);

        // 3. Pokretanje ProcessCheck job-a
        

        // 4. Redirekcija nazad sa porukom
        return redirect()->route('dashboard')->with('success', 'Check je kreiran i poslat na obradu.');
    }
}
