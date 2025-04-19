<?php

namespace App\Http\Controllers;

use App\Enums\RevenuePlanStatus;
use App\Enums\Roles;
use App\Enums\TitleCompletion;
use App\Enums\TitleType;
use App\Models\Advisory;
use App\Models\Buyer;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Order;
use App\Models\RevenuePlan;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Title;
use Illuminate\Http\Request;
use App\Enums\TitleStatus;

class DashboardController extends Controller {

    public function index() {
        if (auth()->user()) {
            if (auth()->user()->getUserProfile->role_id->value == Roles::Superadmin->value) {
                return $this->adminDashboard();
            } else if (auth()->user()->getUserProfile->role_id->value == Roles::Seller->value) {
                return $this->sellerDashboard();
            } else if (auth()->user()->getUserProfile->role_id->value == Roles::Buyer->value) {
                return $this->buyerDashboard();
            }
            abort(404);
        }
        return redirect()->route('login');
    }

    public function adminDashboard() {

        $totalSellers = User::whereHas('getUserProfile', function($query) {
            $query->where('role_id', Roles::Seller->value);
        })->count();
        $titleQuery = Title::where('isSubmitted', TitleCompletion::Completed->value);
        $totalTitles = $titleQuery->count();
        $agreementTitles =$titleQuery->whereHas('getRevenuePlan', function($query) {
            $query->where('status', RevenuePlanStatus::Final->value);
        })->count();
        $titleStats = [
            'total' => $totalTitles,
            'pendingApproval' => $titleQuery->where('status', TitleStatus::Pending->value)->count(),
            'pendingAgreement' => $totalTitles - $agreementTitles,
        ];
         // Buyer statistics
        $buyerStats = [
            'total' => User::whereHas('getUserProfile', function($query) {
                $query->where('role_id', Roles::Buyer->value);
            })->count(),
            'pending' => Buyer::count()
        ];

        $totalOrders = Order::count();
        $orderPrice = Order::sum('total_price');
        return view('backend.dashboard', compact('totalSellers', 'titleStats', 'buyerStats', 'totalOrders', 'orderPrice'));
    }

    public function sellerDashboard() {

        $totalTitles = Title::where('user_id', Auth::id())->count();

        $agreementTitles = Title::where('user_id', Auth::id())
            ->whereHas('getRevenuePlan', function ($query) {
                $query->where('status', RevenuePlanStatus::Final->value);
            })->count();

        $submittedTitles = Title::where('user_id', Auth::id())
            ->where('isSubmitted', TitleCompletion::Completed->value)
            ->count();

        $approvedTitles = Title::where('user_id', Auth::id())
            ->where('status', TitleStatus::Accepted->value)
            ->count();

        return view('seller.dashboard', compact('totalTitles', 'submittedTitles', 'approvedTitles', 'agreementTitles'));
    }

    public function buyerDashboard(Request $request = null) {
        $request = $request ?: request();

        $genres = Genre::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $advisories = Advisory::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $buyerId = Auth::id();
        $cartItems = Cart::where('buyer_id', $buyerId)
            ->select(
                'title_id as titleId',
                'season_id as seasonId',
            )
            ->get();


        $query = RevenuePlan::where('status', RevenuePlanStatus::Final->value)
            ->with(['getTitle' => function($q) {
                $q->with(['getGenres', 'getTags', 'getAdvisories', 'getProductionCountries', 'getLicenceCountry']);
            }]);
        if ($request == null) {
            $titles = $query->paginate(10);
            return view('buyer.dashboard', compact('titles', 'genres', 'tags', 'advisories', 'countries', 'cartItems'));
        }

        if ($request->filled('title_name')) {
            $query->whereHas('getTitle', function($q) use ($request) {
                $q->where('titles.name', 'like', '%' . $request->title_name . '%');
            });
        }


        if ($request->filled('genres')) {
            $query->whereHas('getTitle.getGenres', function($q) use ($request) {
                $q->where('genres.id', $request->genres);
            });
        }

        if ($request->filled('tags')) {
            $query->whereHas('getTitle.getTags', function($q) use ($request) {
                $q->where('tags.id', $request->tags);
            });
        }


        if ($request->filled('advisory')) {
            $query->whereHas('getTitle.getAdvisories', function($q) use ($request) {
                $q->where('advisories.id', $request->advisory);
            });
        }

        if ($request->filled('countries')) {
            $query->whereHas('getTitle.getProductionCountries', function($q) use ($request) {
                $q->where('countries.id', $request->countries);
            });
        }

        if ($request->filled('licence')) {
            $query->whereHas('getTitle.getLicenceCountry', function($q) use ($request) {
                $q->where('countries.id', $request->licence);
            });
        }

        if ($request->filled('year')) {
            $query->whereHas('getTitle', function($q) use ($request) {
                $q->where(function($subQuery) use ($request) {
                    $subQuery->where('type', TitleType::Movie->value)
                        ->whereHas('getMovieMeta', function($movieQuery) use ($request) {
                            $movieQuery->whereYear('release_date', $request->year);
                        });
                })->orWhere(function($subQuery) use ($request) {
                    $subQuery->where('type', TitleType::Series->value)
                        ->whereHas('getSeasons', function($seasonQuery) use ($request) {
                            $seasonQuery->whereYear('release_date', $request->year);
                        });
                });
            });
        }

        $titles = $query->orderBy('id', 'desc')->paginate(10);


        if ($request->ajax()) {
            return response()->json([
                'html' => view('buyer.partials.title-list', compact('titles'))->render(),
                'pagination' => (string)$titles->links()
            ]);
        }

        return view('buyer.dashboard', compact('titles', 'genres', 'tags', 'advisories', 'countries', 'cartItems'));
    }

    public function switchAccount($userId = null) {
        if ($userId) {
            $oldUserId = Auth::id();
            if (!$oldUserId) {
                abort(404);
            }
            $user = User::findOrFail($userId);
            if (!$user) {
                abort(404);
            }
            Session::put('old_user_login', true);
            Session::put('old_user', $oldUserId);
            Auth::loginUsingId($userId, true);
        } else {
            if (!Session::get('old_user_login') || !Session::has('old_user')) {
                return redirect()->route('login');
            }
            $userId = Session::get('old_user');
            Session::flush();
            Auth::loginUsingId($userId, true);
        }
        return redirect()->route('dashboard');
    }

}
