<?php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{

    public function index()
    {
        $searchHistory = SearchHistory::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->pluck('city')
            ->toArray();

        return response()->json($searchHistory);
    }

    public function store(Request $request)
    {
        $request->validate(['city' => 'required|string']);

        $this->storeSearchHistory($request->input('city'));

        return response()->json(['message' => 'Search history updated']);
    }

    protected function storeSearchHistory($city)
    {
        $user = auth()->user();

        // Retrieve the last five searches
        $searchHistory = SearchHistory::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->pluck('city')
            ->toArray();

        // Only store the city if it doesn't already exist in the recent searches
        if (!in_array($city, $searchHistory)) {
            SearchHistory::create([
                'user_id' => $user->id,
                'city' => $city,
            ]);

            // Ensure only the last 5 cities are stored
            if (count($searchHistory) >= 5) {
                SearchHistory::where('user_id', $user->id)
                    ->oldest()
                    ->first()
                    ->delete();
            }
        }
    }
}
