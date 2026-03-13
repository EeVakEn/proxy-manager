.PHONY: up down build restart logs php db frontend

COMPOSE = docker compose

up:
	$(COMPOSE) up -d

build:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

restart:
	$(COMPOSE) restart

logs:
	$(COMPOSE) logs -f

php:
	$(COMPOSE) exec php bash

db:
	$(COMPOSE) exec mysql bash

frontend:
	$(COMPOSE) exec frontend sh
