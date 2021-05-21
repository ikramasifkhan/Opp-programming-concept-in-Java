<?php


namespace App\Traits;


use App\Models\Admin\Category;
use App\Models\District;
use App\Models\Division;
use App\Models\Upazila;

trait Count
{
    public function itemCount($product,$sector, $type)
    {
        $division = Division::findOrFail($product->division_id);
        $district = District::findOrFail($product->district_id);
        $upazila = Upazila::findOrFail($product->upazila_id);
        if($sector=='product') {
            if($type=='inc') {
                $division->update(['products_count' => $division->products_count + 1]);
                $district->update(['products_count' => $district->products_count + 1]);
                $upazila->update(['products_count' => $upazila->products_count + 1]);
            }elseif ($type=='dec'){
                $division->update(['products_count' => $division->products_count - 1]);
                $district->update(['products_count' => $district->products_count - 1]);
                $upazila->update(['products_count' => $upazila->products_count - 1]);
            }
        }elseif ($sector=='service'){
            if($type=='inc') {
                $division->update(['services_count' => $division->services_count + 1]);
                $district->update(['services_count' => $district->services_count + 1]);
                $upazila->update(['services_count' => $upazila->services_count + 1]);
            }elseif ($type=='dec'){
                $division->update(['services_count' => $division->services_count - 1]);
                $district->update(['services_count' => $district->services_count - 1]);
                $upazila->update(['services_count' => $upazila->services_count - 1]);
            }
        }
        $category1 = Category::findOrFail($product->category_id);
        if($type=='inc') {
            $category1->update(['count' => $category1->count + 1]);
            if ($category1->parent_id) {
                $category2 = Category::findOrFail($category1->parent_id);
                $category2->update(['count' => $category2->count + 1]);
                if ($category2->parent_id) {
                    $category3 = Category::findOrFail($category2->parent_id);
                    $category3->update(['count' => $category3->count + 1]);
                }
            }
        }elseif ($type=='dec'){
            $category1->update(['count' => $category1->count - 1]);
            if ($category1->parent_id) {
                $category2 = Category::findOrFail($category1->parent_id);
                $category2->update(['count' => $category2->count - 1]);
                if ($category2->parent_id) {
                    $category3 = Category::findOrFail($category2->parent_id);
                    $category3->update(['count' => $category3->count - 1]);
                }
            }
        }
    }

}
