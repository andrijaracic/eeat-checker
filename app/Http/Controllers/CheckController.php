<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Check;
use App\Jobs\ProcessCheck;
use App\Services\ArticleFetcher;

class CheckController extends Controller
{
    
    public function index()
    {
        $checks = Auth::user()->checks()->latest()->paginate(10);
        return view('dashboard', compact('checks'));
    }

    
    public function store(Request $request, ArticleFetcher $fetcher)
    {
        $validated = $request->validate([
            'url' => 'required|url|max:2000',
        ]);

        $user = Auth::user();

        // 1️⃣ Pokreni fetch
        $result = $fetcher->fetch($validated['url']);

        // 2️⃣ Kreiraj Check u bazi
        $check = Check::create([
            'user_id' => $user->id,
            'url' => $validated['url'],
            'status' => isset($result['error']) ? 'failed' : 'done',
            'fetched_title' => $result['title'] ?? null,
            'fetched_author' => $result['author'] ?? null,
            'fetched_published_at' => $result['published_at'] ?? null,
            'summary' => $result['content'] ?? null,
            'error_message' => $result['message'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success', 'Check je kreiran.');
    }
}
