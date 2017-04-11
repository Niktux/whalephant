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

gen: generate
	cat Dockerfile

test: generate only-test

only-test:
	docker build -t whalephant-generated .
	docker run -it --rm --name whalephant_test whalephant-generated php -i

connect:
	docker run -it --rm --name whalephant_test whalephant-generated /bin/bash

check:
	docker run -it --rm --name whalephant_test whalephant-generated php -r 'phpinfo(INFO_MODULES);'

generate: create-cli-image
	$(call console_exec, generate)
	
help: create-cli-image
	$(call console_exec, )

create-cli-image:
	docker build -q -t ${CONSOLE_IMAGE_NAME} docker/images/cli/

clean-cli-image:
	docker rmi ${CONSOLE_IMAGE_NAME}

.PHONY: generate help create-cli-image clean-cli-image