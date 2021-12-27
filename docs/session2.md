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
  - 書本ISBN API
    - 測試類別 App\Libraries\BookFetcher (測試回傳資料於 getBook function 中)
    - 測試1.確認有書本資料時, Table 寫入正確
    - 測試2.確認無書本資料時, 拋出 Exception
    - 運用的功能
    ```
        $mockClient->shouldReceive($methodName)->andReturn($response);
        $this->expectException($className);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($message);
        $this->assertDatabaseHas
    ```
  - 頂級域名 API
    - 測試類別 App\Libraries\TldFetcher (測試回傳資料於 tests/Feature/Workshop/tw.json,cn.json,us.json)
    - 測試1.有資料時,回傳正確篩選後的資料 (使用 @dataProvider 進行三組資料測試)
    - 測試2.確認無Tld資料時, 拋出 Exception
    - 運用的功能
    ```
        @dataProvider

        $mockClient->shouldReceive($methodName)->andReturn($response);
        $this->expectException($className);
        $this->expectExceptionCode($code);
        $this->expectExceptionMessage($message);
        $this->assertEquals
    ```
  - 虛擬貨幣 API
    - 測試類別 App\Libraries\CryptoFetcher (測試回傳資料於 getGlobal, getBTC function 中)
    - 測試. 使用不同的資料測試回傳訊息是否正確
    - 運用的功能
    ```
        @dataProvider

        $mockClient->shouldReceive($methodName)->withArgs($arg1, $arg2...)->andReturn($response);
        $mockClient->shouldReceive($methodName)->withSomeOfArgs($arg1, $arg2...)->andReturn($response);
        $this->assertEquals
    ```

## 重點整理
- Workshop 重點整理
1. 若程式內部有使用 Carbon now 的方法, 則可以使用 Carbon::setTestNow 進行調整, 降低判斷時間難度
2. 若現有系統使用原生方法 date, 則應替換為 now(), 以便測試
3. 新增的檔案務必於測試結束後刪除, 降低測試之間互相影響
4. 於 Larave Feature 測試時需要 teardown 且需使用 Laravel 功能, 則必須使用 $this->beforeApplicationDestroyed 進行 callback 註冊
5. cookie 驗證時, 預設為加密, 需要傳入第三參數進行切換處理