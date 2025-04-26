@echo off
setlocal enabledelayedexpansion

:: Check for Composer
where composer >nul 2>nul
if errorlevel 1 (
    echo [ERROR] Composer is not installed or not in PATH.
    exit /b 1
)

:: Check for Docker
where docker >nul 2>nul
if errorlevel 1 (
    echo [ERROR] Docker is not installed or not in PATH.
    exit /b 1
)

:: Check for NPM
where npm >nul 2>nul
if errorlevel 1 (
    echo [ERROR] NPM is not installed or not in PATH.
    exit /b 1
)

echo === 🧹 Removing old src directory ===
rmdir /s /q src

echo === 📦 Installing Laravel into src ===
composer create-project laravel/laravel src
if errorlevel 1 (
    echo [ERROR] Failed to create Laravel project.
    exit /b 1
)

echo === 📂 Copying entrypoint.sh ===
mkdir src\docker
copy docker\entrypoint.sh src\docker\entrypoint.sh >nul

echo === 📄 Copying .env file ===
cd src
copy .env.example .env >nul

echo === 🗝️ Generating application key ===
docker run --rm -v %cd%:/app -w /app laravelsail/php82-composer:latest php artisan key:generate
if errorlevel 1 (
    echo [ERROR] Failed to generate application key.
    exit /b 1
)

echo === 📦 Installing NPM dependencies ===
npm install
if errorlevel 1 (
    echo [ERROR] Failed to install NPM dependencies.
    exit /b 1
)

cd ..

echo === 🐳 Starting Docker Compose ===
docker compose up --build
