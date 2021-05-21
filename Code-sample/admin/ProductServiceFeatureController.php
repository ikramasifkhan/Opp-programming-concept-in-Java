<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Organizations;
use App\Models\Seller\Feature;
use App\Models\Seller\Product;
use App\Models\Seller\Service;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductServiceFeatureController extends Controller
{
    public function listCommonInfo($classType, $type)
    {
        $featuresList = Feature::with(['featurable', 'featurable.organization', 'featurable.category'])->where(['type' => $type, 'featurable_type' => $classType]);
        $statusCount = $featuresList->get();
        if (\request()->status == 'approved') {
            $features = $featuresList->where('status', 'approved')->latest();
        } elseif (\request()->status == 'pending') {
            $features = $featuresList->where('status', 'pending')->latest();
        } elseif (\request()->status == 'unapproved') {
            $features = $featuresList->where('status', 'unapproved')->latest();
        } else {
            $features = $featuresList->latest();
        }

        //$features = $feature->with('featurable', 'featurable.organization', 'featurable.category')->where(['type' => $type, 'featurable_type' => $classType])->latest();
        if (\request()->ajax()) {
            return DataTables::of($features)->addIndexColumn()
                ->addColumn('organization', function ($feature) {
                    return '<a href="' . route('organization.show.home', encrypt($feature->featurable->organization_id)) . '">' . $feature->featurable->organization->name . '</a>';

                })
                ->addColumn('name', function ($feature) use ($classType) {
                    return view('components.nameWithImage', [
                        'name' => $feature->featurable->name,
                        'image' => $feature->featurable->featured_image ? asset(config('imagepath.feature')) . $feature->featurable->featured_image : asset('images/default_product.png'),
                        'route' => ($classType == Product::class) ? route('product.show', encrypt($feature->featurable->id)) : route('service.show', encrypt($feature->featurable->id))
                    ]);
                })
                ->addColumn('status', function ($feature) {
                    if ($feature->status == 'approved') {
                        return '<label class="badge badge-success">' . ucfirst($feature->status) . '</label>';
                    } else if ($feature->status == 'pending') {
                        return '<label class="badge badge-warning">' . ucfirst($feature->status) . '</label>';
                    } else {
                        return '<label class="badge badge-danger">Unapproved</label>';
                    }

                })
                ->addColumn('category', function ($feature) {
                    return $feature->featurable->category->name;
                })
                ->addColumn('date', function ($feature){
                    return date('d-M-y', strtotime($feature->start_date)).' To '.date('d-M-y', strtotime($feature->end_date));
                })
                ->addColumn('action', function ($feature) use ($classType, $type) {
                    return view('admin.featureAndSponsor.action-button', compact('feature', 'classType', 'type'));
                })
                ->rawColumns(['organization', 'name', 'status', 'category', 'date', 'action'])->tojson();
        }
        if ($classType == Product::class) {
            return view('admin.featureAndSponsor.product.list', compact('classType', 'type', 'statusCount'));
        } elseif ($classType == Service::class) {
            return view('admin.featureAndSponsor.service.list', compact('classType', 'type', 'statusCount'));
        }

    }

    public function featureProductList()
    {
        return $this->listCommonInfo(Product::class, 'featured');
    }

    public function sponsorProductList()
    {
        return $this->listCommonInfo(Product::class, 'sponsored');
    }

    public function serviceSponsorList()
    {
        return $this->listCommonInfo(Service::class, 'sponsored');
    }

    public function serviceFeatureList()
    {
        return $this->listCommonInfo(Service::class, 'featured');
    }

    public function sponsorOrganizationList(){
        $classType = Organizations::class;
        $featuresList = Feature::with('featurable')->where('featurable_type', Organizations::class);
        $statusCount = $featuresList->get();
        if(\request()->status == 'approved'){
            $features = $featuresList->where('status', 'approved')->latest();
        }elseif (\request()->status == 'pending'){
            $features = $featuresList->where('status', 'pending')->latest();
        }elseif (\request()->status == 'unapproved'){
            $features = $featuresList->where('status', 'unapproved')->latest();
        }else{
            $features = $featuresList->latest();
        }
        if (\request()->ajax()) {
            return DataTables::of($features)->addIndexColumn()
                ->addColumn('name', function ($feature) {
                    return view('components.nameWithImage', [
                        'name' => $feature->featurable->name,
                        'image' => $feature->featurable->logo ? asset(config('imagepath.companyLogo')) . $feature->featurable->logo : asset('images/default_logo.png'),
                        'route' => route('organization.show.home', encrypt($feature->featurable_id))
                    ]);
                })
                ->addColumn('status', function ($feature) {
                    if ($feature->status == 'approved') {
                        return '<label class="badge badge-success">' . ucfirst($feature->status) . '</label>';
                    } else if ($feature->status == 'pending') {
                        return '<label class="badge badge-warning">' . ucfirst($feature->status) . '</label>';
                    } else {
                        return '<label class="badge badge-danger">Unapproved</label>';
                    }

                })
                ->addColumn('date', function ($feature){
                    return date('d-M-y', strtotime($feature->start_date)).' To '.date('d-M-y', strtotime($feature->end_date));
                })
                ->addColumn('action', function ($feature) use ($classType){
                    return view('admin.featureAndSponsor.action-button', compact('feature', 'classType'));
                })
                ->rawColumns(['name', 'status', 'date', 'action'])->tojson();
        }
        return view('admin.featureAndSponsor.organization.list', compact('statusCount', 'classType'));
    }

    public function approved($id)
    {
        try {
            $feature = Feature::findOrfail(decrypt($id));
            $feature->update([
                'status' => 'approved',
            ]);
            Toastr::success('Approved successfully', "Approved");
            return redirect()->back();
        } catch (DecryptException $e) {
            abort(404);
        }
    }

    public function unapproved($id)
    {
        try {
            $feature = Feature::findOrfail(decrypt($id));
            $feature->update([
                'status' => 'unapproved',
            ]);
            Toastr::success('Unapproved successfully', "Unapproved");
            return redirect()->back();
        } catch (DecryptException $e) {
            abort(404);
        }

    }
}
