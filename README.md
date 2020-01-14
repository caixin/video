<p align="center"><img src="http://tv.f1good.com/images/logo.png" alt="IQQTV" width="400"></p>

# 安裝流程

## 開啟BIOS啟用CPU虛擬化技術(VT-x、Intel Virtualization Technology)

## 下載Docker for Windows
https://hub.docker.com/editions/community/docker-ce-desktop-windows

##  git clone Laradock 專案
git clone https://github.com/laradock/laradock.git Laradock

## 複製Laradock環境設定檔:
cd laradock
cp env-example .env

## 啟用Laradock 提供的docker container:
docker-compose up -d nginx mariadb workspace(可填其他環境需要之條件)

## 安裝Composer
https://getcomposer.org/download/

## Composer依賴
    composer install

## 複製環境變數
複製 .env.example => .env

## 產生key
    php artisan key:generate

## 修改.env(資料庫帳密 可直接連測試機比較快)
- ADMIN_DOMAIN=admin.iqqtv
- DB_DATABASE=192.168.1.79
- DB_USERNAME=root
- DB_PASSWORD=ji3g4go6

## 執行資料庫遷移檔(連測試機就不用執行)
    php artisan migrate
