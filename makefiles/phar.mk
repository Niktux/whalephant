#------------------------------------------------------------------------------
# PHAR
#------------------------------------------------------------------------------
BOX_VERSION=2.7.4

compile-phar: prepare-env whalephant.phar composer-install ## Compile whalephant.phar

prepare-env:
	$(call composer_exec,install --no-dev --ignore-platform-reqs)
	
whalephant.phar: clean-phar box.phar 
	php -d phar.readonly=off box.phar build

clean-phar:
	rm whalephant.phar

box.phar:
	wget -q https://github.com/box-project/box2/releases/download/2.7.4/box-${BOX_VERSION}.phar
	mv box-${BOX_VERSION}.phar box.phar

test-phar: compile-phar ## Compile and run phar
	docker run -it --rm --name whalephant_console \
               -v ${HOST_SOURCE_PATH}:${CONSOLE_CONTAINER_SOURCE_PATH} \
               -w ${CONSOLE_CONTAINER_SOURCE_PATH} \
               -u ${USER_ID}:${GROUP_ID} \
               ${CONSOLE_IMAGE_NAME} \
               php whalephant.phar generate $(CLI_ARGS)

.PHONY: compile-phar clean-phar prepare-env test-phar
