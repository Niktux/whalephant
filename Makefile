###############################################################################
# ONYX Main Makefile
###############################################################################

HOST_SOURCE_PATH=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

export USER_ID
export GROUP_ID

# Spread cli arguments for composer & phpunit
ifneq (,$(filter $(firstword $(MAKECMDGOALS)),composer phpunit gen generate test only-test test-phar))
    CLI_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
    $(eval $(CLI_ARGS):;@:)
endif

# Add ignore platform reqs for composer install & update
COMPOSER_ARGS=
ifeq (composer, $(firstword $(MAKECMDGOALS)))
    ifneq (,$(filter install update,$(CLI_ARGS)))
        COMPOSER_ARGS=--ignore-platform-reqs
    endif
endif

init: var install-deps

.PHONY: init

#------------------------------------------------------------------------------
# Includes
#------------------------------------------------------------------------------
-include vendor/onyx/core/wizards.mk
include commands.mk
include phpunit.mk
include phar.mk

#------------------------------------------------------------------------------
# High level targets
#------------------------------------------------------------------------------
install-deps: composer-install

var:
	mkdir -m a+w var

.PHONY: install-deps

#------------------------------------------------------------------------------
# Composer
#------------------------------------------------------------------------------
composer_exec = php composer.phar $1

composer: composer.phar
	php composer.phar $(CLI_ARGS) $(COMPOSER_ARGS)

composer.phar:
	curl -sS https://getcomposer.org/installer | php

composer-install: composer.phar
	$(call composer_exec, install --ignore-platform-reqs)

composer-update: composer.phar
	$(call composer_exec, update --ignore-platform-reqs)

composer-dumpautoload: composer.phar
	$(call composer_exec, dumpautoload)

.PHONY: composer composer-install composer-update composer-dumpautoload

#------------------------------------------------------------------------------
# Cleaning targets
#------------------------------------------------------------------------------
uninstall: clean remove-deps
	rm -f composer.lock

clean:
	rm -f composer.phar

remove-deps:
	rm -rf vendor

.PHONY: uninstall clean remove-deps
