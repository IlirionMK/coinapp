@echo off
echo === 🧹 Удаляем старую src ===
rmdir /s /q src

echo === 📦 Устанавливаем Laravel в src ===
composer create-project laravel/laravel src

echo === 📂 Копируем entrypoint.sh ===
mkdir src\docker
copy docker\entrypoint.sh src\docker\entrypoint.sh >nul

echo === 📄 Копируем .env ===
cd src
copy .env.example .env >nul

echo === 🗝️ Генерируем ключ ===
docker run --rm -v %cd%:/app -w /app laravelsail/php82-composer:latest php artisan key:generate

echo === 📦 Устанавливаем NPM зависимости ===
cd src
npm install

cd ..
echo === 🐳 Запускаем Docker Compose ===
docker compose up --build
