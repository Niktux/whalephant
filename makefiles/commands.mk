#------------------------------------------------------------------------------
# Console
#------------------------------------------------------------------------------
CONSOLE_IMAGE_NAME=whalephant/commands
CONSOLE_CONTAINER_SOURCE_PATH=/usr/src/whalephant

console_exec = docker run -it --rm --name whalephant_console \
	                 -v ${HOST_SOURCE_PATH}:${CONSOLE_CONTAINER_SOURCE_PATH} \
	                 -w ${CONSOLE_CONTAINER_SOURCE_PATH} \
	                 -u ${USER_ID}:${GROUP_ID} \
	                 ${CONSOLE_IMAGE_NAME} \
	                 ./console $1 $(CLI_ARGS)

generate: create-cli-image ## Generate Dockerfile from Whalephant file
	$(call console_exec, generate)
	
create-cli-image:
	docker build -q -t ${CONSOLE_IMAGE_NAME} docker/images/cli/
	
test: generate only-test ## Generate, build and test

only-test: ## Build container from generated Dockerfile and display php info
	docker build -t whalephant-generated ${CLI_ARGS}
	docker run -it --rm --name whalephant_test whalephant-generated php -i

exec_on_generated = docker run -it --rm --name whalephant_test whalephant-generated $1 

connect: ## Build container from generated Dockerfile and run bash
	$(call exec_on_generated, /bin/bash)

check: ## Build container from generated Dockerfile and check php modules
	$(call exec_on_generated, php -r 'phpinfo(INFO_MODULES);')
	
whalephant-help: create-cli-image ## Display Whalephant help
	$(call console_exec, )

clean-cli-image:
	docker rmi ${CONSOLE_IMAGE_NAME}

.PHONY: generate create-cli-image test only-test connect check whalephant-help clean-cli-image
