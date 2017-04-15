#------------------------------------------------------------------------------
# Console
#------------------------------------------------------------------------------
CONSOLE_IMAGE_NAME=whalephant/commands
CONSOLE_CONTAINER_SOURCE_PATH=/usr/src/whalephant

.DEFAULT_GOAL := help

console_exec = docker run -it --rm --name whalephant_console \
	                 -v ${HOST_SOURCE_PATH}:${CONSOLE_CONTAINER_SOURCE_PATH} \
	                 -w ${CONSOLE_CONTAINER_SOURCE_PATH} \
	                 -u ${USER_ID}:${GROUP_ID} \
	                 ${CONSOLE_IMAGE_NAME} \
	                 ./console $1 $(CLI_ARGS)

gen: generate ## Generate and display Dockerfile 
	cat recipes/Dockerfile
	echo "*************************************"
	cat recipes/php.ini

test: generate only-test ## Generate, build and test

only-test: ## Build container from generated Dockerfile and display php info
	docker build -t whalephant-generated ${CLI_ARGS}
	docker run -it --rm --name whalephant_test whalephant-generated php -i

connect: ## Build container and run bash
	docker run -it --rm --name whalephant_test whalephant-generated /bin/bash

check: ## Build container and check php modules
	docker run -it --rm --name whalephant_test whalephant-generated php -r 'phpinfo(INFO_MODULES);'

generate: create-cli-image ## Generate Dockerfile from Whalephant file
	$(call console_exec, generate)
	
cli-help: create-cli-image ## Help Whalephant
	$(call console_exec, )

create-cli-image: ## Create Whalephant image
	docker build -q -t ${CONSOLE_IMAGE_NAME} docker/images/cli/

clean-cli-image: ## Delete Whalephant image
	docker rmi ${CONSOLE_IMAGE_NAME}

.PHONY: help generate help create-cli-image clean-cli-image

help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)
