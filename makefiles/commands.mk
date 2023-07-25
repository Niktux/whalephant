#------------------------------------------------------------------------------
# Console
#------------------------------------------------------------------------------
CONSOLE_IMAGE_NAME=whalephant/commands
CONSOLE_CONTAINER_SOURCE_PATH=/usr/src/whalephant

console_exec = ${DOCKER_RUN} --rm --name whalephant_console \
	                 -v ${HOST_SOURCE_PATH}:${CONSOLE_CONTAINER_SOURCE_PATH} \
	                 -w ${CONSOLE_CONTAINER_SOURCE_PATH} \
	                 -u ${USER_ID}:${GROUP_ID} \
	                 ${CONSOLE_IMAGE_NAME} \
	                 ./console -vvv $1 $(CLI_ARGS)

generate: create-cli-image ## Generate Dockerfile from Whalephant file
	$(call console_exec,generate)

extensions: create-cli-image ## List extensions supported by Whalephant
	$(call console_exec,extensions)

version: create-cli-image ## Show whalephant version
	$(call console_exec,version)

create-cli-image:
	docker build -q -t ${CONSOLE_IMAGE_NAME} docker/images/cli/
	
test: generate only-test ## Generate, build and test

exec_on_generated = docker run -it --rm --name whalephant_test whalephant-generated $1 

only-test: ## Build container from generated Dockerfile and display php info
	docker build -t whalephant-generated ${CLI_ARGS}
	$(call exec_on_generated, php -m)
	$(call exec_on_generated, php -v)

connect: ## Build container from generated Dockerfile and run bash
	$(call exec_on_generated, /bin/bash)

check: ## Build container from generated Dockerfile and check php modules
	$(call exec_on_generated, php -m)
	
whalephant-help: create-cli-image ## Display Whalephant help
	$(call console_exec, )

clean-cli-image:
	docker rmi ${CONSOLE_IMAGE_NAME}

.PHONY: generate create-cli-image test only-test connect check whalephant-help clean-cli-image
