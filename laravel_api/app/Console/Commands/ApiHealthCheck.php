<?php
// シンプル接続テスト
// 使い方
// 1. 設定ファイル作成
// php artisan make:command ApiHealthCheck

// 2. Tinkerでトークン生成
// php artisan tinker

// # 管理者トークン取得
// $admin = User::find(1); // 管理者ユーザー
// $adminToken = $admin->createToken('health-check')->plainTextToken;
// echo "Admin Token: " . $adminToken;

// # ユーザートークン取得  
// $user = User::find(2); // 一般ユーザー
// $userToken = $user->createToken('health-check')->plainTextToken;
// echo "User Token: " . $userToken;

// 3. ファイル内のトークンを書き換え
// private $adminToken = '1|your_admin_token_here';
// private $userToken = '2|your_user_token_here';


//  基本実行
// php artisan api:health-check

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ApiHealthCheck extends Command
{
    protected $signature = 'api:health-check {--host=http://localhost:8000}';
    protected $description = 'Check all API endpoints health status';

    // ここにトークンをハードコーディング
    private $adminToken = '1|your_admin_token_here';
    private $userToken = '2|your_user_token_here';

    public function handle()
    {
        $endpoints = [
            ['GET', '/api/books', [], 'public', '書籍一覧取得'],
            ['POST', '/api/books', [
                'title' => 'ABCの本',
                'price' => 1000,
            ], 'public', '商品登録'],
        ];
        // $endpoints = [
        //     // 管理者用エンドポイント
        //     ['GET', '/api/admin/products', [], 'admin', '商品一覧取得（管理者）'],
        //     ['GET', '/api/admin/products/1', [], 'admin', '商品詳細取得（管理者）'],
        //     ['POST', '/api/admin/products/create', [
        //         'name' => 'Test Product',
        //         'price' => 1000,
        //         'description' => 'Test description'
        //     ], 'admin', '商品新規作成（管理者）'],
        //     ['PUT', '/api/admin/products/1', [
        //         'name' => 'Updated Product',
        //         'price' => 1500
        //     ], 'admin', '商品更新（管理者）'],
        //     ['DELETE', '/api/admin/products/1', [], 'admin', '商品削除（管理者）'],
            
        //     // バリデーションエラーテスト（管理者）
        //     ['POST', '/api/admin/products/create', [
        //         'name' => '', // 必須フィールドを空に
        //         'price' => 'invalid_price' // 無効な価格
        //     ], 'admin', 'バリデーションエラーテスト（管理者）', 422],
            
        //     // ユーザー用エンドポイント
        //     ['GET', '/api/user/products', [], 'user', '商品一覧取得（ユーザー）'],
        //     ['GET', '/api/user/profile', [], 'user', 'プロフィール取得（ユーザー）'],
        //     ['POST', '/api/user/favorites', [
        //         'product_id' => 1
        //     ], 'user', 'お気に入り追加（ユーザー）'],
            
        //     // バリデーションエラーテスト（ユーザー）
        //     ['POST', '/api/user/favorites', [
        //         'product_id' => 'invalid_id' // 無効なID
        //     ], 'user', 'バリデーションエラーテスト（ユーザー）', 422],
            
        //     // 認証不要（公開）
        //     ['GET', '/api/public/products', [], 'public', '公開商品一覧取得'],
        //     ['GET', '/api/health', [], 'public', 'ヘルスチェック'],
        // ];

        $host = $this->option('host');
        $results = [];
        
        $this->info("🚀 Testing " . count($endpoints) . " endpoints...");
        $this->info("Host: {$host}\n");
        
        foreach ($endpoints as $endpoint) {
            [$method, $path, $data, $authType, $description] = array_pad($endpoint, 6, null);
            $expectedStatus = $endpoint[5] ?? null; // 期待するステータスコード
            
            try {
                $startTime = microtime(true);
                
                // 認証ヘッダーの設定
                $headers = ['Accept' => 'application/json'];
                if ($authType === 'admin' && $this->adminToken && $this->adminToken !== '1|your_admin_token_here') {
                    $headers['Authorization'] = 'Bearer ' . $this->adminToken;
                } elseif ($authType === 'user' && $this->userToken && $this->userToken !== '2|your_user_token_here') {
                    $headers['Authorization'] = 'Bearer ' . $this->userToken;
                }
                
                $response = Http::timeout(10)
                    ->withHeaders($headers)
                    ->{strtolower($method)}($host . $path, $data);
                
                $responseTime = round((microtime(true) - $startTime) * 1000, 2);
                $status = $response->status();
                $responseBody = $response->body();
                
                // JSON形式チェック
                $isValidJson = $this->isValidJson($responseBody);
                
                // 成功判定
                $success = $expectedStatus 
                    ? ($status === $expectedStatus) // 期待するステータスコードが指定されている場合
                    : ($status >= 200 && $status < 400); // 通常の成功判定
                
                $results[] = [
                    'method' => $method,
                    'path' => $path,
                    'auth_type' => $authType,
                    'description' => $description,
                    'status' => $status,
                    'expected_status' => $expectedStatus,
                    'success' => $success,
                    'response_time' => $responseTime,
                    'is_json' => $isValidJson,
                    'error' => null
                ];
                
                // リアルタイム表示
                $this->displayResult($method, $path, $status, $responseTime, $authType, $success, $isValidJson, $expectedStatus);
                
            } catch (\Exception $e) {
                $results[] = [
                    'method' => $method,
                    'path' => $path,
                    'auth_type' => $authType,
                    'description' => $description,
                    'status' => 'ERROR',
                    'expected_status' => $expectedStatus,
                    'success' => false,
                    'response_time' => 0,
                    'is_json' => false,
                    'error' => $e->getMessage()
                ];
                
                $this->displayResult($method, $path, 'ERROR', 0, $authType, false, false, $expectedStatus, $e->getMessage());
            }
        }
        
        // サマリー表示
        $this->showSummary($results);
        
        return $this->hasCriticalErrors($results) ? Command::FAILURE : Command::SUCCESS;
    }
    
    private function displayResult($method, $path, $status, $time, $authType, $success, $isJson, $expectedStatus = null, $error = null)
    {
        $authIcon = match($authType) {
            'admin' => '🔐',
            'user' => '👤',
            'public' => '🌐',
            default => '❓'
        };
        
        $jsonIcon = $isJson ? '○' : 'X';
        $expectedText = $expectedStatus ? " (期待:{$expectedStatus})" : '';
        
        if ($success) {
            $this->line("<fg=green>✓</> {$authIcon} {$method} {$path} <fg=green>[{$status}]</>{$expectedText} JSON:{$jsonIcon} ({$time}ms)");
        } else {
            $errorMsg = $error ? " - {$error}" : '';
            if (in_array($status, [401, 403])) {
                $this->line("<fg=yellow>⚠</> {$authIcon} {$method} {$path} <fg=yellow>[{$status}]</>{$expectedText} JSON:{$jsonIcon} - 認証エラー{$errorMsg}");
            } else {
                $this->line("<fg=red>✗</> {$authIcon} {$method} {$path} <fg=red>[{$status}]</>{$expectedText} JSON:{$jsonIcon} - エラー{$errorMsg}");
            }
        }
    }
    
    private function showSummary($results)
    {
        $this->newLine();
        $this->info("📊 Summary:");
        $this->line(str_repeat('=', 40));
        
        $total = count($results);
        $successful = collect($results)->where('success', true)->count();
        $failed = $total - $successful;
        $authErrors = collect($results)->whereIn('status', [401, 403])->count();
        $validationErrors = collect($results)->where('status', 422)->count();
        $validJson = collect($results)->where('is_json', true)->count();
        $avgTime = collect($results)->avg('response_time');
        
        $this->line("Total: <fg=blue>{$total}</>");
        $this->line("Success: <fg=green>{$successful}</>");
        $this->line("Failed: <fg=red>{$failed}</>");
        if ($authErrors > 0) {
            $this->line("Auth Errors: <fg=yellow>{$authErrors}</>");
        }
        if ($validationErrors > 0) {
            $this->line("Validation Errors: <fg=cyan>{$validationErrors}</>");
        }
        $this->line("Valid JSON: <fg=cyan>{$validJson}/{$total}</>");
        $this->line("Avg Time: <fg=cyan>" . round($avgTime, 2) . "ms</>");
        $this->line("Success Rate: <fg=cyan>" . round(($successful / $total) * 100, 2) . "%</>");
        
        // 認証タイプ別
        $authStats = collect($results)->groupBy('auth_type')->map(function ($group) {
            return $group->where('success', true)->count() . '/' . $group->count();
        });
        
        $this->newLine();
        $this->line("By Auth Type:");
        foreach ($authStats as $type => $stat) {
            $icon = match($type) {
                'admin' => '🔐',
                'user' => '👤',
                'public' => '🌐',
                default => '❓'
            };
            $this->line("  {$icon} {$type}: {$stat}");
        }
        
        // バリデーションエラーテスト結果
        if ($validationErrors > 0) {
            $this->newLine();
            $this->line("バリデーションエラーテスト:");
            foreach ($results as $result) {
                if ($result['status'] === 422) {
                    $statusColor = $result['success'] ? 'green' : 'red';
                    $this->line("  <fg={$statusColor}>✓</> {$result['description']} [422]");
                }
            }
        }
        
        if ($failed === 0) {
            $this->newLine();
            $this->info("✅ All endpoints are healthy!");
        } else {
            $this->newLine();
            $this->error("🔥 Some endpoints need attention!");
        }
    }
    
    private function isValidJson($content)
    {
        if (empty($content)) return false;
        json_decode($content);
        return json_last_error() === JSON_ERROR_NONE;
    }
    
    private function hasCriticalErrors($results)
    {
        // 認証エラー(401,403)とバリデーションエラー(422)以外のエラーがあるかチェック
        return collect($results)
            ->where('success', false)
            ->whereNotIn('status', [401, 403, 422])
            ->count() > 0;
    }
}