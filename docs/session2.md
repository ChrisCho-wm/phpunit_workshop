# Session2
## 目標
1. 瞭解 WithoutMiddleware Trait 用法
2. 瞭解 TestResponse 物件
3. 可以對 now() 進行時間控制
4. 撰寫針對 API 的 Feature Test
5. 可以進行 "檔案上傳", "Session", "Cookie" 的測試

## 範例
- API
    - api/dogs/uploadImage
    - api/dogs/getMyFavoriteDog
    - api/dogs/getRelativeDog
- ExampleTest
    - [SessionDemoDogApiTest](./../tests/Feature/Workshop/Session2/SessionDemoDogApiTest.php)

## 題目
- 題目內容
  - 上傳 Cat 圖片
    - 測試圖片位於 tests/Feature/Workshop/Session2 cat.jpeg
    - 測試流程
      - 上傳 cat.jpeg, 並設定寬高為 100x100
      - 驗證回傳格式
      - 驗證 storage 是否有正確將圖片儲存
      - 測試完成請刪除產生的資料夾
    - 運用的功能
    ```
        Carbon::setTestNow, Carbon::create
        $this->beforeApplicationDestroyed()
        UploadedFile::fake()->image
        assertExactJson
        Storage::assertExists
    ```
  - 驗證登入使用者喜好-1
    - 測試流程
      - 驗證回傳格式
      - 驗證 session 是否有正確寫入資料
      - 請使用 dataProvider 傳入資料
      - 測試完成請刪除產生的資料夾
    - 運用的功能
    ```
        Carbon::setTestNow, Carbon::create
        actingAs
        assertExactJson
        assertSessionHas
    ```
  - 驗證登入使用者喜好-2
    - 測試流程
      - 驗證回傳格式
      - 驗證 cookie 是否有正確寫入資料
      - 請使用 dataProvider 傳入資料
      - 測試完成請刪除產生的資料夾
    - 運用的功能
    ```
        Carbon::setTestNow, Carbon::create
        actingAs
        assertExactJson
        assertCookie
    ```

## 重點整理
- Workshop 重點整理
1. 若程式內部有使用 Carbon now 的方法, 則可以使用 Carbon::setTestNow 進行調整, 降低判斷時間難度
2. 若現有系統使用原生方法 date, 則應替換為 now(), 以便測試
3. 新增的檔案務必於測試結束後刪除, 降低測試之間互相影響
4. 於 Laravel Feature 測試時需要 teardown 且需使用 Laravel 功能, 則必須使用 $this->beforeApplicationDestroyed 進行 callback 註冊
5. cookie 驗證時, 預設為加密, 需要傳入第三參數進行切換處理
6. 若有測試時不易操作的 middleware, 可使用 trait WithoutMiddleware 移除
