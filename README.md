# Egg

[![Testing](https://github.com/takemo101/egg/actions/workflows/testing.yml/badge.svg)](https://github.com/takemo101/egg/actions/workflows/testing.yml)
[![PHPStan](https://github.com/takemo101/egg/actions/workflows/phpstan.yml/badge.svg)](https://github.com/takemo101/egg/actions/workflows/phpstan.yml)
[![Validate Composer](https://github.com/takemo101/egg/actions/workflows/composer.yml/badge.svg)](https://github.com/takemo101/egg/actions/workflows/composer.yml)


卵のようにどんな料理にもマッチする、、、  
そんな、PHPなんちゃってWebフレームワークです（笑）  
  
色々な機能は、Symfonyのコンポーネントを利用して実装しています（便利ですね！）

## インストール
```bash
composer require takemo101/egg
```
## 使い方
基本的には、以下機能を利用することができます。
1. Httpリクエストとレスポンスを扱う機能
2. コンソールコマンドを扱う機能
3. Httpとコンソールを制御するアプリケーション機能

新しい機能などを追加したい場合は、アプリケーション機能のDIコンテナを利用してください。

#### Httpリクエストとレスポンスを扱う機能
ドキュメントルートの```index.php```に以下のように記述してください。
```php
<?php

require __DIR__ . '/../vendor/autoload.php';

// アプリケーションを起動して
// Httpリクエストとレスポンスを扱うプロセスを実行する
Takemo101\Egg\Http\HttpProcess::fromApplication(
    new Takemo101\Egg\Kernel\Application(
        path: new Takemo101\Egg\Kernel\ApplicationPath(
            base: $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__),
        ),
    ),
)->run();
```

#### コンソールを扱う機能
コマンドを実行するルートの```xxx.php```に以下のように記述してください。
```php
<?php

require __DIR__ . '/vendor/autoload.php';

// アプリケーションを起動して
// コンソールコマンドのプロセスを実行
// ちなみにコンソールの処理は、おなじみのsymfony/consoleを利用しています
Takemo101\Egg\Console\ConsoleProcess::fromApplication(
    new Takemo101\Egg\Kernel\Application(
        path: new Takemo101\Egg\Kernel\ApplicationPath(
            base: $_ENV['APP_BASE_PATH'] ?? __DIR__,
        ),
    ),
)->run();
```

## その他サポート
アプリケーションの初期設定などは、以下のディレクトリから設定を行うことができます。
1. ```./config```ディレクトリには、アプリケーションで参照できる、色々な設定を記述することができます。
2. ```./setting```ディレクトリには、アプリケーションで必要な初期設定を記述することができます。

#### コンフィグ
```./config```ディレクトリに```xxx.php```のような適当な名前のファイルを作成し、以下のように記述してください。
```php
<?php

// 連想配列で設定を記述する
return [
    'key' => 'value',
];
```
以上のような、連想配列を返すファイルを作成すると、```config('xxx.key')```のように、アプリケーション内で設定を参照することができます。

#### DIコンテナ
```./setting/function.php```では、``ContainerAccessor``に依存関係を設定できます。
```php
<?php

use Takemo101\Egg\Support\ServiceAccessor\ContainerAccessor as Container;

Container::bind(
    XXXRepository::class,
    fn () => new XXXRepositoryImpl(
        db: $c->make(DB::class),
    ),
);

// シングルトンでインスタンスを作成する依存設定の場合は、singletonメソッドを利用する
Container::singleton(
    XXXQueryService::class,
    fn () => new XXXQueryServiceImpl(
        db: $c->make(DB::class),
    ),
);

```

#### ルーティング
```./setting/function.php```では、``HookAccessor``を利用したフック処理により、``RouteBuilder``にルートを設定できます。
```php
<?php

use Takemo101\Egg\Routing\RouteBuilder;
use Takemo101\Egg\Support\ServiceAccessor\HookAccessor as Hook;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Hook::onByType(
    function (RouteBuilder $r) {
        // リクエストとレスポンスはSymfonyのものを利用しています
        $r->get(
            '/', 
            function (Request $request, Response $response) {
                return $response->setContent('home');
            },
        )
            ->filter(XXXFilter::class) // フィルタ（ミドルウェア）を設定することができます
            ->name('home'); // 名付けておくと、ルート名を利用してURLを生成することができます

        // ルートのグルーピングもできます
        $r->group(function (RouteBuilder $r) {
            // ルート解析はAltoRouterを利用しているので
            // プレースホルダーを利用してパラメータを取得することができます
            $r->get('/[i:id]', function (int $id) {
                echo $id;
            })
                ->name('show'); 

            // 配列（callable）でコントローラーを指定することもできます
            $r->put('/[i:id]/edit', [EditController::class, 'edit']])
                ->name('edit');
        })
            ->path('group') // ルートグループのパスプレフィックスを設定できます。
            ->name('group.');
    },
);

```

#### フィルタ（ミドルウェア）
```./setting/function.php```では、``HookAccessor``を利用したフック処理により、``RootFilters``へルート全体で適用するフィルタ（ミドルウェア）を設定できます。
```php
<?php

use Takemo101\Egg\Http\Filter\CsrfFilter;
use Takemo101\Egg\Http\Filter\MethodOverrideFilter;
use Takemo101\Egg\Http\Filter\SessionFilter;
use Takemo101\Egg\Http\RootFilters;
use Takemo101\Egg\Support\ServiceAccessor\HookAccessor as Hook;

Hook::onByType(
    fn (RootFilters $filters) => $filters->add(
        MethodOverrideFilter::class, // リクエストメソッドを上書きするフィルタ
        SessionFilter::class, // Sessionを利用するフィルタ
        CsrfFilter::class, // Csrf対策のフィルタ
    ),
);

```

#### コマンド
```./setting/function.php```では、``HookAccessor``を利用したフック処理により、``Commands``へコマンドを設定できます。
```php
<?php

use Takemo101\Egg\Console\Command\VersionCommand;
use Takemo101\Egg\Console\Commands;
use Takemo101\Egg\Support\ServiceAccessor\HookAccessor as Hook;

Hook::onByType(
    fn (Commands $commands) => $commands->add(
        VersionCommand::class,　// バージョンを表示するコマンド
    ),
);

```

#### フック
```./setting/function.php```では、フック処理を設定できます（WordPressのfunctions.phpのようなイメージ）
```php

use Symfony\Component\HttpFoundation\Response;
use Takemo101\Egg\Support\Hook\Hook;
use Takemo101\Egg\Support\ServiceLocator;

/** 
 * ServiceLocatorからは特定のインスタンスをキーワードで取得できる
 * 'hook'キーワードでHookインスタンスを取得できる
 * もちろんServiceAccessorからもHookインスタンスを取得しても良い
 * 
 * ※ Hookインスタンスはフック処理を登録するためのものです
 * 
 * @var Hook
 */
$hook = ServiceLocator::get('hook');

// レスポンス返却前に処理をフックすることで
// レスポンスを加工できる
$hook->on(
    'after-response',
    function (Response $response) {
        return $response;
    },
);


```

#### モジュール
```./setting/function.php```では、``HookAccessor``を利用したフック処理により、``Modules``へモジュールを設定できます。
```php
<?php

use Takemo101\Egg\Module\HelperModule;
use Takemo101\Egg\Module\Modules;
use Takemo101\Egg\Support\ServiceAccessor\HookAccessor as Hook;

Hook::onByType(
    fn (Modules $modules) => $modules->add(
        HelperModule::class, // ヘルパー関数を提供するモジュール
    ),
};

```
モジュールは```Takemo101\Egg\Module\ModuleContract```を実装 or ```Takemo101\Egg\Module\Module```を継承したクラスを作成し、```./setting/module.php```で設定することで利用できます。
