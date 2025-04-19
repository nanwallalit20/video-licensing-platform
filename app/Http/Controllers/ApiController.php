<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuperAdminRegisterBuyerRequest;
use App\Models\Buyer;
use App\Models\Genre;
use App\Models\BuyerContentType;
use App\Models\MapBuyerGenre;

class ApiController extends Controller {
    public function registerBuyer(SuperAdminRegisterBuyerRequest $request) {
        $buyer = Buyer::create([
            'full_name' => $request->full_name,
            'company_name' => $request->company_name,
            'job_title' => $request->job_title,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'target_audience' => $request->target_audience,
            'territories' => $request->territories,
            'budget' => $request->budget,
            'content_usage' => $request->content_usage,
            'content_duration' => $request->content_duration,
            'acquisition_preferences' => $request->acquisition_preferences,
            'additional_details' => $request->additional_details,
            'whatsapp_number' => $request->whatsapp_number,
            'terms_and_conditons' => $request->terms_and_conditons,
        ]);

        // Save content types in the map_buyer_content_types table
        foreach ($request->content_type as $type) {
            BuyerContentType::create([
                'buyer_id' => $buyer->id,
                'content_type' => $type,
            ]);
        }

        // Save genres in the map_buyer_genres table
        foreach ($request->genre as $genreName) {
            $genre = Genre::where('slug', $genreName)->first();
            if ($genre) {
                MapBuyerGenre::create([
                    'buyer_id' => $buyer->id,
                    'genre_id' => $genre->id,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Buyer registered successfully',], 200);
    }
}
