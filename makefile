# Основное
up:
	docker-compose up -d --build

down:
	docker-compose down --remove-orphans

restart:
	docker-compose down --remove-orphans && docker-compose up -d --build

logs:
	docker-compose logs -f app

# Laravel
artisan:
	docker exec -it coinapp_app php artisan

migrate:
	docker exec -it coinapp_app php artisan migrate

seed:
	docker exec -it coinapp_app php artisan db:seed

queue:
	docker exec -it coinapp_queue php artisan queue:work

scheduler:
	docker exec -it coinapp_scheduler php artisan schedule:run

# NPM / Vite
npm-install:
	docker exec -it coinapp_vite npm install

npm-dev:
	docker exec -it coinapp_vite npm run dev

npm-build:
	docker exec -it coinapp_vite npm run build

# База
psql:
	docker exec -it coinapp_postgres psql -U coinuser -d coinapp

# Утилиты
ssh:
	docker exec -it coinapp_app bash

refresh:
	docker-compose down --volumes --remove-orphans
	rm -rf src
	mkdir src
	make up
