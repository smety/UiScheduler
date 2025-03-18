DOCKER_COMPOSE = docker compose
APP_CONTAINER = app
WEBSERVER_CONTAINER = webserver
DB_CONTAINER = mysql
REDIS_CONTAINER = redis
SUPERVISOR_CONTAINER = supervisor

.PHONY: up down restart ps logs

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

restart:
	$(DOCKER_COMPOSE) restart

ps:
	$(DOCKER_COMPOSE) ps

logs:
	$(DOCKER_COMPOSE) logs -f

.PHONY: composer-install composer-update npm-install npm-run-dev migrate migrate-fresh seed key-generate

composer-install:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) composer install

composer-update:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) composer update

npm-install:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) npm install

npm-run-dev:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) npm run dev

migrate:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate

migrate-fresh:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate:fresh

seed:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan db:seed

key-generate:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan key:generate

.PHONY: uischeduler-run laravel-scheduler-list crunz-scheduler-list module-install publish-config

uischeduler-run:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan uischeduler:run

laravel-scheduler-list:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan module:list

crunz-scheduler-list:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) vendor/bin/crunz schedule:list

module-install:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan module:make UiScheduler

publish-config:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan vendor:publish --provider="Modules\UiScheduler\Providers\UiSchedulerServiceProvider" --tag="config"

.PHONY: bash shell mysql redis supervisor fix-permissions clear-cache

bash:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) bash

shell:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) sh

mysql:
	$(DOCKER_COMPOSE) exec $(DB_CONTAINER) mysql -u uischeduler -p uischeduler

redis-cli:
	$(DOCKER_COMPOSE) exec $(REDIS_CONTAINER) redis-cli

supervisor-status:
	$(DOCKER_COMPOSE) exec $(SUPERVISOR_CONTAINER) supervisorctl status

fix-permissions:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) chmod -R 777 storage bootstrap/cache

clear-cache:
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan config:clear && \
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan cache:clear && \
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan route:clear && \
	$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan view:clear

.PHONY: build fresh

build:
	$(DOCKER_COMPOSE) build

fresh: down build up key-generate migrate-fresh seed fix-permissions
	@echo "Environment has been completely reset."

.PHONY: init init-laravel init-composer init-npm init-permissions

init: init-laravel init-composer init-npm init-permissions
	@echo "Project initialization completed."

init-laravel:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) composer create-project laravel/laravel=9 tmp && \
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) mv tmp/* . && \
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) mv tmp/.* . 2>/dev/null || true && \
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) rmdir tmp

init-composer:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) composer require nwidart/laravel-modules="9.*" && \
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) composer require crunzphp/crunz && \
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) composer require predis/predis

phpstan:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) vendor/bin/phpstan analyse --memory-limit=2G

pint:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) php vendor/bin/pint

init-npm:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) npm install

init-permissions:
	$(DOCKER_COMPOSE) run --rm $(APP_CONTAINER) chmod -R 777 storage bootstrap/cache

.PHONY: help

help:
	@echo "UiScheduler Makefile - seznam příkazů:"
	@echo ""
	@echo "Docker příkazy:"
	@echo "  make up                 - Spustit kontejnery"
	@echo "  make down               - Zastavit a odstranit kontejnery"
	@echo "  make restart            - Restartovat kontejnery"
	@echo "  make ps                 - Zobrazit běžící kontejnery"
	@echo "  make logs               - Zobrazit logy kontejnerů"
	@echo ""
	@echo "Laravel a PHP příkazy:"
	@echo "  make composer-install   - Spustit 'composer install'"
	@echo "  make composer-update    - Spustit 'composer update'"
	@echo "  make npm-install        - Spustit 'npm install'"
	@echo "  make npm-run-dev        - Spustit 'npm run dev'"
	@echo "  make migrate            - Spustit migrace"
	@echo "  make migrate-fresh      - Spustit čisté migrace"
	@echo "  make seed               - Naplnit databázi testovacími daty"
	@echo "  make key-generate       - Vygenerovat aplikační klíč"
	@echo ""
	@echo "UiScheduler příkazy:"
	@echo "  make uischeduler-run    - Spustit UiScheduler"
	@echo "  make laravel-scheduler-list - Zobrazit Laravel naplánované úlohy"
	@echo "  make crunz-scheduler-list  - Zobrazit Crunz naplánované úlohy"
	@echo "  make module-install     - Nainstalovat UiScheduler modul"
	@echo "  make publish-config     - Publikovat konfiguraci modulu"
	@echo ""
	@echo "Utility:"
	@echo "  make bash               - Otevřít bash v app kontejneru"
	@echo "  make shell              - Otevřít shell v app kontejneru"
	@echo "  make mysql              - Otevřít MySQL konzoli"
	@echo "  make redis-cli          - Otevřít Redis CLI"
	@echo "  make supervisor-status  - Zkontrolovat stav Supervisor procesů"
	@echo "  make fix-permissions    - Opravit oprávnění souborů"
	@echo "  make clear-cache        - Vyčistit cache"
	@echo "  make phpstan            - Spustit PHPStan"
	@echo "  make pint               - Spustit Pint"
	@echo ""
	@echo "Sestavení a inicializace:"
	@echo "  make build              - Sestavit Docker image"
	@echo "  make fresh              - Kompletní reset prostředí"
	@echo "  make init               - Inicializovat nový Laravel projekt"
