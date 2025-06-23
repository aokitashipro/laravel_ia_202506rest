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
            ['GET', '/api/books', [], 'public', '公開商品一覧取得'],
        ];
        // ■■下記を参考にしてください■■
        // [メソッド名, エンドポイント、リクエストボディ, 権限, メモ]
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
            
        //     // ユーザー用エンドポイント
        //     ['GET', '/api/user/products', [], 'user', '商品一覧取得（ユーザー）'],
        //     ['GET', '/api/user/profile', [], 'user', 'プロフィール取得（ユーザー）'],
        //     ['POST', '/api/user/favorites', [
        //         'product_id' => 1
        //     ], 'user', 'お気に入り追加（ユーザー）'],
            
        //     // 認証不要（公開）
        //     ['GET', '/api/public/products', [], 'public', '公開商品一覧取得'],
        //     ['GET', '/api/health', [], 'public', 'ヘルスチェック'],
        // ];

        $host = $this->option('host');
        $results = [];
        
        $this->info("🚀 Testing " . count($endpoints) . " endpoints...");
        $this->info("Host: {$host}\n");
        
        foreach ($endpoints as $endpoint) {
            [$method, $path, $data, $authType, $description] = $endpoint;
            
            try {
                $startTime = microtime(true);
                
                // 認証ヘッダーの設定
                $headers = ['Accept' => 'application/json'];
                if ($authType === 'admin' && $this->adminToken) {
                    $headers['Authorization'] = 'Bearer ' . $this->adminToken;
                } elseif ($authType === 'user' && $this->userToken) {
                    $headers['Authorization'] = 'Bearer ' . $this->userToken;
                }
                
                $response = Http::timeout(10)
                    ->withHeaders($headers)
                    ->{strtolower($method)}($host . $path, $data);
                
                $responseTime = round((microtime(true) - $startTime) * 1000, 2);
                $status = $response->status();
                $success = $status >= 200 && $status < 400;
                
                $results[] = [
                    'method' => $method,
                    'path' => $path,
                    'auth_type' => $authType,
                    'description' => $description,
                    'status' => $status,
                    'success' => $success,
                    'response_time' => $responseTime,
                    'error' => null
                ];
                
                // リアルタイム表示
                $this->displayResult($method, $path, $status, $responseTime, $authType, $success);
                
            } catch (\Exception $e) {
                $results[] = [
                    'method' => $method,
                    'path' => $path,
                    'auth_type' => $authType,
                    'description' => $description,
                    'status' => 'ERROR',
                    'success' => false,
                    'response_time' => 0,
                    'error' => $e->getMessage()
                ];
                
                $this->displayResult($method, $path, 'ERROR', 0, $authType, false, $e->getMessage());
            }
        }
        
        // サマリー表示
        $this->showSummary($results);
        
        return $this->hasCriticalErrors($results) ? Command::FAILURE : Command::SUCCESS;
    }
    
    private function displayResult($method, $path, $status, $time, $authType, $success, $error = null)
    {
        $authIcon = match($authType) {
            'admin' => '🔐',
            'user' => '👤',
            'public' => '🌐',
            default => '❓'
        };
        
        if ($success) {
            $this->line("<fg=green>✓</> {$authIcon} {$method} {$path} <fg=green>[{$status}]</> ({$time}ms)");
        } else {
            $errorMsg = $error ? " - {$error}" : '';
            if (in_array($status, [401, 403])) {
                $this->line("<fg=yellow>⚠</> {$authIcon} {$method} {$path} <fg=yellow>[{$status}]</> - Auth Error{$errorMsg}");
            } else {
                $this->line("<fg=red>✗</> {$authIcon} {$method} {$path} <fg=red>[{$status}]</> - Error{$errorMsg}");
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
        $avgTime = collect($results)->avg('response_time');
        
        $this->line("Total: <fg=blue>{$total}</>");
        $this->line("Success: <fg=green>{$successful}</>");
        $this->line("Failed: <fg=red>{$failed}</>");
        if ($authErrors > 0) {
            $this->line("Auth Errors: <fg=yellow>{$authErrors}</>");
        }
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
        
        if ($failed === 0) {
            $this->newLine();
            $this->info("✅ All endpoints are healthy!");
        } else {
            $this->newLine();
            $this->error("🔥 Some endpoints need attention!");
        }
    }
    
    private function hasCriticalErrors($results)
    {
        // 認証エラー以外のエラーがあるかチェック
        return collect($results)
            ->where('success', false)
            ->whereNotIn('status', [401, 403])
            ->count() > 0;
    }
}