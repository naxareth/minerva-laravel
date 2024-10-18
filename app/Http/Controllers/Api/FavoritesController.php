<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\FavoriteResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{
    public function index(Request $request)
    {
        // Fetch favorites for the authenticated user
        $favorites = Favorite::where('user_id', $request->user()->id)->get();
        
        if ($favorites->count() > 0) {
            return FavoriteResource::collection($favorites);
        } else {
            return response()->json(['message' => 'No record available'], 200);
        }
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'anime_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'image' => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are required',
                'error' => $validator->messages(),
            ], 422);
        }
    
        // Check if the favorite already exists for the user
        $existingFavorite = Favorite::where('user_id', $request->user()->id)
                                    ->where('anime_id', $request->anime_id)
                                    ->first();
    
        if ($existingFavorite) {
            return response()->json([
                'message' => 'Favorite already exists.',
            ], 409); // Conflict
        }
    
        // Create a new favorite with the authenticated user's ID
        $favorite = Favorite::create([
            'anime_id' => $request->anime_id,
            'title' => $request->title,
            'image' => $request->image,
            'user_id' => $request->user()->id, // Automatically set user_id
        ]);
    
        return response()->json([
            'message' => 'Favorite added',
            'data' => new FavoriteResource($favorite),
        ], 201); // Use 201 for resource creation
    }
    public function show(Request $request, $anime_id)
    {
        // Fetch a specific favorite for the authenticated user
        $favorite = Favorite::where('user_id', $request->user()->id)
                            ->where('anime_id', $anime_id)
                            ->first();

        if ($favorite) {
            return new FavoriteResource($favorite);
        } else {
            return response()->json(['message' => 'Favorite not found.'], 404);
        }
    }

    public function update(Request $request, $anime_id)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are required.',
                'error' => $validator->messages(),
            ], 422);
        }

        // Fetch the favorite to update
        $favorite = Favorite::where('user_id', $request->user()->id)
                            ->where('anime_id', $anime_id)
                            ->first();

        if ($favorite) {
            $favorite->update([
                'title' => $request->title,
                'image' => $request->image,
            ]);

            return response()->json([
                'message' => 'Favorite updated.',
                'data' => new FavoriteResource($favorite),
            ], 200);
        } else {
            return response()->json(['message' => 'Favorite not found.'], 404);
        }
    }

    public function destroy(Request $request, $anime_id)
    {
        // Ensure anime_id is treated as a string
        $anime_id = (string) $anime_id;
    
        // Attempt to delete the favorite using both user_id and anime_id
        $deleted = Favorite::where('user_id', $request->user()->id)
                           ->where('anime_id', $anime_id)
                           ->delete();
    
        if ($deleted) {
            return response()->json(['message' => 'Favorite deleted.'], 200);
        } else {
            return response()->json(['message' => 'Favorite not found.'], 404);
        }
    }
}