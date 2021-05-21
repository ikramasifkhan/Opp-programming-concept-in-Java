<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Admin\Category;
use App\Models\District;
use App\Models\Division;
use App\Models\RenewHistory;
use App\Models\Seller\Feature;
use App\Models\Seller\Product;
use App\Models\Seller\Service;
use App\Models\Seller\Tag;
use App\Models\Upazila;
use App\Traits\Description;
use App\Traits\ProductServiceList;
use App\Traits\Queries\ServiceQueries;
use App\Traits\ServiceTrait;
use App\Traits\Count;
use App\Traits\UniqueSlug;
use App\Traits\UploadAble;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    use UploadAble, ProductServiceList, UniqueSlug, Description, ServiceTrait, Count;

    public $ffolder = '/image/product/feature-image';
    public function __construct()
    {
        $this->middleware('can:service.show')->only(['index', 'show', 'active', 'unapproved']);
    }

    public function index()
    {
        return $this->adminServiceList('admin.service.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $service = Service::with([
                'category'=>function($query){
                    $query->select('id', 'name');
                },
                'organization'=>function($query){
                    $query->select('id', 'name');
                },'ratingReviews', 'comments.user'])->findOrFail(decrypt($id));
            return view('admin.service.show', compact('service'));
        } catch (DecryptException $e){
           abort(404);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $service = ServiceQueries::findOrFailAnInstance($id);
        $tags = Tag::all();
        return view('admin.service.edit', compact('service', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        return $this->updateService($request, $service, 'admin');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->destroyService($id);
        return redirect()->back();
    }

    //get deleted data
    public function deleteForever($id)
    {
        $this->deleteForeverService($id);
        return redirect()->back();
    }

    //restore deleted data
    public function restore($id)
    {
        $this->restoreService($id);
        return redirect()->back();
    }

    public function getDeletedData()
    {
        $services = $this->deletedServiceList();
        return view('admin.service.trash', compact('services'));
    }
    public function active($id){
        $this->adminServiceActive($id);
        Toastr::success('Service active successfully.', 'Active');
        return redirect()->back();
    }
    public function unapproved($id){
        $this->adminUnapprovedService($id);
        Toastr::success('Service unapproved successfully.', 'Inactive');
        return redirect()->back();
    }
//delete data forever
}
