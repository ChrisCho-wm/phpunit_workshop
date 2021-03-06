# Session1
## 目標
1. 瞭解 Mockery 用法
2. 使用 Mockery, 產生一個 Mock 物件
3. 解讀程式覆蓋率

## 初始化
- 範例 repository
  - git@github.com:ChrisCho-wm/phpunit_workshop.git
- 初始化步驟
  - composer install
  - 建立 .env.testing, 設定 DB 連線, sqlite, MySQL 均可
  - 安裝 xdebug (測試覆蓋率)
  - php artisan migrate:install
  - php artisan migrate

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
- 測試指令
  ```
    php artisan make:test {FeatureOrClassName}Test   // 建立 test
    ./vendor/bin/phpunit tests/{FeatureOrClassName}Test(.php) // 執行測試
    ./vendor/bin/phpunit tests/{FeatureOrClassName}Test(.php) --coverage-html report // 執行測試, 並建立測試覆蓋率報告
  ```
## 重點整理
- Workshop 重點整理
1. mock 步驟一定要確實執行, 若會忘記, 請使用 snippet, 快速新增
2. dataProvider function 執行時間比 setup function 還前面, 故無法在 dataProvider function 內使用 laravel 方法, 建議使用純值或是使用 php 原生方法讀取外部資料
3. 若同時間需要 mock 不同的 API, 可以使用 withArgs 或 withSomeOfArgs 或是 andReturnUsing