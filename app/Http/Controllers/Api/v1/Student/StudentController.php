<?php

namespace App\Http\Controllers\Api\v1\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\CategoryResource;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Manager;
use App\Models\User;
use App\Notifications\ContactUsNotification;
use App\Rules\EmailRule;
use App\Rules\StartWith;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        dd(checkRequestIsWorkingOrNot());
        return apiSuccess(ChatResource::collection(apiUser()->chats));
    }

    public function contactUs(Request $request)
    {
        $request->validate([
            'name' => 'required|max:250',
            'title' => 'required|min:3|max:100',
            'mobile' => ['required', new StartWith('+')],
            'message' => 'required|min:3|max:200',
            'email' => ['nullable', 'email', 'max:50', new EmailRule()],
        ]);

        $user = apiUser();
        $contact = ContactUs::create([
            'name' => $request->name,
            'title' => $request->title,
            'mobile' => $request->mobile,
            'message' => $request->message,
            'email' => $request->email,
            'user_id' => optional($user)->id,
        ]);
        Notification::send(Manager::query()->get(), new ContactUsNotification($contact));
        return apiSuccess(null, api('Message Sent Successfully'));
    }


    public function share_app(Request $request)
    {
//        لازم هذا الرقم يسجل في التطبيق بعد هيك ومن ثم يطلب ثلاث اوردرات ع الاقل عشان اعطيه النقاط الي انا محددها من اللوحة
        $request->validate(['mobile' => ['required', 'numeric']]);
        $share = ShareApp::find($request->mobile);
//        if (isset($share)) $share->update(['times' => ($share->times + 1)]);
        if (!isset($share))
            ShareApp::create([
                'mobile' => $request->mobile,
                'shared_user_id' => apiUser()->id,
            ]);
        return apiSuccess(null, api('App Shared Successfully'));
    }

    public function settings()
    {
        $user = apiUser();
        $have_orders = false;
        if (isset($user)) {
            if ($user->user_type == User::DISTRIBUTOR) {
                $have_orders = $user->distributor_orders()->pendingOrOnWayOrder()->count() > 0 ? true : false;
            }
        }
        return apiSuccess([
//            'system_constants' => [
//                'METHOD_NOT_ALLOWED_EXCEPTION' => METHOD_NOT_ALLOWED_EXCEPTION,
//                'UN_AUTHENTICATED' => UN_AUTHENTICATED,
//                'VALIDATION_EXCEPTION' => VALIDATION_EXCEPTION,
//                'SERVER_ERROR' => SERVER_ERROR,
//            ],
            'has3Orders' => isset($user) ? $user->orders()->completedOrder()->count() >= 3 : false,
            'have_orders' => $have_orders,
            'app_version' => (int)optional(getSettings('app_version'))->value,
            'home_page_version' => (int)optional(getSettings('home_page_version'))->value,
            'policy' => optional(getSettings('policy'))->value,
            'about_us' => optional(getSettings('about_us'))->value,
            'address' => optional($this->country)->address,
            'lat' => optional($this->country)->lat,
            'lng' => optional($this->country)->lng,
            'email' => optional($this->country)->email,
            'mobile' => optional($this->country)->mobile,
//            'token_from_dashboard' => setting('token_form_dashboard'),
        ]);
    }

    public function home(Request $request)
    {
        $request['except_arr_resource'] = ['products', 'product'];
        $country = (integer)$request->header('country-id');
        $sliders = AdImages::currentCountry($country)->get();
        $categories = Category::whereHas('products')->get();
//        $products = Product::with(['prices', 'category'])->whereHas('category')->whereHas('prices', function ($query) use ($request) {
//            $query->currentCountry($request->header('country-id'));
//        })->get();
        return apiSuccess([
            'ads_images' => AdsImagesResource::collection($sliders),
            'products_categories' => CategoryResource::collection($categories),
//            'best_deal' => ProductResource::collection($products),
        ]);
    }

    public function get_products(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:sale,normal_offer,limit_offer,gifts',
//            'category_id' => 'requiredIf:type,sale|exists:categories,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $request['except_arr_resource'] = ['products', 'product', 'category', 'price'];
        $category = request()->get('category_id', false);
        $search = request()->get('search', false);
        $country = $request->header('country-id');
        $now = \Carbon\Carbon::now()->toDateString();
//dd($now);
        $product_query = Product::with(['price', 'price.offer', 'category'])
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name->' . lang(), 'like', '%' . $search . '%');
            });
        if ($request->type == 'sale') {
            $product_query = $product_query->whereHas('price', function ($query) use ($request) {
                $query->doesnthave('offer');
            })->paginate($this->perPage);
            return apiSuccess([
                'items' => ProductResource::collection($product_query),
                'paginate' => paginate($product_query),
            ]);
        } else if ($request->type == 'gifts') {
            $gifts = Gift::currentCountry($country)->when($search, function ($query) use ($search) {
                $query->where('name->' . lang(), 'like', '%' . $search . '%');
            });
            $gifts = $gifts->paginate($this->perPage);
            return apiSuccess([
                'points' => optional(apiUser())->points,
                'items' => GiftResource::collection($gifts),
                'paginate' => paginate($gifts),
            ]);
        } elseif ($request->type == 'normal_offer') {
            $product_query = $product_query->whereHas('price', function ($query) use ($request) {
                $query->whereHas('offer', function ($qq) {
                    $qq->where('type', Offer::type['NORMAL'])
                        ->where('active', true);
                });
            })->paginate($this->perPage);
            return apiSuccess([
                'items' => ProductResource::collection($product_query),
                'paginate' => paginate($product_query),
            ]);
        } elseif ($request->type == 'limit_offer') {
            $product_query = $product_query->whereHas('price', function ($query) use ($request, $now) {
                $query->whereHas('offer', function ($query) use ($now) {
                    $query->where('active', true)->where(function ($query) use ($now) {
                        $query->where(function ($query2) use ($now) {
                            $query2->where('type', Offer::type['LIMITED'])
//                                ->whereBetween('reservation_from', [$from, $to])
                                ->whereDate('from', '<=', $now)
                                ->whereDate('to', '>=', $now);
                        })->orWhere(function ($qq) {
                            $qq->where('type', Offer::type['ONE_DAY'])->where('day', getDayNumber(Carbon::now()->dayOfWeek));
                        });
                    });
                });
            })->paginate($this->perPage);
            return apiSuccess([
                'items' => ProductResource::collection($product_query),
                'paginate' => paginate($product_query),
            ]);

        } else {
            $product_query = $product_query->paginate($this->perPage);
            return apiSuccess([
                'items' => ProductResource::collection($product_query),
                'paginate' => paginate($product_query),
            ]);
        }
    }

    public function categories()
    {
        $categories = Category::query()->get();
        return apiSuccess(CategoryResource::collection($categories));
    }

    public function countries(Request $request)
    {
        return apiSuccess(CountryResource::collection(Country::get()));
    }

    public function ads_images(Request $request)
    {
        return apiSuccess(AdsImagesResource::collection(AdImages::currentCountry($request->header('country-id'))->get()));
    }

    public function favorite(Request $request, $id)
    {
        $user = apiUser();
        $fav = Favorite::query()->where('user_id', $user->id)->where('price_id', $id)->first();
        if (isset($fav)) {
            $fav->delete();
            return apiSuccess(api('Favorite item deleted successfully'));
        }
        $product_price = ProductPrices::findOrFail($id);
        Favorite::create([
            'user_id' => $user->id,
            'price_id' => $product_price->id,
        ]);
        return apiSuccess(api('Product added to favorites successfully'));
    }


    public function favorites(Request $request)
    {
        $user = apiUser();
        $products = Favorite::where('user_id', $user->id)
            ->whereHas('product_price')->with(['product_price.product'])->paginate($this->perPage);
//        dd($products);
        return apiSuccess([
            'items' => ProductPriceResource::collection($products->pluck('product_price')),
            'paginate' => paginate($products),
        ]);
    }

}
