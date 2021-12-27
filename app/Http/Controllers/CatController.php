<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cat\CatImageUploadRequest;
use App\Http\Requests\Cat\MyFavoriteCatGetRequest;
use App\Http\Requests\Cat\CatDescriptionGetRequest;

class CatController extends Controller
{
    public function uploadCatImage(CatImageUploadRequest $request)
    {
        $path = $request->file('cat_image')->storeAs('cat_images', now()->format('YmdHms').".jpeg");

        return response(['success' => 1, 'path' => $path]);
    }

    public function getMyFavoriteCat(MyFavoriteCatGetRequest $request)
    {
        $name = $request->user()->name;

        switch ($name) {
            case 'Chris':
                $breed = 'shibainu';
                break;
            case 'John':
                $breed = 'golden retriever';
                break;
            default:
                $breed = 'chihuahua';
                break;
        }

        session(['current' => [
            'user_name' => $name,
            'breed' => $breed,
        ]]);

        return [
            'breed' => $breed,
        ];
    }

    public function getCatDescription(CatDescriptionGetRequest $request)
    {
        $name = $request->user()->name;

        switch ($request->input('breed')) {
            case 'shibainu';
                $desc = 'cute';
                break;
            case 'golden retriever';
                $desc = 'big';
                break;
            case 'chihuahua';
            default:
                $desc = 'alien';
                break;
        }

        $cookie = cookie('desc', $desc, 1);

        return response([
            'user_name' => $name,
        ])->cookie($cookie);
    }
}