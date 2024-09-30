<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FavouritesController extends Controller
{

    public function store(Request $request)
    {
        $request->validate(['city' => 'required|string']);
        Log::info('City requested:', ['city' => $request->input('city')]);

        $favorite = new Favorite();
        $favorite->user_id = auth()->id(); // Assuming you have user authentication
        $favorite->city = $request->input('city');
        $favorite->save();

        return response()->json(['message' => 'City added to favorites'], 201);
    }

    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())->get();
        return response()->json($favorites);
    }
}
