setup:
	@echo "Starting environment setup..."
	docker compose down --remove-orphans || true
	docker compose up -d --build
	@echo "Installing PHP dependencies..."
	docker compose exec app composer install
	@echo "Installing Node dependencies..."
	docker compose exec vite npm install
	@echo "Preparing .env and application key..."
	docker compose exec app cp .env.example .env || true
	docker compose exec app php artisan key:generate || true
	docker compose exec app php artisan migrate --force || true
	docker compose exec app php artisan db:seed || true
	docker compose exec app php artisan storage:link || true
	@echo "Building frontend..."
	docker compose exec vite npm run build
	@echo "Setup complete. Project is running at http://localhost:8000"
