-include .env

help: ## Display this current help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-25s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

copy-env: ## Copy .env.dist to .env
	cp -n .env.dist .env

start: ## Start project
	docker compose up -d

stop: ## Stop project
	docker compose stop

phpstan: ## Analyse PhpStan
	docker compose run --rm php vendor/bin/phpstan analyse src

phpspec: ## Launch phpspec
	docker compose run --rm php vendor/bin/phpspec run src

csfixer: ## Launch csfixer
	docker compose run --rm php vendor/bin/php-cs-fixer fix

dmd: ## Launch migration generation
	docker compose run --rm php bin/console doctrine:migrations:diff

dmm: ## Launch migrattion migrate
	docker compose run --rm php bin/console doctrine:migrations:migrate

fixture-load: ## Create fixtures
	docker compose run --rm php bin/console doctrine:fixtures:load