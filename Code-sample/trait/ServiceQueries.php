<?php


namespace App\Traits\Queries;


use App\Models\Seller\Service;
use Illuminate\Contracts\Encryption\DecryptException;

trait ServiceQueries
{
    public static function findOrFailAnInstance($id){
        try{
           return $service = Service::findOrFail(decrypt($id));
        }catch (DecryptException $e){
            abort(404);
        }
    }

    public static function findOrFailWithTrashed($id){
        try {
            return $service = Service::withTrashed()->findOrFail(decrypt($id));
        } catch (DecryptException $e) {
            abort(404);
        }
    }

}
