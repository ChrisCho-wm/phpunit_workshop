# UnitTest workshop
## Workshop目標
1. 瞭解 Mockery 用法
2. 使用 Mockery, 產生一個 Mock 物件
3. 解讀程式覆蓋率

## 內容
- DogFetcher 程式進行範例說明 (30mins)
- 說明需要測試的類別 (5mins)
- 分組進行開發 (45mins)
- 成果展示 (30mins)
- Q & A (10mins)

## 實作流程
- 提供3個題目練習
- 兩個人一組, 選擇一個題目進行練習, 各自實作, 可以討論
- 若完成, 可以練習尚未完成的題目
- 解析測試撰寫結果

## 題目
- 範例 repository
  - git@github.com:ChrisCho-wm/phpunit_workshop.git
- 初始化步驟
  - composer install
  - 建立 .env.testing, 設定 DB 連線, sqlite, MySQL 均可
  - 安裝 xdebug (測試覆蓋率)
  - php artisan migrate:install
  - php artisan migrate
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
    - 測試類別 App\Libraries\TldFetcher (測試回傳資料於 tests/Feature/WorkShop/tw.json,cn.json,us.json)
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
        $this->assertEquals
    ```
- 測試指令
  ```
    php artisan make:test {FeatureOrClassName}Test   // 建立 test
    ./vendor/bin/phpunit tests/{FeatureOrClassName}Test(.php) // 執行測試
    ./vendor/bin/phpunit tests/{FeatureOrClassName}Test(.php) --coverage-html report // 執行測試, 並建立測試覆蓋率報告
  ```