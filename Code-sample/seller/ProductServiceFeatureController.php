<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeatureSponsorRequest;
use App\Models\Seller\Feature;
use App\Models\Seller\Product;
use App\Models\Seller\Service;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductServiceFeatureController extends Controller
{
    protected $productStatusCount;
    protected function featureSponsorCommonList($class, $classType, $featureType){
        if($featureType == 'featured'){
            $list = $class->with(['category', 'feature'])->whereHas('feature', function ($query) use($featureType, $classType){$query->where(['type'=>$featureType, 'featurable_type'=> $classType]);})->where(['organization_id'=>auth('seller')->user()->organization_id])->get();
        }else{
            $list = $class->with(['category', 'sponsor'])->whereHas('sponsor', function ($query) use($featureType, $classType){$query->where(['type'=>$featureType, 'featurable_type'=> $classType]);})->where(['organization_id'=>auth('seller')->user()->organization_id])->get();
        }
        return view('seller.feature.list', compact('list', 'classType', 'featureType'));

    }
    public function productFeatureList(){
        $class= new Product();
        return $this->featureSponsorCommonList($class, Product::class, 'featured');
    }
    public function productSponsorList(){
        $class= new Product();
        return $this->featureSponsorCommonList($class, Product::class, 'sponsored');
    }

    public function serviceFeatureList(){
        $class = new Service();
        return $this->featureSponsorCommonList($class, Service::class, 'featured');;
    }

    public function serviceSponsoredList(){
        $class = new Service();
        return $this->featureSponsorCommonList($class, Service::class, 'sponsored');;
    }

    //create the sponsor and feature
    protected function featureCreateCommonInfo($classType, $type, $slug)
    {
        if ($classType == Product::class) {
            $classType == Product::class;
            $product = Product::where('slug', $slug)->first();
            if (!$product) {
                abort(404);
            }
            return view('seller.feature.create', compact('product', 'classType', 'type'));
        }else{
            $classType == Service::class;
            $service = Service::where('slug', $slug)->first();
            if (!$service) {
                abort(404);
            }
            return view('seller.feature.create', compact('service', 'classType', 'type'));
        }

    }

    public function productFeatureCreate($slug)
    {
        return $this->featureCreateCommonInfo(Product::class, 'featured', $slug);
    }

    public function productSponsorCreate($slug){
        return $this->featureCreateCommonInfo(Product::class, 'sponsored', $slug);
    }
    public function serviceFeatureCreate($slug)
    {
        return $this->featureCreateCommonInfo(Service::class, 'featured',  $slug);
    }
    public function serviceSponsorCreate($slug)
    {
        return $this->featureCreateCommonInfo(Service::class, 'sponsored',  $slug);
    }

    protected function productServiceStoreCommonInfo($request, $classType, $type, $slug){
        if($classType == Product::class){
            $product = Product::select('id', 'slug', 'featured', 'sponsored', 'to_date')->where('slug', $slug)->first();
            $id = $product->id;
            $to_date = $product->to_date;
        }else{
            $service = Service::select('id', 'slug', 'featured', 'sponsored', 'to_date')->where('slug', $slug)->first();
            $id = $service->id;
            $to_date = $service->to_date;
        }
        if($request->start_date > $to_date or $request->end_date > $to_date){
            Toastr::error('Your request date is exceed to expire date.', 'Error');
            return redirect()->back();
        }
        $feature = new Feature();
        $feature->create([
            'type' => $type,
            'featurable_type' => $classType,
            'featurable_id' => $id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        Toastr::success('Your request send to the authority.', 'Success'); //show message after saving
        if($classType == Product::class){
            return redirect()->route('seller.product.index');
        }else {
            return redirect()->route('seller.service.index');
        }
    }
    public function productFeatureStore(StoreFeatureSponsorRequest $request, $slug)
    {
        return $this->productServiceStoreCommonInfo( $request, Product::class, 'featured', $slug);
    }
    public function productSponsorStore(StoreFeatureSponsorRequest $request, $slug)
    {
        return $this->productServiceStoreCommonInfo( $request, Product::class, 'sponsored', $slug);
    }

    public function serviceFeatureStore(StoreFeatureSponsorRequest $request, $slug)
    {
        return $this->productServiceStoreCommonInfo($request, Service::class, 'featured', $slug);
    }

    public function serviceSponsorStore(StoreFeatureSponsorRequest $request, $slug)
    {
        return $this->productServiceStoreCommonInfo($request, Service::class, 'sponsored', $slug);
    }
}
