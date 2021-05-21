<?php


namespace App\Traits;


use App\Models\Admin\Category;
use App\Models\District;
use App\Models\Division;
use App\Models\Image;
use App\Models\RenewHistory;
use App\Models\Seller\Service;
use App\Models\Seller\Tag;
use App\Models\Upazila;
use App\Traits\Queries\ServiceQueries;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

trait ServiceTrait
{
    use Count, ServiceQueries;
    public function adminServiceList($view){
        if (\request('status') == 'all') {
            $service = new Service();
        } elseif (\request()->status == 'active') {
            $service = Service::where('status', 'active');
        } elseif (\request()->status == 'paused') {
            $service = Service::where('status', 'paused');
        } elseif (\request()->status == 'pending') {
            $service = Service::where('status', 'pending');
        } elseif (\request()->status == 'unapproved') {
            $service = Service::where('status', 'unapproved');
        }elseif (\request()->status == 'expired'){
            $service = Service::where('status', 'expired');
        }
        else{
            $service = new Service();
        }
        $services = $service->with(['organization', 'category' => function ($query) {$query->select('id', 'name');}])->latest();
        if (\request()->ajax()) {
            return DataTables::of($services)
                ->addIndexColumn()
                ->addColumn('name', function ($service) {
                    return view('components.nameWithImage', [
                        'item'=>$service,
                        'name'=>$service->name,
                        'picture_type'=>'featured_image',
                        'image'=>asset(config('imagepath.feature')).$service->featured_image,
                        'default_picture'=>asset('images/default_product.png'),
                        'route'=>route('service.show', encrypt($service->id))
                    ]);
                })
                ->addColumn('organization', function ($service) {
                    return '<a href="' . route('organization.show.home', encrypt($service->organization_id)) . '">' . $service->organization->name . '</a>';
                })
                ->addColumn('action', function ($service) {
                    return view('admin.service.action-button', compact('service'));
                })
                ->addColumn('status', function ($services) {
                    if ($services->status == 'active') {
                        return '<label class="badge badge-success">' . ucfirst($services->status) . '</label>';
                    } else if($services->status == 'paused'){
                        return '<label class="badge badge-secondary">' . ucfirst($services->status) . '</label>';
                    }else if($services->status == 'pending'){
                        return '<label class="badge badge-warning">' . ucfirst($services->status) . '</label>';
                    }else if($services->status == 'unapproved'){
                        return '<label class="badge badge-danger">' . ucfirst($services->status) . '</label>';
                    }else{
                        return '<span class="text-danger font-weight-bolder">' . ucfirst($services->status) . '</span>';
                    }

                })
                ->addColumn('date', function ($service) {
                    if($service->from_date and $service->to_date){
                        return date('d M', strtotime($service->from_date)). ' to '.date('d M', strtotime($service->to_date));
                    }else{
                        return '<span class="text-danger">Date range will show after first activation</span>';
                    }
                })
                ->rawColumns(['action', 'name', 'organization', 'status', 'action', 'date'])
                ->tojson();
        }

        return view($view);
    }

    public function storeService($request){
        $arr = array();
        foreach ($request->featureList as $featured) {
            $array = array_push($arr, $featured['features']);
        }
        $service = new Service();
        $service->features = json_encode($arr);
        $service->fill($request->all());
        $service->organization_id = Auth::guard('seller')->user()->organization->id;
        $service->division_id = Auth::guard('seller')->user()->organization->division_id;
        $service->district_id = Auth::guard('seller')->user()->organization->district_id;
        $service->upazila_id = Auth::guard('seller')->user()->organization->upazila_id;
        if (strip_tags($request->worldwide_delivery) == "on") {
            $service->worldwide_delivery = 1;
        } else {
            $service->worldwide_delivery = 0;
        }

        if ($request->hasFile('featured_image')) {
            $featured_image = $request->featured_image;
            $filename = $this->uploadOne($featured_image, 700, 700, config('imagepath.feature'));
            $service->featured_image = $filename;
            $service->slug = $this->makeSlug($request->name);
        }
        $description=$this->addDescription($request->description);
        $service->description = $description;
        $service->save();
        //upload service image
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $key => $image) {
                $filename = $this->uploadOne($image, 700, 700, config('imagepath.service'));
                $img = new Image();
                $img->imageable_id = $service->id;
                $img->imageable_type = Service::class;
                $img->filename = $filename;
                $img->save();
            }
        }
        //insert product tags
        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $res = Tag::where('name', $tag)->first();
                if (!$res) {
                    $newTag = Tag::create(['name' => $tag]);
                    $service->tags()->attach($newTag->id);
                } elseif ($res) {
                    $service->tags()->attach($res->id);
                }
            }
        }

        Toastr::success("<strong>$service->name</strong>" . " Created Successfully", "Success");
        return response()->json(['success' => true, 'route' => route('seller.service.index')]);
    }
    public function updateService($request, $service, $loginType){
        $request->validate([
            'featured_image' => 'image|mimes:jpg,png|max:512',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png|max:512',

        ]);
        if($service->status=='active'){
            $this->itemCount($service,'service','dec');
        }
        $service->update(['status' => 'pending']);

        $arr = array();
        if ($request->featureList) {
            foreach ($request->featureList as $featured) {
                $array = array_push($arr, $featured['features']);
            }
        }
        $service->update($request->except('slug'));
        $service_image_number = $service->images()->count();
        $uploadable_image = 5 - $service_image_number;
        if ($request->hasFile('featured_image')) {
            $featured_image = $request->featured_image;
            File::delete(public_path($this->ffolder . $service->featured_image));
            $filename = $this->uploadOne($featured_image, 700, 700, config('imagepath.feature'));
            $service->update(['featured_image' => $filename]);
        }
        if ($request->hasFile('image')) {
            $request_image = count($request->image);
            if ($request_image <= $uploadable_image) {
                foreach ($request->file('image') as $key => $image) {
                    $filename = $this->uploadOne($image, 700, 700, config('imagepath.service'));
                    $img = new Image();
                    $img->imageable_id = $service->id;
                    $img->imageable_type = Service::class;
                    $img->filename = $filename;
                    $img->save();
                }
            } else {
                Toastr::error("You Can Upload Only " . $uploadable_image . " Images");
                return redirect()->route('seller.service.index');
            }
        }
        $description=$this->addDescription($request->description);
        $service->description = $description;
        $service->update();

        if($loginType == 'seller'){
            return response()->json(['success' => true, 'route' => route('seller.service.index')]);
        }
        if($loginType == 'admin'){
            return response()->json(['success' => true, 'route' => route('service.index')]);
        }
    }
    public function destroyService($id)
    {
        $service = ServiceQueries::findOrFailAnInstance($id);
        if ($service->status == 'active') {
            $this->itemCount($service,'service','dec');
        }
        $service->delete();
        Toastr::success('Service' . ' ' . "<strong>$service->name</strong>" . ' ' . 'Deleted Successfully', 'Deleted.');
    }

    public function deletedServiceList(){
         return Service::onlyTrashed()->get();
    }

    public function restoreService($id){
        $service = ServiceQueries::findOrFailWithTrashed($id);
        if ($service->status == 'active') {
            $this->itemCount($service,'service','inc');
        }
        $service->restore();
        Toastr::success('Service' . ' ' . "<strong>$service->name</strong>" . ' ' . 'Restored Successfully', 'Restored.');
    }

    public function deleteForeverService($id){
        $service = ServiceQueries::findOrFailWithTrashed($id);

        $service->forceDelete();
        Toastr::success('Service' . ' ' . "<strong>$service->name</strong>" . ' ' . 'Deleted Permanently', 'Deleted');

    }

    public function adminServiceActive($id){
        $service = ServiceQueries::findOrFailAnInstance($id);
        $this->itemCount($service,'service','inc');

        if($service->from_date){
            $renewHistory = RenewHistory::where(['renewable_id'=>$service->id, 'renewable_type'=>Service::class])->latest()->take(1)->first();
            if($renewHistory) {
                $renewHistory->update(['status' => 'approved']);
                $service->from_date = Carbon::now()->format('Y-m-d h:i:s');
                $service->to_date = Carbon::now()->addMonths($renewHistory->duration)->format('Y-m-d h:i:s');
                $service->status = 'active';
                $service->update();
            }else{
                $service->update(['status'=>'active']);
            }
        }
        if ($service->from_date == null) {
            $fromDate = Carbon::now()->format('Y-m-d h:i:s');
            $toDate = Carbon::now()->addMonth()->format('Y-m-d h:i:s');
            $service->from_date = $fromDate;
            $service->to_date = $toDate;
            $service->update(['status'=>'active']);
        }

    }

    public function sellerServiceActive($id){
        $service = ServiceQueries::findOrFailAnInstance($id);
        if ($service->status == 'paused' and $service->organization_id == \auth('seller')->user()->organization_id) {
            $this->itemCount($service,'service','inc');
            $service->update([
                'status' => 'active'
            ]);
            Toastr::success('Service active successfully', 'Active.');
            return redirect()->back();
        } else {
            Toastr::success('Unauthorized access', 'Active.');
            return redirect()->back();
        }
    }

    public function adminUnapprovedService($id){
        $service = ServiceQueries::findOrFailAnInstance($id);
        if($service->status=='active'){
            $this->itemCount($service,'service','dec');
        }
        $service->update(['status'=>'unapproved']);
        $renewHistory = RenewHistory::where(['renewable_id'=>$service->id, 'renewable_type'=>Service::class])->latest()->take(1)->first();
        if($renewHistory){
            $renewHistory->update(['status'=>'unapproved']);
        }
    }

    public function sellerPausedService($id){
        $service = ServiceQueries::findOrFailAnInstance($id);
        if ($service->status == 'active' and $service->organization_id == \auth('seller')->user()->organization_id) {
            $this->itemCount($service,'service','dec');
            $service->update([
                'status' => 'paused'
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function sellerRenewService($request, $id){
        $service = ServiceQueries::findOrFailAnInstance($id);

        $renewalDate = Carbon::createFromFormat('Y-m-d H:i:s', $service->to_date)->addMonths($request->duration)->format('Y-m-d H:i:s');
        if ($renewalDate > \auth('seller')->user()->organization->expire_at) {
            Toastr::error('Opps! your requested date is grater than your organization expire date', 'Error..');
            return redirect()->back();
        }else{
            $renewHistory = new RenewHistory();
            $renewHistory->create([
                'renewable_id' => $service->id,
                'renewable_type' => Service::class,
                'duration' => $request->duration
            ]);
            $fromDate = Carbon::now()->format('Y-m-d h:i:s');
            $toDate = Carbon::now()->addMonths($request->duration)->format('Y-m-d h:i:s');
            $service->from_date = $fromDate;
            $service->to_date = $toDate;
            $service->status = 'pending';
            $service->update();

            Toastr::success('Your request sent successfully', 'Renew.');
            return redirect()->route('seller.service.index');
        }

    }
}
