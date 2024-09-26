<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Http\Controllers\Controller;
use App\Http\Resources\Resources\FavoriteResource;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoritesController extends Controller
{
    public function index()
    {
        $favorites = Favorite::get();
        if ($favorites->count() > 0)
        {
            return FavoriteResource::collection($favorites);
        }
        else
        {
            return response()->json(['message'=>'No record available'],200);
        }
    }

    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(),[
                'id' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'image' => 'required|string|max:255',
    ]);
    if($validator->fails())
    {
        return response()->json([
            'message' => 'All fields are required',
            'error'=>$validator->messages(),
        ],422);
    }
      
        $favorite = Favorite::create([
            'id' => $request->id,
            'title' => $request->title,
            'image' => $request->image,
        ]);

        return response()->json([
            'message'=>'Favorite added',
            'data' => new FavoriteResource($favorite)
            ],200);
        
    }
    public function show(Favorite $favorite)
    {
        return new FavoriteResource($favorite);
    }
    public function update(Request $request, Favorite $favorite)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'image' => 'required|string|max:255',
    ]);
    if($validator->fails())
    {
    return response()->json([
        'message' => 'All fields are required.',
        'error'=>$validator->messages(),
    ],422);
    }
  
    $favorite -> update([
        'id' => $request->id,
        'title' => $request->title,
        'image' => $request->image,
    ]);

    return response()->json([
        'message'=>'Favorite updated.',
        'data' => new FavoriteResource($favorite)
        ],200);
    }
    public function destroy(Favorite $favorite)
    {
        $favorite->delete();
        return response()->json([
            'message'=> 'Favorite deleted.'
        ],200);
    }
}
