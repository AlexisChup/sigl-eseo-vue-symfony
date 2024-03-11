SHELL := /bin/bash

tests:
	symfony console doctrine:schema:drop --force -n --env=test || true
	symfony console doctrine:database:drop --force -n --env=test || true
	symfony console doctrine:database:create -n --env=test
	symfony console doctrine:migrations:migrate -n --env=test
	symfony console doctrine:fixtures:load -n --env=test
	php bin/phpunit --coverage-html ./tests/report/
.PHONY: tests
