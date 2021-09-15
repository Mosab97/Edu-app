<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AdImages;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Country;
use App\Models\Manager;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductPrices;
use App\Models\Slider;
use App\Models\User;
use App\Notifications\ContactUsNotification;
use App\Rules\EmailRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Proengsoft\JsValidation\Facades\JsValidatorFacade as JsValidator;

class HomeController extends Controller
{
    private $view = 'website';

    public function __construct()
    {
//        dd(DB::select("SELECT CONVERT(SUBSTRING_INDEX(mobile,'-',-1),UNSIGNED INTEGER) AS num FROM users ORDER BY id;"));
//        phpinfo();
        parent::__construct();
    }

    public function privacy_policy(Request $request)
    {
        return view($this->view . '.privacy_policy');
    }

    public function about_us(Request $request)
    {
        return view($this->view . '.about_us');
    }

    public function welcome()
    {
//        $contValidationRules = [];
        $contValidationRules["name"] = 'required|max:255';
        $contValidationRules["title"] = 'required|max:255';
        $contValidationRules["message"] = 'required';
        $contValidationRules["mobile"] = ['required'];
        $contValidationRules["email"] = ['required', new EmailRule()];
        $cont_validator = JsValidator::make($contValidationRules);


        $products = Product::limit(20)->with(['images'])->whereHas('price')->get();
        $merchants = User::merchantUserType()->count();
        $customers = User::customer()->count();
        $orders = Order::count();
        $products_count = Product::count();
        $sliders = Slider::get();
        return view($this->view . '.welcome', compact('products', 'merchants', 'orders', 'customers', 'products_count', 'cont_validator', 'sliders'));
    }

    public function changeCountry(Request $request, $country)
    {
        $country = Country::with(['currency'])->findOrFail($country);
        $request->session()->put('country', $country);
        return redirect()->route('home');

        return redirect()->back();
    }

    public function search(Request $request)
    {
        return redirect()->route('products', ['search' => $request->search]);
    }

    public function products(Request $request)
    {
        $search = $request->get('search', false);
        $items = isset($request->items) ? $request->items : 5;
        $products = Product::whereHas('price')->when($search, function ($query) use ($search) {
            $query->where('name->' . lang(), 'like', '%' . $search . '%');
        })->limit($items)->get();
        $categories = Category::get();
        return view($this->view . '.products', compact('products', 'categories'));
    }

    public function product(Request $request, $product)
    {
        $product = Product::whereHas('price')->with(['images', 'category'])->findOrFail($product);
        $related_product = Product::where('category_id', $product->category_id)->where('id', '<>', $product->id)
            ->with(['images', 'category'])->whereHas('price')->get();
        return view($this->view . '.product', compact('product', 'related_product'));
    }

    public function filter(Request $request, $categories = null)
    {
        $price = $request->price;
        $items = isset($request->items) ? $request->items : 15;
        $categories = isset($categories) ? explode(',', $categories) : null;
        $products = Product::when($categories, function ($query) use ($categories) {
            $query->whereIn('category_id', $categories);
        })->whereHas('price');
//        if (isset($price)) {
//            switch ($price) {
//                case 1:
//                    $products->whereHas('price', function ($query) {
//                        $query->where('piece_cost', '>=', 0)->where('piece_cost', '<=', 100);
//                    });
//                    break;
//                case 2:
//                    $products->whereHas('price', function ($query) {
//                        $query->where('piece_cost', '>=', 100)->where('piece_cost', '<=', 200);
//                    });
//                    break;
//                case 3:
//                    $products->whereHas('price', function ($query) {
//                        $query->where('piece_cost', '>=', 200)->where('piece_cost', '<=', 300);
//                    });
//                    break;
//
//                case 4:
//                    $products->whereHas('price', function ($query) {
//                        $query->where('piece_cost', '>=', 400)->where('piece_cost', '<=', 500);
//                    });
//                    break;
//            }
//        }

        $products = $products->limit($items)->get();


        return view($this->view . '.products-filter', compact('products', 'items', 'categories', 'price'))->render();
    }

    public function filterPrice(Request $request, $price = null)
    {
        $items = isset($request->items) ? $request->items : 15;
//        dd($price,$items,checkRequestIsWorkingOrNot());
        $products = Product::query()->limit($items);

        switch ($price) {
            case 1:
                $products->whereHas('price', function ($query) {
                    $query->where('piece_cost', '>=', 0)->where('piece_cost', '<=', 100);
                });
                break;
            case 2:
                $products->whereHas('price', function ($query) {
                    $query->where('piece_cost', '>=', 100)->where('piece_cost', '<=', 200);
                });
                break;
            case 3:
                $products->whereHas('price', function ($query) {
                    $query->where('piece_cost', '>=', 200)->where('piece_cost', '<=', 300);
                });
                break;

            case 4:
                $products->whereHas('price', function ($query) {
                    $query->where('piece_cost', '>=', 400)->where('piece_cost', '<=', 500);
                });
                break;
        }
        $products = $products->get();

        return view($this->view . '.products-filter', compact('products', 'items'))->render();
    }

    public function loadMore(Request $request, $items)
    {
        $categories = isset($request->categories) ? explode(',', $request->categories) : null;

        $price = $request->price;
        $search = $request->get('search', false);
        $products = Product::when($search, function ($query) use ($search) {
            $query->where('name->' . lang(), 'like', '%' . $search . '%');
        });//->limit($items)->get();
        $products = $products->when($categories, function ($query) use ($categories) {
            $query->whereIn('category_id', $categories);
        });
        if (isset($price)) {
            switch ($price) {
                case 1:
                    $products->whereHas('price', function ($query) {
                        $query->where('piece_cost', '>=', 0)->where('piece_cost', '<=', 100);
                    });
                    break;
                case 2:
                    $products->whereHas('price', function ($query) {
                        $query->where('piece_cost', '>=', 100)->where('piece_cost', '<=', 200);
                    });
                    break;
                case 3:
                    $products->whereHas('price', function ($query) {
                        $query->where('piece_cost', '>=', 200)->where('piece_cost', '<=', 300);
                    });
                    break;

                case 4:
                    $products->whereHas('price', function ($query) {
                        $query->where('piece_cost', '>=', 400)->where('piece_cost', '<=', 500);
                    });
                    break;
            }
        }
        $products = $products->whereHas('price')->limit($items)->get();
        $items += 5;
        return view($this->view . '.products-filter', compact('products', 'items', 'categories', 'price'))->render();
    }

    public function loadMoreBestOffers(Request $request, $items)
    {
        $prices_best_offers = ProductPrices::with(['offer', 'product'])->currentCountry(getCurrentCountry()->id)->whereHas('offer', function ($query) {
            $query->where('active', true)->where('type', Offer::type['NORMAL']);
        })->whereHas('product', function ($query) {
            $query->whereHas('price');
        })->limit($items)->get();
        $items += 5;
        return view($this->view . '.best-offer-filter', compact('prices_best_offers', 'items'))->render();
    }


    public function offers(Request $request)
    {
        $ad_images = AdImages::/*where('country_id', optional(getCurrentCountry())->id)->*/ get();
//        $now = \Carbon\Carbon::now()->format('Y-m-d');
        $now = \Carbon\Carbon::now()->toDateString();

//        dd($now);
        $prices = ProductPrices::with(['offer', 'product'])->currentCountry(getCurrentCountry()->id)
            ->whereHas('offer', function ($query) use ($now) {
                $query->where('active', true)->where(function ($query) use ($now) {
                    $query->where(function ($query2) use ($now) {
                        $query2->where('type', Offer::type['LIMITED']);
                    })->orWhere(function ($qq) {
                        $qq->where('type', Offer::type['ONE_DAY'])->where('day', getDayNumber(Carbon::now()->dayOfWeek));
                    });
                });
            })
//            ->dd()

            ->whereHas('product', function ($query) {
                $query->whereHas('price');
            })->get()->filter(function ($item) use ($now) {
                $offer = $item->offer;
                if (optional($offer)->type == Offer::type['LIMITED']) {

                    return Carbon::now()->gte(Carbon::parse(optional($offer)->from)) && Carbon::parse(optional($offer)->to)->gte(Carbon::now());
                    dd(optional($offer)->from, Carbon::parse(optional($offer)->from)->toDateString() >= Carbon::parse($now)->toDateString(), optional($offer)->to, $now);
                    return (optional($offer)->from >= $now && optional($offer)->to >= $now);
                } else return true;
            });
        $prices_best_offers = ProductPrices::with(['offer', 'product'])->currentCountry(getCurrentCountry()->id)->whereHas('offer', function ($query) {
            $query->where('active', true)->where('type', Offer::type['NORMAL']);
        })->whereHas('product', function ($query) {
            $query->whereHas('price');
        })->limit(5)->get();
        return view($this->view . '.offers', compact('ad_images', 'prices', 'prices_best_offers'));
    }

    public function offer(Request $request, $offer_id)
    {
        $offer = Offer::with(['price', 'price.product', 'price.product.images'])->findOrFail($offer_id);
        $price = $offer->price;
        $product = $price->product;
        $related_offers = Offer::whereHas('price', function ($query) use ($product) {
            $query->currentCountry(getCurrentCountry()->id);//->where('product_id', optional($product)->id);
        })->get();
//        $related_product = Product::where('category_id', $product->category_id)->where('id', '<>', $product->id)->with(['images', 'category'])->whereHas('price')->get();
        return view($this->view . '.offer', compact('offer', 'price', 'product', 'related_offers'));
    }

    public function gallery(Request $request)
    {
        $posts = Post::get();
        $first_post = $posts->first();
        $first_4_posts = $posts->skip(1)->take(4);
        $latest_posts_section = $posts->skip(5)->take(4);
        $remaining = $posts->skip(9)->take(6);
        return view($this->view . '.gallery', compact('first_post', 'first_4_posts', 'latest_posts_section', 'remaining'));
    }

    public function blog(Request $request, $id)
    {
        $blog = Post::findOrFail($id);
        return view($this->view . '.blog', compact('blog'));
    }

    public function loadMoreGallery(Request $request, $items)
    {
        $posts = Post::skip(9)->take($items)->get();
        $items += 6;
        return view($this->view . '.gallery-filter', compact('posts', 'items'))->render();
    }

    public function contactUs(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'title' => 'required',
            'message' => 'required',
            'mobile' => ['required'],
            'email' => ['required', new EmailRule()],
        ]);
        $contact = ContactUs::create([
            'name' => $request->name,
            'title' => $request->title,
            'message' => $request->message,
            'mobile' => $request->mobile,
            'email' => $request->email,
        ]);
        Notification::send(Manager::query()->get(), new ContactUsNotification($contact));
        return apiSuccess($contact, w('Message Sent Successfully'));
//        return redirect()->back()->with('message', w('Message Sent Successfully'))->with('m-class', 'success');
    }


}
