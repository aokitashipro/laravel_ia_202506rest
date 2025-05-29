front・・ViteでHTML/CSS/JS構成

backend・・Laravel RestfulAPI


## ダウンロードして起動する方法

### 1. 任意のフォルダに移動


### 2. git clone

git clone git@github.com:aokitashipro/laravel_ia_202506rest.git


ブランチ指定

git clone -b branch_name git@github.com:aokitashipro/laravel_ia_202506rest.git


cd laravel_ia_202506rest/laravel_api

### 3. .env作成

cp .env.example .env


### 4. vendor生成

composer update


### 5. キー生成

php artisan key:generate


### 6. .envの調整

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ia_api
DB_USERNAME=laravel_user
DB_PASSWORD=password123


### 7. 簡易サーバー起動

php artisan serve

http://localhost:8000 で起動する

## フロント側


### 1. フロントに移動

cd ../front

### 2. node_modulesインストール

npm ci

### 3. 簡易サーバー起動

npm run dev

http://localhost:5173 で起動する
