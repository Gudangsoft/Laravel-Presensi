<?php

namespace App\Http\Controllers;

use App\Models\LearningScore;
use App\Models\LearningWord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function index()
    {
        $karyawan  = Auth::guard('karyawan')->user();
        $bestScore = LearningScore::where('karyawan_id', $karyawan->id)
            ->selectRaw('level, MAX(score) as best, COUNT(*) as plays')
            ->groupBy('level')
            ->pluck('best', 'level');

        $leaderboard = LearningScore::with('karyawan')
            ->selectRaw('karyawan_id, MAX(score) as best_score, MAX(level) as level')
            ->groupBy('karyawan_id')
            ->orderByDesc('best_score')
            ->limit(10)
            ->get();

        $title = 'Smart Learning English';
        return view('dashboard.learning.index', compact('title', 'karyawan', 'bestScore', 'leaderboard'));
    }

    public function words(Request $request): JsonResponse
    {
        $level = in_array($request->level, ['easy', 'medium', 'hard']) ? $request->level : 'easy';
        $words = LearningWord::where('level', $level)->inRandomOrder()->limit(10)->get();
        return response()->json($words);
    }

    public function saveScore(Request $request): JsonResponse
    {
        $request->validate([
            'level'   => 'required|in:easy,medium,hard',
            'score'   => 'required|integer|min:0|max:100',
            'correct' => 'required|integer|min:0',
            'total'   => 'required|integer|min:1',
        ]);

        $karyawan = Auth::guard('karyawan')->user();

        LearningScore::create([
            'karyawan_id' => $karyawan->id,
            'level'       => $request->level,
            'score'       => $request->score,
            'correct'     => $request->correct,
            'total'       => $request->total,
        ]);

        return response()->json(['message' => 'Skor tersimpan']);
    }
}
