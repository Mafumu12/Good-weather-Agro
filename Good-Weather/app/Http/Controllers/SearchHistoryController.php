<?php

namespace App\Http\Controllers;

use App\Models\SearchHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchHistoryController extends Controller
{

    public function index()
    {
        $searchHistory = SearchHistory::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->pluck('city', 'id')
            ->toArray();

        return response()->json($searchHistory);
    }

    public function store(Request $request)
    {
        $request->validate(['city' => 'required|string']);

        log::info('history', ["search" => $request->input('city')]);

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

        // Check if the city already exists in the search history
        if (in_array($city, $searchHistory)) {
            // If it exists, delete the old record
            SearchHistory::where('user_id', $user->id)
                ->where('city', $city)
                ->delete();
        }

        // Add the new city to the search history
        SearchHistory::create([
            'user_id' => $user->id,
            'city' => $city,
        ]);

        // Ensure only the last 5 cities are stored
        if (count($searchHistory) > 6) {
            SearchHistory::where('user_id', $user->id)
                ->oldest()
                ->first()
                ->delete();
        }
    }

    public function destroy($id)
    {
        try {

            Log::info("Attempting to delete history item", ['user_id' => auth()->id(), 'item_id' => $id]);

            $historyItem = SearchHistory::where('user_id', auth()->id())->where('id', $id)->first();

            if (!$historyItem) {
                return response()->json(['error' => 'History item not found or not owned by user'], 404);
            }

            $historyItem->delete();
            return response()->json(['success' => 'History item deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error deleting history item: ' . $e->getMessage()], 500);
        }
    }
}
