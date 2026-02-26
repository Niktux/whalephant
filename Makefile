###############################################################################
# Whalephant Main Makefile
###############################################################################

HOST_SOURCE_PATH=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

export USER_ID
export GROUP_ID

# Spread cli arguments
ifneq (,$(filter $(firstword $(MAKECMDGOALS)),composer phpunit generate test only-test test-phar))
    CLI_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    $(eval $(CLI_ARGS):;@:)
endif

#------------------------------------------------------------------------------
# Includes
#------------------------------------------------------------------------------
-include vendor/onyx/core/wizards.mk
include makefiles/composer.mk
include makefiles/commands.mk
include makefiles/docker.mk
include makefiles/phpunit.mk
include makefiles/phar.mk

#------------------------------------------------------------------------------
# Help
#------------------------------------------------------------------------------
.DEFAULT_GOAL := help

help:
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.PHONY: help

#------------------------------------------------------------------------------
# High level targets
#------------------------------------------------------------------------------
init: var composer-install

var:
	mkdir -m a+w var

clean:
	rm -f composer.phar
	rm -f composer.lock
	rm -rf vendor

.PHONY: init clean
