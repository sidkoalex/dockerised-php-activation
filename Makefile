# Include docker-compose env
include .env

# MySQL backup path
MYSQL_DUMPS_DIR=data/db

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  start            Create and start containers"
	@echo "  stop             Stop and clear all services"
	@echo "  logs             Follow log output"
	@echo "  db-init          Create Activation database with tables"
	@echo "  db-backup        Create backup of Activation database"
	@echo "  db-restore       Restore Activation database"

start:
	@echo "Run services:"
	docker-compose up -d
	@echo ""
	@echo "Links:"
	@echo "Testing serials: http://localhost:8080/web/example/serial.php"
	@echo "Upload activation: http://localhost:8080/web/upload.php"

stop:
	@docker-compose down

logs:
	@docker-compose logs -f

db-backup:
	(docker exec $(shell docker-compose ps -q mysqldb) mysqldump -u $(MYSQL_ROOT_USER) --password=$(MYSQL_ROOT_PASSWORD) activation) > backup.sql

db-restore:
	cat backup.sql | docker exec -i $(shell docker-compose ps -q mysqldb) mysql -u $(MYSQL_ROOT_USER) --password=$(MYSQL_ROOT_PASSWORD) $(MYSQL_DATABASE)

db-init:
	cat db.sql | docker exec -i $(shell docker-compose ps -q mysqldb) mysql -u $(MYSQL_ROOT_USER) --password=$(MYSQL_ROOT_PASSWORD) $(MYSQL_DATABASE)