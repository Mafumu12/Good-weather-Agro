<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FavouritesController extends Controller
{

    public function store(Request $request)
    {

        $request->validate(['city' => 'required|string']);

        try
        {

            Log::info('City requested:', ['city' => $request->input('city')]);
            $existingFavorite = Favorite::where('user_id', auth()->id())
                ->where('city', $request->input('city'))
                ->first();

            if ($existingFavorite) {
                return redirect()->back()->with('error', 'City is already in your favorites');
            }

            $favorite = new Favorite();
            $favorite->user_id = auth()->id(); // Assuming you have user authentication
            $favorite->city = $request->input('city');
            $favorite->save();

            return redirect()->back()->with('success', 'City added to favorites');
        } catch (Exception $e) {

            Log::error('Unexpected error when adding favorite city', [
                'user_id' => auth()->id(),
                'city' => $request->input('city'),
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again later.');

        }

    }

    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())->latest()->get();
        return response()->json($favorites);
    }

    public function destroy($id)
    {
        try {
            $favorite = Favorite::where('user_id', auth()->id())->where('id', $id)->first();

            if (!$favorite) {
                return response()->json(['error' => 'City not found or not owned by user'], 404);
            }

            $favorite->delete();
            return response()->json(['success' => 'City deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error deleting city: ' . $e->getMessage()], 500);
        }
    }

}
