<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dog\DogDescriptionGetRequest;
use App\Http\Requests\Dog\DogImageUploadRequest;
use App\Http\Requests\Dog\MyFavoriteDogGetRequest;

class DogController extends Controller
{
    public function uploadDogImage(DogImageUploadRequest $request)
    {
        $path = $request->file('image')->storeAs('dog_images', now()->format('YmdHms') . ".jpeg");

        return response(['success' => 1, 'path' => $path]);
    }

    public function getMyFavoriteDog(MyFavoriteDogGetRequest $request)
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

    public function getDogDescription(DogDescriptionGetRequest $request)
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
