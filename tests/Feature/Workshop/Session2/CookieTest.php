<?php

namespace Tests\Feature\Workshop\Session2;

use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CookieTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    public function setup()
    {
        parent::setup();

        // 設定當前時間
        Carbon::setTestNow(Carbon::create(2021, 12, 31, 0, 0, 0));
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

    public function breedDataProvider()
    {
        return [
            ['shibainu', 'cute'],
            ['golden retriever', 'big'],
            ['chihuahua', 'alien'],
        ];
    }
}
