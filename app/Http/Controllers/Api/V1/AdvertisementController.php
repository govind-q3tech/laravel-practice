<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserReview;
use App\Models\Category;
use App\Models\Advertisement;
use App\Models\Discussion;
use App\Models\AdvertisementImage;
use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Area;
use App\Models\HashTag;
use App\Models\AdvertisementAttribute;
use App\Traits\ApiGlobalFunctions;
use App\Models\UserWishlist;
use App\Models\AdvertisementHashtag;
use Auth, Mail, DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Membership\Subscription;
use Exception;
use Carbon\Carbon;


class AdvertisementController extends Controller
{
    use ApiGlobalFunctions;
    public function subcategoryList(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'category_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }

            $subCategory = Category::where('status', 1)->where('parent_id', $request->category_id)->get(['id', 'title', 'slug', 'icon']);
            if (count($subCategory) > 0) {
                $attributes = $this->getAttributes($request->category_id);
                $result['subCategory'] = $subCategory;
                $result['attributes'] = $attributes;
                return $this->sendResponse($result, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    private function getAttributes($business_category_id)
    {
        $attributes = Attribute::withCount([
            'attribute_options' => function ($query) {
                $query->where('parent_id', 0);
            }
        ])
            ->sortable(['ordering' => 'ASC'])
            ->with(['attribute_options' => function ($q) {
                $q->where('status', 1);
            }])->whereHas('businessCategory', function ($query) use ($business_category_id) {
                $query->where('category_id', $business_category_id);
            })->where('status', 1)->get();
        // if($attributes){
        //     $i = 0;
        //     foreach($attributes as $attrValue){
        //         if($attrValue->type == 'dropdown'){
        //             $attributeOption = AttributeOption::where('parent_id', $attrValue->id)
        //             ->sortable(['ordering' => 'ASC'])
        //             ->whereHas('businessCategory', function ($query) use ($business_category_id) {
        //                 $query->where('category_id', $business_category_id);
        //             })->status(1)->count();
        //             $attributes[$i]->attribute_option = $attributeOption;
        //             // dump($attrValue->type);
        //         }
        //         $i++
        //     }
        // }
        // dd($attributes);
        // die;
        return $attributes;
    }
    // =========================subcategoryListAttribute===================================
    public function subcategoryListAttribute(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        // try {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'id'   => 'required',
            'attr_id'     => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
        }
        try {
            $attributeOption = AttributeOption::where('parent_id', $data['id'])->where('category_id', $data['category_id'])->get();
            // dd($attributeOption->count());
            if (!empty($attributeOption)) {
                return $this->sendResponse($attributeOption, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }
    // ===========================================================

    public function addListingBySubcategoryId(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'sub_category_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $adsQuery = Advertisement::query();
            //==================== filter on category_id
            if (isset($data['category_id']) && !empty($data['category_id'])) {
                $adsQuery->where('business_category_id', $data['category_id']);
            }
            //==================== filter on sub_category_id
            if (isset($data['sub_category_id']) && !empty($data['sub_category_id'])) {
                $adsQuery->where('sub_business_category_id', $data['sub_category_id']);
            }
            //==================== filter with city
            if (isset($data['city']) && !empty($data['city'])) {
                $adsQuery->where('city_id', $data['city']);
            }
            // area 
            if (isset($data['area']) && !empty($data['area'])) {
                $adsQuery->where('area_id', $data['area']);
            }
            // price
            if (isset($data['price']) && !empty($data['price'])) {
                $price = explode('-', $data['price']);
                if ($price[0] ==0  && $price[1] == 50000) {
                    // dd("a");
                  // $adsQuery->whereBetween('fixed_price', [$price[0], $price[1]]);
                   $adsQuery->where('fixed_price', '>=',$price[0]);
                }
                elseif($price[0] >=0  && $price[1] < 50000){
                    // dd("b");
                    $adsQuery->whereBetween('fixed_price', [$price[0], $price[1]]);
                }else{
                    // dd("c");
                    $adsQuery->where('fixed_price', '>=',$price[0]);
                }
                // ================= Old Filter 
                // if ($price[1] <= 50000) {
                //     $adsQuery->whereBetween('fixed_price', [$price[0], $price[1]]);
                //     // ->whereBetween('created_at', [$start, $end])
                // }
            }

            //   attribute base filter
            if (isset($data['attribute']) && !empty($data['attribute'])) {

                $attributeOption = [];
                foreach ($data['attribute'] as $key) {
                    $attributeOption[] =   $key['option_id'];
                }
                $adsQuery->whereHas('advertisement_attributes', function ($q) use ($attributeOption) {
                    $q->whereIn('value', $attributeOption);
                });
            }

            $adsQuery->with([
                'user',
                'advertisement_images',
                'user_wishlist' => function ($q) use ($user) {
                    $q->select('id');
                },
                'city' => function ($q) {
                    $q->select('id', 'title');
                },
                'area' => function ($q) {
                    $q->select('id', 'title', 'city_id');
                },
                'location' => function ($q) {
                    $q->select('id', 'title');
                },
                'sub_business_category' => function ($q) {
                    $q->select('id', 'title', 'parent_id', 'color');
                },
                'sub_business_category.parent' => function ($q) {
                    $q->select('id', 'title', 'icon', 'color');
                }
            ]);
            // =================== after comming problem sorting related ============================
            // if (isset($data['is_sort']) && $data['is_sort'] == 1 && !empty($data['sortby'])) {
            //     if ($data['sortby'] == "old") {
            //         $adsQuery->orderBy('id', 'DESC');
            //     }
            //     if ($data['sortby'] == "new") {
            //         $adsQuery->orderBy('id', 'ASC');
            //     }
            //     if ($data['sortby'] == "min_price") {
            //        // $adsQuery->orderBy('min_price', 'DESC');
            //         $adsQuery->orderBy('min_price', 'ASC');
            //     }
            //     if ($data['sortby'] == "max_price") {
            //         $adsQuery->orderBy('max_price', 'DESC');
            //     }
            // }
            // =================== after comming problem sorting related ============================
            //$adsQuery->orderBy('id', 'DESC');
            if (isset($data['is_sort']) && $data['is_sort'] == 1 && !empty($data['sortby'])) {

                if ($data['sortby'] == "old") {
                    //  $adsQuery->orderBy('id', 'DESC');
                    //   $adsQuery->orderBy('id', 'ASC');
                    $adsQuery->orderBy('created_at', 'ASC');
                }
                if ($data['sortby'] == "new") {
                    //  $adsQuery->orderBy('id', 'ASC');
                    //  $adsQuery->orderBy('id', 'DESC');
                    $adsQuery->orderBy('created_at', 'DESC');
                }
                if ($data['sortby'] == "min_price") {
                    $adsQuery->orderBy('fixed_price', 'ASC');
                }
                if ($data['sortby'] == "max_price") {
                    $adsQuery->orderBy('fixed_price', 'DESC');
                }
            }

            //=============== wishilist  ===========    ===========
            $advertisements = $adsQuery->get();
            // dd($advertisements)->toArray();
            $adsArr = array();
            foreach ($advertisements as $key => $ad) {
                $user_wishlist = UserWishlist::where(['advertisement_id' => $ad->id, 'user_id' => $user->id])->count();
                if ($user_wishlist > 0) {
                    $is_wishlisted = 1;
                } else {
                    $is_wishlisted = 0;
                }
                $advertisements[$key]['is_wishlisted'] = $is_wishlisted;
            }
            //   dd($advertisements->toArray());
            //================= wishlist =====================

            /*filter Attribute*/
            if (isset($request->filterAttribute) && count($request->filterAttribute) > 0) {
                $attrData = [];
                foreach ($request->filterAttribute as $attr) {
                    $attrData[] = $attr['option_id'];
                }
                $adsQuery->whereHas('advertisement_attributes', function ($q) use ($attrData) {
                    $q->whereIn('value', $attrData);
                });
            }

            /*Front Filter*/
            $adsQuery->frontfilter($request);

            /*Category Child and main category Filter*/
            if (!empty($request->sub_category_id)) {
                $adsQuery->where('sub_business_category_id', $request->sub_category_id);
                if (!empty($request->category)) {
                    $adsQuery->where('business_category_id', $request->category);
                }
            }

            /*Status Check*/
            $adsQuery->status(1);
            $ads = $adsQuery->get();
            $ads =  $advertisements;

            // $adsArr = array();
            // foreach($ads as $key => $ad){
            //     $user_wishlist = UserWishlist::where(['advertisement_id' => $ad->id, 'user_id' => $user->id])
            //     ->count();
            //     if ($user_wishlist > 0) {
            //         $is_wishlisted = 1;
            //     }
            //     else{
            //         $is_wishlisted = 0;
            //     }
            //     $ads[$key]['is_wishlisted'] = $is_wishlisted;
            // }

            // $ads = Advertisement::with('advertisement_images')->where('sub_business_category_id', $request->sub_category_id)->get();
            if (count($ads) > 0) {
                return $this->sendResponse($ads, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function addDetail(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'ads_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $ads = Advertisement::with([
                'advertisement_images', 'user_reviews.user', 'sellerDetail', 'advertisement_attributes', 'advertisement_attributes.attribute', 'city' => function ($q) {
                    $q->select('id', 'title');
                },
                'area' => function ($q) {
                    $q->select('id', 'title', 'city_id');
                }
            ])->where('id', $data['ads_id'])->first();
            if (!empty($ads)) {
                // $similarAds = Advertisement::with([
                //     'advertisement_images', 'sellerDetail', 'city' => function ($q) {
                //         $q->select('id', 'title');
                //     },
                //     'area' => function ($q) {
                //         $q->select('id', 'title', 'city_id');
                //     }
                // ])->where('business_category_id', $ads->business_category_id)->get();
                // $ads['similarAds'] = $similarAds;
                //============================= is_feautre ===================================    
                if (!empty($ads->advertisement_attributes)) {
                    $feature = [];
                    $i = 0;
                    foreach ($ads->advertisement_attributes as $key) {

                        $feature[$i]['value'] = $key->value;
                        $feature[$i]['name'] = $key->attribute->name;
                    }
                    $ads['featured'] = $feature;
                }
                //======================================= close is_feautre==================================================
                //================================ check discusion  =======================================
                //  dd($ads->sellerDetail->id);
                // dd($ads->sellerDetail->toArray());
                //   $discussion = Discussion::where('sender_id', $user->id)->where('receiver_id', $ads->sellerDetail->id)->first();
                $discussion = Discussion::where('advertisement_id',  $data['ads_id'])
                    ->where('sender_id', $user->id)
                    //  ->orwhere('receiver_id',$user->id)  
                    ->first();

                if (!empty($discussion)) {
                    $ads['discussion_id'] = $discussion->id;
                } else {
                    $ads['discussion_id'] = null;
                }


                //========================================= close check discusion ================================================
                $similarAdverts = Advertisement::with([
                    'user', 'advertisement_images', 'city' => function ($q) {
                        $q->select('id', 'title');
                    },
                    'area' => function ($q) {
                        $q->select('id', 'title', 'city_id');
                    },
                    'sub_business_category' => function ($q) {
                        $q->select('id', 'title', 'parent_id', 'color');
                    }, 'sub_business_category.parent' => function ($q) {
                        $q->select('id', 'title', 'icon', 'color');
                    }
                ])
                    ->where('business_category_id', $ads->business_category_id)
                    ->where('id', '!=', $ads->id)
                    ->where('is_publish', 1)
                    ->status(1)
                    ->take(10)
                    ->get();

                $ads['similarAds'] = $similarAdverts;
                // Is wishlist check

                $user_wishlist = UserWishlist::where(['advertisement_id' => $ads->id, 'user_id' => $user->id])->count();

                if ($user_wishlist > 0) {
                    $is_wishlisted = 1;
                } else {
                    $is_wishlisted = 0;
                }
                $ads['is_wishlisted'] = $is_wishlisted;

                return $this->sendResponse($ads, $this->messageDefault('Ads found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }


    public function applyForFeatured(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'ads_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $ads = Advertisement::where('id', $request->ads_id)->where('status', 1)->first();
            // echo "<prE>"; print_r($ads); die; 
            if (!empty($ads)) {

                Advertisement::where('id', $ads->id)->update(['is_featured' => 2]);
                $data = (object) [];
                return $this->sendResponse($data, $this->messageDefault('Send request successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    //============================================== change publis_status ====================================
    public function changeStatusPublish(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        // dd($user->id);
        try {
            $validator = Validator::make($request->all(), [
                'ads_id' => 'required|integer|',
                'is_publish' => 'required|integer|'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $ads = Advertisement::where('id', $request->ads_id)->where('status', 1)->first();

            if (!empty($ads)) {

                if ($request->is_publish == 0) {
                    Advertisement::where('id', $ads->id)->update(['is_publish' => $request->is_publish, 'is_featured' => 0]);
                } else {
                    Advertisement::where('id', $ads->id)->update(['is_publish' => $request->is_publish]);
                }


                $data = (object) [];
                return $this->sendResponse($data, $this->messageDefault('Change status request successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }
    //==============================================close change publis_status ====================================

    public function myAdsListing(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {

            $validator = Validator::make($request->all(), [
                'status' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            if ($request->status == 5) {
                $ads = Advertisement::with([
                    'advertisement_images', 'business_category', 'user_reviews.user', 'sellerDetail', 'city' => function ($q) {
                        $q->select('id', 'title');
                    },
                    'area' => function ($q) {
                        $q->select('id', 'title', 'city_id');
                    }
                ])->where('user_id', $user->id)->get();
            } else {
                $ads = Advertisement::with([
                    'advertisement_images', 'business_category', 'user_reviews.user', 'sellerDetail', 'city' => function ($q) {
                        $q->select('id', 'title');
                    },
                    'area' => function ($q) {
                        $q->select('id', 'title', 'city_id');
                    }
                ])->where('user_id', $user->id)->where('status', $request->status)->get();
            }



            if (count($ads) > 0) {
                // $similarAds = Advertisement::with('advertisement_images', 'sellerDetail')->where('business_category_id', $ads->business_category_id)->get();
                // $ads['similarAds'] = $similarAds;

                // // Is wishlist check

                // $user_wishlist = UserWishlist::where(['advertisement_id' => $ads->id, 'user_id' => $user->id])->count();

                // if ($user_wishlist > 0) {
                //     $is_wishlisted = 1;
                // }
                // else{
                //     $is_wishlisted = 0;
                // }
                // $ads['is_wishlisted'] = $is_wishlisted;

                return $this->sendResponse($ads, $this->messageDefault('Ads list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Ads list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function myAdsStatus(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {

            $activeAdvertisements = Advertisement::where('status', 1)->where('user_id', $user->id)->count();
            $draftAdvertisements = Advertisement::where('status', 0)->where('user_id', $user->id)->count();
            $reviewAdvertisements = Advertisement::where('status', 2)->where('user_id', $user->id)->count();
            $declineAdvertisements = Advertisement::where('status', 3)->where('user_id', $user->id)->count();
            $closeAdvertisements = Advertisement::where('status', 4)->where('user_id', $user->id)->count();
            $adsStatus['active'] = $activeAdvertisements;
            $adsStatus['draft'] = $draftAdvertisements;
            $adsStatus['review'] = $reviewAdvertisements;
            $adsStatus['decline'] = $declineAdvertisements;
            $adsStatus['close'] = $closeAdvertisements;
            return $this->sendResponse($adsStatus, $this->messageDefault('Ads Status list found successfully.'));
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function areaList(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'city_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $areas = Area::where('city_id', $request->city_id)->status(1)->get();
            if (count($areas) > 0) {
                return $this->sendResponse($areas, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function add(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        // Log::info(print_r($data, true));
        //  die; 
        // try {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'area_id' => 'required',
            'city_id' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            // 'status' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
        }

        $advertisement = new Advertisement();
        $advertisement->title = $data['title'];
        $advertisement->description = $data['description'];
        $advertisement->area_id = $data['area_id'];
        $advertisement->city_id = $data['city_id'];
        $advertisement->user_id = $user->id;
        $advertisement->business_category_id = $data['category_id'];
        //$advertisement->status = $data['status'];
        $advertisement->sub_business_category_id = $data['sub_category_id'];
        $advertisement->price_type = isset($data['price_type']) ? $data['price_type'] : "";
        $advertisement->fixed_price = isset($data['fixed_price']) ? $data['fixed_price'] : "";
        if ($data['price_type'] == '1') {
            $advertisement->fixed_price = isset($data['max_price']) ? $data['max_price'] : "";
        }
        $advertisement->min_price = isset($data['min_price']) ? $data['min_price'] : "";
        $advertisement->max_price = isset($data['max_price']) ? $data['max_price'] : "";
        $advertisement->hashtag = isset($data['hashtag']) ? $data['hashtag'] : "";
        $advertisement->save();

        //================== hashtag add ==============================================
        if (!empty($data['hashtags'])) {
            //  $advertisement =  $advertisement->id;
            if ($request->hashtags) {
                $advertisement->advertisement_hashtags()->delete();
                $hashtagsObj = [];
                foreach ($request->hashtags as $value) {
                    $hashtagsObj[] = new AdvertisementHashtag(['hashtag_id' => $value['id']]);
                }
                if (!empty($hashtagsObj)) {
                    $advertisement->advertisement_hashtags()
                        ->saveMany($hashtagsObj);
                }
            }
            $advertisement['hashtags'] = $request->hashtags;
        }

        //================== has tag close ==============================================
        if ($advertisement) {
            if (isset($data['image']) && count($data['image']) > 0) {
                foreach ($data['image'] as $key => $value) {
                    $ad_photo = Str::random(6) . '.' . $value['image_name']->getClientOriginalExtension();
                    $value['image_name']->move(storage_path('app/public/advertisements/'), $ad_photo);
                    $input['advertisement_id'] = $advertisement->id;
                    $input['image_name'] = $ad_photo;
                    $input['main'] = $value['main'];
                    AdvertisementImage::insert($input);
                }
            }

            if (isset($data['attribute']) && count($data['attribute']) > 0) {
                foreach ($data['attribute'] as $k => $val) {
                    $input1['advertisement_id'] = $advertisement->id;
                    $input1['attribute_id'] = $val['attribute_id'];
                    $input1['value'] = $val['value'];

                    AdvertisementAttribute::insert($input1);
                }
            }

            return $this->sendResponse($advertisement, $this->messageDefault('ADS Post successfully.'));
        } else {
            return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
        }

        //    } catch (\Exception $e) {

        //      return $this->sendError($this->messageDefault('oops'), '', '200');
        //    }
    }

    public function edit(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        //  dd($data);
        // try {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'area_id' => 'required',
            'city_id' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
        }

        $advertisement = Advertisement::find($request->id);
        // dd($advertisement);
        $advertisement->title = $data['title'];
        $advertisement->description = $data['description'];
        $advertisement->area_id = $data['area_id'];
        $advertisement->city_id = $data['city_id'];
        $advertisement->business_category_id = $data['category_id'];
        $advertisement->sub_business_category_id = $data['sub_category_id'];
        $advertisement->price_type = isset($data['price_type']) ? $data['price_type'] : "";

        $advertisement->fixed_price = isset($data['fixed_price']) ? $data['fixed_price'] : "";
        if (!empty($data['price_type'])) {
            if (($data['price_type'] == '1')) {
                $advertisement->fixed_price = isset($data['max_price']) ? $data['max_price'] : "";
            }
        }

        $advertisement->min_price = isset($data['min_price']) ? $data['min_price'] : "";
        $advertisement->max_price = isset($data['max_price']) ? $data['max_price'] : "";
        $advertisement->hashtag = isset($data['hashtag']) ? $data['hashtag'] : "";
        $advertisement->status = 0;
        $advertisement->admin_approve = 0;
        $advertisement->is_publish = 0;
        $advertisement->save();

        //================== hashtag edit ==============================================
        if (!empty($data['hashtags'])) {
            //  $advertisement =  $advertisement->id;
            if ($request->hashtags) {
                $advertisement->advertisement_hashtags()->delete();
                $hashtagsObj = [];
                foreach ($request->hashtags as $value) {
                    $hashtagsObj[] = new AdvertisementHashtag(['hashtag_id' => $value['id']]);
                }
                if (!empty($hashtagsObj)) {
                    $advertisement->advertisement_hashtags()
                        ->saveMany($hashtagsObj);
                }
            }
            $advertisement['hashtags'] = $request->hashtags;
        }

        //================== has tag close ==============================================

        if ($advertisement) {
            if (isset($data['image']) && !empty($data['image'])) {
                AdvertisementImage::where('advertisement_id', $advertisement->id)->delete();
                foreach ($data['image'] as $key => $value) {
                    $ad_photo = Str::random(6) . '.' . $value['image_name']->getClientOriginalExtension();
                    $value['image_name']->move(storage_path('app/public/advertisements/'), $ad_photo);
                    $input['advertisement_id'] = $advertisement->id;
                    $input['image_name'] = $ad_photo;
                    $input['main'] = $value['main'];
                    AdvertisementImage::insert($input);
                }
            }


            if (isset($data['attribute']) && count($data['attribute']) > 0) {
                AdvertisementAttribute::where('advertisement_id', $advertisement->id)->delete();
                foreach ($data['attribute'] as $k => $val) {
                    $input1['advertisement_id'] = $advertisement->id;
                    $input1['attribute_id'] = $val['attribute_id'];
                    $input1['value'] = $val['value'];

                    AdvertisementAttribute::insert($input1);
                }
            }

            return $this->sendResponse($advertisement, $this->messageDefault('AD Updated successfully.'));
        } else {
            return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $advertisement = Advertisement::find($request->advertisement_id);
            $advertisement->status = $request->status;
            $advertisement->admin_approve = 0;
            $advertisement->is_publish = 0;
            $advertisement->save();
            if ($request->status == 2) {
                return $this->sendResponse($advertisement, $this->messageDefault('AD send for review successfully.'));
            } else {
                return $this->sendResponse($advertisement, $this->messageDefault('AD saved as draft successfully.'));
            }
            // return $this->sendResponse($advertisement, $this->messageDefault('ADS Edit successfully.'));
        } catch (\Throwable $th) {
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }
    // ============================ Delete advertisment ==================================================
    public function delete(Request $request)
    {
        $user = $request->get('Auth');
        // dd($user->id);
        try {
            $advertisement = Advertisement::where('user_id', $user->id)->where('id', $request->advertisement_id)->first();
            // dd($advertisement,$user);
            $advertisement->delete();
            return $this->sendResponse($advertisement, $this->messageDefault('ADS Deleted successfully.'));
        } catch (\Throwable $th) {
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }
    // ============================ Delete advertisment ==================================================
    // public function delete(Request $request)
    // {
    //     $user = $request->get('Auth');
    //     try {
    //         $advertisement = Advertisement::where('user_id', $user->id)->where('id', $request->advertisement_id)->first();
    //         // dd($advertisement,$user);
    //         $advertisement->delete();
    //         return $this->sendResponse($advertisement, $this->messageDefault('ADS Deleted successfully.'));
    //     } catch (\Throwable $th) {
    //         return $this->sendError($this->messageDefault('oops'), '', '200');
    //     }
    // }
    // ===================================================================================================


    public function uplaodImage(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'advertisement_id' => 'required',
                'image_name' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $image_name = time() . '.' . request()->image_name->getClientOriginalExtension();
            request()->image_name->move(storage_path('app/public/advertisements/'), $image_name);
            $adsImage = new AdvertisementImage();
            $adsImage->advertisement_id = $data['advertisement_id'];
            $adsImage->image_name = $image_name;
            $adsImage->save();
            if ($adsImage) {
                return $this->sendResponse($adsImage, $this->messageDefault('Image upload successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }


    public function deleteImage(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'image_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }

            $adsImage = AdvertisementImage::where('id', $data['image_id'])->delete();
            if ($adsImage) {
                $data = (object)[];
                return $this->sendResponse($data, $this->messageDefault('Image deleted successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function reviewRating(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'rating' => 'required',
                'review' => 'required',
                'advertisement_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $review = new UserReview();
            $review->user_id = $user->id;
            $review->advertisement_id = $data['advertisement_id'];
            $review->rating = $data['rating'];
            $review->review = $data['review'];
            $review->save();
            if ($review) {
                return $this->sendResponse($review, $this->messageDefault('Review saved successfully, Please wait while your review gets approve.'));
            } else {
                return $this->sendError($this->messageDefault('Ads not save.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function reviewList(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        //  dd($data['advertisement_id']);
        try {
            $validator = Validator::make($request->all(), [
                'advertisement_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            //   $reviews = UserReview::with('user')->where('advertisement_id', $data['advertisement_id'])->get();
            $reviews = UserReview::with('user',)->where('advertisement_id', $request->advertisement_id)->where('admin_approve', 1)->get();
            if (count($reviews) > 0) {
                return $this->sendResponse($reviews, $this->messageDefault('Review list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Review not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }


    public function addFilter(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {

            $ads = Advertisement::with('advertisement_images', 'user_reviews', 'sellerDetail')->where('id', $request->ads_id)->first();
            if (!empty($ads)) {
                return $this->sendResponse($ads, $this->messageDefault('Ads found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }


    public function editAds(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        //   try {

        $validator = Validator::make($request->all(), [
            'ads_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
        }

        $ads = Advertisement::with('advertisement_images', 'advertisement_hashtags.getHashtags', 'advertisement_attributes')->where('id', $request->ads_id)->first();

        //================== hash tag details  ==============================================
        //  dd($ads->advertisement_hashtags->toArray());
        $hashtags = [];
        $i = 0;
        if (count($ads->advertisement_hashtags) > 0) {
            foreach ($ads->advertisement_hashtags as $key) {
                // dd($key->hashtag_id);
                $hashtags[$i]['id'] = $key->hashtag_id;
                $hashtags[$i]['title'] = $key->getHashtags->title;
                $i++;
            }
            $ads['hashtags_datails'] = $hashtags;
        }

        //================== hash tag close ==============================================

        if (!empty($ads)) {
            return $this->sendResponse($ads, $this->messageDefault('Ads found successfully.'));
        } else {
            return $this->sendError($this->messageDefault('Ads not found.'), '', '200');
        }
        // } catch (\Exception $e) {
        //     dd($e)
        //     return $this->sendError($this->messageDefault('oops'), '', '200');
        // }
    }






    public function addSearch(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'keyword' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            //    $ads = Advertisement::with('advertisement_images')->where('title', 'like', '%' . $request->keyword . '%')->get();

            $adsQuery = Advertisement::with('user')
                //  ->where('is_featured', 1)
                ->where('status', 1)
                ->where('is_publish', 1)
                ->where('title', 'like', '%' . $request->keyword . '%')
                ->with([
                    'advertisement_images',
                    'city' => function ($q) {
                        $q->select('id', 'title');
                    },
                    'area' => function ($q) {
                        $q->select('id', 'title', 'city_id');
                    },
                ]);

            $ads = $adsQuery->get();
            // dd($ads->count());
            //=============== wishilist  ======================
            $advertisements = $adsQuery->get();

            $adsArr = array();
            foreach ($advertisements as $key => $ad) {
                $user_wishlist = UserWishlist::where(['advertisement_id' => $ad->id, 'user_id' => $user->id])->count();
                if ($user_wishlist > 0) {
                    $is_wishlisted = 1;
                } else {
                    $is_wishlisted = 0;
                }
                $ads[$key]['is_wishlisted'] = $is_wishlisted;
            }
            //   dd($advertisements->toArray());
            //================= wishlist =====================

            if (count($ads) == 0) {
                $subCategory = Category::where('status', 1)->where('title', 'like', '%' . $request->keyword . '%')->pluck('id');
                $ads = Advertisement::with('advertisement_images')->whereIn('business_category_id', $subCategory)->orWhereIn('sub_business_category_id', $subCategory)->get();
            }
            if (count($ads) > 0) {
                return $this->sendResponse($ads, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function addRemoveWish(Request $request)
    {
        $wishdata = (object)[];
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                'advertisement_id' => 'required'
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $advertisement = Advertisement::where(['id' => $request->advertisement_id, 'user_id' => $user->id])->count();

            if ($advertisement > 0) {
                return $this->sendError($this->messageDefault('Advertisement owner can\'t  add wishlist.'), '', '200');
            } else {
                $advertisementexist = Advertisement::where(['id' => $request->advertisement_id])->count();
                if ($advertisementexist > 0) {
                    $user_wishlist = UserWishlist::where(['advertisement_id' => $request->advertisement_id, 'user_id' => $user->id])
                        ->count();
                    if ($user_wishlist > 0) {
                        // die('if');
                        //  dd("asdfas");
                        UserWishlist::where(['advertisement_id' => $request->advertisement_id, 'user_id' => $user->id])->delete();
                        return $this->sendResponse($wishdata, $this->messageDefault('Advertisement has been removed from wishlist.'));
                    } else {
                        //die('elsds');
                        $storeData = [];
                        $storeData['user_id'] = $user->id;
                        $storeData['advertisement_id'] = $request->advertisement_id;
                        UserWishlist::create($storeData);
                        return $this->sendResponse($wishdata, $this->messageDefault('Advertisement has been added wishlist successfully.'));
                    }
                } else {
                    return $this->sendError($this->messageDefault('Advertisement not found.'), '', '200');
                }
            }
        } catch (\Exception $e) {
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    /**
     * Index the form for wishlist.
     * @return Response
     */
    public function getWishlist(Request $request)
    {
        try {
            $user = $request->get('Auth');
            $adsQuery = Advertisement::with('user')->where('is_publish', 1)->whereHas('user_wishlist', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with([
                'advertisement_images',
                'location' => function ($q) {
                    $q->select('id', 'title', 'area_id');
                },
                'location.area' => function ($q) {
                    $q->select('id', 'title', 'city_id');
                },
                'location.area.city' => function ($q) {
                    $q->select('id', 'title');
                },
                'location.area.city' => function ($q) {
                    $q->select('id', 'title');
                },
                'sub_business_category' => function ($q) {
                    $q->select('id', 'title', 'parent_id');
                },
                'sub_business_category.parent' => function ($q) {
                    $q->select('id', 'title');
                },
                'advertisement_images',
                'city' => function ($q) {
                    $q->select('id', 'title');
                },
                'area' => function ($q) {
                    $q->select('id', 'title', 'city_id');
                },
            ]);

            /*filter Attribute*/
            if (isset($request->filterAttribute) && count($request->filterAttribute) > 0) {
                $attrData = [];
                foreach ($request->filterAttribute as $attr) {
                    $attrData[] = $attr['option_id'];
                }
                $adsQuery->whereHas('advertisement_attributes', function ($q) use ($attrData) {
                    $q->whereIn('value', $attrData);
                });
            }

            /*Front Filter*/
            $adsQuery->frontfilter($request);

            /*Status Check*/
            $adsQuery->status(1);
            $advertisements = $adsQuery->get();
            if (count($advertisements) > 0) {
                return $this->sendResponse($advertisements, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }


    public function search(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {

            $query = Advertisement::with('advertisement_images')->where('is_publish', 1)->where('status', 1)
                ->with([
                    'city' => function ($q) {
                        $q->select('id', 'title');
                    },
                    'area' => function ($q) {
                        $q->select('id', 'title', 'city_id');
                    }
                ])
                ->newQuery();
            if (isset($data['category_id']) && !empty($data['category_id'])) {
                $query->where('business_category_id', $data['category_id']);
            }
            if (isset($data['sub_category_id']) && !empty($data['sub_category_id'])) {
                $query->where('sub_business_category_id', $data['sub_category_id']);
            }
            if (isset($data['keyword']) && !empty($data['keyword'])) {
                $query->where('title', 'like', '%' . $data['keyword'] . '%');
            }
            // if(isset($data['city_id']) && !empty($data['city_id'])) {
            //     $query->where('city_id',$data['city_id']);
            // }

            $ads = $query->paginate(10);
            // dd($ads->count());
            //=============== wishilist  ======================
            $advertisements = $ads;

            $adsArr = array();
            foreach ($advertisements as $key => $ad) {
                $user_wishlist = UserWishlist::where(['advertisement_id' => $ad->id, 'user_id' => $user->id])->count();
                if ($user_wishlist > 0) {
                    $is_wishlisted = 1;
                } else {
                    $is_wishlisted = 0;
                }
                $ads[$key]['is_wishlisted'] = $is_wishlisted;
            }
            //   dd($advertisements->toArray());
            //================= wishlist =====================


            if (count($ads) > 0) {
                return $this->sendResponse($ads, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    // ================================ Hashtag ========================================
    public function getHashtags(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        $validator = Validator::make($request->all(), [
            'q' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
        }
        try {

            $search = $request->q;

            //    $data = HashTag::select("id", "title")->where('title', 'LIKE', "%$$request->q%")->get();
            $data = HashTag::select("id", "title")->where('title', 'like', '%' .  $search . '%')->get();

            if (!empty($data->count() > 0)) {

                return $this->sendResponse($data, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }
    public function createHashtags(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        $validator = Validator::make($request->all(), [
            'title' =>  'required|regex:/^[a-zA-Z]+$/u|min:2|max:100|unique:hash_tags,title'

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
        }
        try {
            $user_id = $user->id;
            //  dd($user_id);
            $data = HashTag::create(['title' => $request->title, 'user_id' => $user_id]);
            if (!empty($data)) {
                return $this->sendResponse($data, $this->messageDefault('save_records'));
            } else {
                return $this->sendError($this->messageDefault('save_failed'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    // ================================Close Hastag ========================================

    public function planList(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            //  $subCategory = Subscription::where('user_id', $user->id)->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->pluck('category_id');
            $plans = Subscription::where('user_id', $user->id)->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->get();
            if (count($plans) > 0) {
                return $this->sendResponse($plans, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }
    // ============================= Check store description ====================================
    public function checkstoredescription(Request $request)
    { {
            $data = $request->all();
            $user = $request->get('Auth');
            try {

                if ($user->store_name != null && $user->description != null) {
                    $data = true;
                    return $this->sendResponse($data, $this->messageDefault('list found successfully.'));
                } else {
                    $data = false;
                    return $this->sendResponse($data, $this->messageDefault('list found successfully.'));
                }
            } catch (\Exception $e) {

                return $this->sendError($this->messageDefault('oops'), '', '200');
            }
        }
    }
    // ============================= checkstoreescription ====================================
}
