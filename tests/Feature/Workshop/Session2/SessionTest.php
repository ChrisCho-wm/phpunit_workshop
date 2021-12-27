<?php

namespace Tests\Feature\Workshop\Session2;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SessionTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        // 設定當前時間
        Carbon::setTestNow(Carbon::create(2021, 12, 31, 0, 0, 0));
    }

    public function testUploadFile()
    {
        // 測試結束時, 需要執行的動作, 如:刪除檔案
        $this->beforeApplicationDestroyed(function () {
            Storage::deleteDirectory("dog_images");
        });

        $file = UploadedFile::fake()->image('dog.jpg');

        $this->json('POST', 'api/dogs/uploadImage', [
            'image' => $file,
        ])->assertExactJson([
            "success" => 1,
            "path" => "dog_images/20211231001200.jpeg",
        ]);

        Storage::assertExists("dog_images/" . now()->format('YmdHms') . ".jpeg");
    }

    /**
     * testUserSession
     *
     * @dataProvider userNameDataProvider
     */
    public function testUserSession($userName, $expectBreed)
    {
        $user = factory(User::class)->create(['name' => $userName]);

        $this->actingAs($user) // 使用 User  進行操作
            ->post('api/dogs/getMyFavoriteDog')
            ->assertExactJson([
                "breed" => $expectBreed,
            ])
            ->assertSessionHas('current', [
                'user_name' => $user->name,
                'breed' => $expectBreed,
            ]);
    }

    /**
     * testUserCookie
     *
     * @dataProvider breedDataProvider
     */
    public function testUserCookie($breed, $expectDesc)
    {
        $user = factory(User::class)->create(['name' => 'Chris']);

        $this->actingAs($user) // 使用 User  進行操作
            ->post('api/dogs/getDogDescription', ['breed' => $breed])
            ->assertExactJson([
                "user_name" => $user->name,
            ])
            ->assertCookie('desc', $expectDesc, false);
    }

    public function userNameDataProvider()
    {
        return [
            ['Chris', 'shibainu'],
            ['John', 'golden retriever'],
            ['Mary', 'chihuahua'],
        ];
    }

    public function breedDataProvider()
    {
        return [
            ['shibainu', 'cute'],
            ['golden retriever', 'big'],
            ['chihuahua', 'alien'],
        ];
    }
}
