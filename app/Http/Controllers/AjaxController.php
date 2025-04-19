<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\OfficialRating;
use App\Helpers\FileUploadHelper;
use App\Models\Tag;
use App\Models\Genre;
use App\Models\Writer;
use App\Models\Country;
use App\Models\Keywords;
use App\Models\Director;
use App\Models\Producer;
use App\Models\Festival;
use App\Models\Advisory;
use App\Models\Composers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Title;
use App\Enums\TitleType;
use App\Enums\MediaTypes;

class AjaxController extends Controller {

    public function getGenres(Request $request) {
        $term = $request->get('term');
        $genres = Genre::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($genres);
    }

    public function getKeywords(Request $request) {
        $term = $request->get('term');
        $keywords = Keywords::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($keywords);
    }

    public function getCountries(Request $request) {
        $term = $request->get('term');
        $countries = Country::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($countries);
    }

    public function getDirectors(Request $request) {
        $term = $request->get('term');
        $directors = Director::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($directors);
    }

    public function getProducers(Request $request) {
        $term = $request->get('term');
        $producers = Producer::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($producers);
    }

    public function getWriters(Request $request) {
        $term = $request->get('term');
        $writers = Writer::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($writers);
    }

    public function getComposers(Request $request) {
        $term = $request->get('term');
        $compsers = Composers::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($compsers);
    }

    public function getTags(Request $request) {
        $term = $request->get('term');
        $tags = Tag::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($tags);
    }

    public function getFestivals(Request $request) {
        $term = $request->get('term');
        $festivals = Festival::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($festivals);
    }

    public function getAdvisories(Request $request) {
        $term = $request->get('term');
        $advisory = Advisory::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($advisory);
    }

    public function fetchTitlesByIds() {
        $buyerId = Auth::user()->id;
        $cartItems = Cart::where('buyer_id', $buyerId)->get();

        // Pass the cart items directly to the view
        return view('components.content-library.cart-item', [
            'cartItems' => $cartItems
        ])->render();
    }

    public function getRating(Request $request) {
        $term = $request->get('term');
        $ratings = OfficialRating::select([
            DB::raw('id as id'),
            DB::raw('name as text'),
        ])->where('name', 'like', '%' . $term . '%')->paginate();
        return response()->json($ratings);
    }

}
