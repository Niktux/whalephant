#------------------------------------------------------------------------------
# Composer
#------------------------------------------------------------------------------

# Add ignore platform reqs for composer install & update
COMPOSER_ARGS=
ifeq (composer, $(firstword $(MAKECMDGOALS)))
    ifneq (,$(filter install update,$(CLI_ARGS)))
        COMPOSER_ARGS=--ignore-platform-reqs
    endif
endif

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
