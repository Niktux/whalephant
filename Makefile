###############################################################################
# ONYX Main Makefile
###############################################################################

HOST_SOURCE_PATH=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

export USER_ID
export GROUP_ID

# Spread cli arguments for composer & phpunit
ifneq (,$(filter $(firstword $(MAKECMDGOALS)),composer phpunit, gen))
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

#------------------------------------------------------------------------------
# Default target
#------------------------------------------------------------------------------
init: var install-deps config

#------------------------------------------------------------------------------
# Includes
#------------------------------------------------------------------------------
-include vendor/onyx/core/wizards.mk
-include commands.mk
-include phpunit.mk

#------------------------------------------------------------------------------
# High level targets
#------------------------------------------------------------------------------
install-deps: composer-install

var:
	mkdir -m a+w var

.PHONY: install-deps config composer composer-install composer-update composer-dumpautoload uninstall clean remove-deps

#------------------------------------------------------------------------------
# Karma
#------------------------------------------------------------------------------
config: karma
	./karma hydrate

karma:
	$(eval LATEST_VERSION := $(shell curl -L -s -H 'Accept: application/json' https://github.com/niktux/karma/releases/latest | sed -e 's/.*"tag_name":"\([^"]*\)".*/\1/'))
	@echo "Latest version of Karma is ${LATEST_VERSION}"
	wget -O karma -q https://github.com/Niktux/karma/releases/download/${LATEST_VERSION}/karma.phar
	chmod 0755 karma

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

#------------------------------------------------------------------------------
# Cleaning targets
#------------------------------------------------------------------------------
uninstall: clean remove-deps
	rm -f composer.lock
	rm -f config/built-in/*.yml

clean:
	rm -f karma
	rm -f composer.phar

remove-deps:
	rm -rf vendor
