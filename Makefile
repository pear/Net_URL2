PHP ?= php
export PHP_PEAR_PHP_BIN ?= $(PHP)
export PHP_BINARY ?= $(PHP)
COMPOSERCMD ?= $(PHP) "$(shell command -v composer)" -qn

all:
.PHONY: all clean build dist distclean

vendor/bin/php% : composer.json
	$(COMPOSERCMD) install
	touch -c $@

build: vendor/bin/phpunit
	pear version
	$(PHP) $<
	pear run-tests -r tests/

all: build

clean:
	rm -f -- $(wildcard .php*)

distclean: clean
	rm -rf -- $(wildcard vendor composer.lock Net_URL2-?.?.?.tgz)

dist:
	pear version
	pear package
