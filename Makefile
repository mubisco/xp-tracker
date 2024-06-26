CURRENT_DIR:=$(dir $(abspath $(lastword $(MAKEFILE_LIST))))
DOCKER_COMPOSE:=USER_ID=${shell id -u} docker compose
BACK_IMAGE=backend
FRONT_IMAGE=frontend
DOCKER_FRONT_EXEC=$(DOCKER_COMPOSE) exec xp-track-front
DOCKER_BACK_EXEC=$(DOCKER_COMPOSE) exec backend

.PHONY: default
default: info

.PHONY: info
info:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: up
up: ##  Start containers
	@$(DOCKER_COMPOSE) up -d

.PHONY: down
down: ##  Stop containers
	@$(DOCKER_COMPOSE) down

.PHONY: run
run: ## Run application with events
	@make down
	@make up
	@make events-up

.PHONY: reload
reload: down up ##  Restart containers

.PHONY: restart-front
restart-front: ##  Restart front container to relaoa env variables
	@$(DOCKER_COMPOSE) restart $(FRONT_IMAGE)

.PHONY: build
build: ##  Force container build
	@make down
	@$(DOCKER_COMPOSE) build --no-cache

.PHONY: sh-back
sh-back: ##  Access to backend shell
	@$(DOCKER_COMPOSE) exec $(BACK_IMAGE) /bin/zsh

.PHONY: sh-root
sh-root: ##  Access to backend shell as root
	@$(DOCKER_COMPOSE) exec -u 0 $(BACK_IMAGE) /bin/zsh

.PHONY: sh-front
sh-front: ##  Access to frontend shell
	@$(DOCKER_COMPOSE) exec $(FRONT_IMAGE) /bin/zsh

.PHONY: logs
logs: ##  Access to backend shell
	@$(DOCKER_COMPOSE) logs -f $(FRONT_IMAGE)

.PHONY: but
but: ## Run Backend Unit Tests
	@$(DOCKER_COMPOSE) exec $(BACK_IMAGE) composer unit

.PHONY: bat
bat: ## Run Backend Acceptance (Behat) Tests
	@$(DOCKER_COMPOSE) exec $(BACK_IMAGE) composer behat

.PHONY: events-up
events-up: ## Cleans previous messages and raises messenger consumer
	@$(DOCKER_BACK_EXEC) php ./bin/console dbal:run-sql 'TRUNCATE TABLE messenger_messages'
	@$(DOCKER_BACK_EXEC) php ./bin/console messenger:setup-transports
	@$(DOCKER_BACK_EXEC) php ./bin/console messenger:consume event.async -vv

.PHONY: install
install: ## Setup application to work locally
	@make up
	@$(DOCKER_BACK_EXEC) php ./bin/console doctrine:migrations:migrate -n
