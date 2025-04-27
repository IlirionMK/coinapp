.PHONY: dev-up dev-down dev-rebuild prod-up prod-down migrate cache-clear

dev-up:
	docker compose -f docker-compose.dev.yml up -d

dev-down:
	docker compose -f docker-compose.dev.yml down

dev-rebuild:
	docker compose -f docker-compose.dev.yml up --build -d

prod-up:
	docker compose -f docker-compose.prod.yml up --build -d

prod-down:
	docker compose -f docker-compose.prod.yml down

migrate:
	docker compose exec app php artisan migrate

cache-clear:
	docker compose exec app php artisan config:clear && \
	docker compose exec app php artisan config:cache && \
	docker compose exec app php artisan route:cache && \
	docker compose exec app php artisan view:cache
