<?php

namespace Tests\Feature\Workshop\Session2;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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

    public function userNameDataProvider()
    {
        return [
            ['Chris', 'shibainu'],
            ['John', 'golden retriever'],
            ['Mary', 'chihuahua'],
        ];
    }
}
