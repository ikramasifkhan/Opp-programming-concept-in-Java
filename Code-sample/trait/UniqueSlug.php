<?php


namespace App\Traits;


use Illuminate\Support\Str;

trait UniqueSlug
{
    public function makeSlug($content){
        $slug = str::slug(($content), "_");
        return $slug .'_'. substr(hexdec(uniqid()), -5);
    }
}
