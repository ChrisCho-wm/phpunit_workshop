<?php

namespace Tests\Feature\Workshop\Session2;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadTest extends TestCase
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
            Storage::deleteDirectory("cat_images");
        });

        $file = UploadedFile::fake()->image('cat.jpeg', 100, 100);

        $this->json('POST', 'api/cats/uploadImage', [
            'cat_image' => $file,
        ])->assertExactJson([
            "success" => 1,
            "path" => "cat_images/20211231001200.jpeg",
        ]);

        Storage::assertExists("cat_images/" . now()->format('YmdHms') . ".jpeg");
    }
}
