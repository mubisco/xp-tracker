CURRENT_DIR:=$(dir $(abspath $(lastword $(MAKEFILE_LIST))))
DOCKER_COMPOSE:=USER_ID=${shell id -u} docker compose
BACK_IMAGE=backend
FRONT_IMAGE=frontend
DOCKER_FRONT_EXEC=$(DOCKER_COMPOSE) exec xp-track-front

.PHONY: default
default: info

.PHONY: info
info:
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_-]+:.*?##/ { printf "  \033[36m%-27s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: start
start: ##  Start containers
	@$(DOCKER_COMPOSE) up -d

.PHONY: stop
stop: ##  Stop containers
	@$(DOCKER_COMPOSE) down

.PHONY: restart
restart: stop start ##  Restart containers

.PHONY: build
build: ##  Force container build
	@make stop
	@$(DOCKER_COMPOSE) build --no-cache

.PHONY: sh-back
sh-back: ##  Access to backend shell
	@$(DOCKER_COMPOSE) exec $(BACK_IMAGE) /bin/zsh

.PHONY: sh-root
sh-root: ##  Access to backend shell as root
	@$(DOCKER_COMPOSE) exec -u 0 $(BACK_IMAGE) /bin/zsh

.PHONY: logs
logs: ##  Access to backend shell
	@$(DOCKER_COMPOSE) logs $(BACK_IMAGE)

.PHONY: test-back
test-back:
	@$(DOCKER_COMPOSE) exec $(BACK_IMAGE) go test ./...
