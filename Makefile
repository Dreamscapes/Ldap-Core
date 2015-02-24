# Dreamscapes\Ldap-Core
#
# Licensed under the BSD-3-Clause license
# For full copyright and license information, please see the LICENSE file
#
# @author       Robert Rossmann <rr.rossmann@me.com>
# @copyright    2015 Robert Rossmann
# @link         https://github.com/Dreamscapes/Ldap-Core
# @license      http://choosealicense.com/licenses/BSD-3-Clause  BSD-3-Clause License

# Helper vars
BIN = vendor/bin/
# Current PHP version (in the form {MAJOR}.{MINOR}, i.e. 5.6)
PHP_V = $(shell php -v | head -n 1 | cut -f2 -d" " | cut -f1,2 -d".")
# If there is any target that mutates some remote data, check if it runs on this version of PHP
PHP_T = 5.6

# Project-specific information
GH_USER = Alaneor
GH_REPO = $(shell git remote -v | grep origin | grep fetch | cut -d":" -f2 | cut -d"." -f1)

# Project-specific paths
LIBDIR = Dreamscapes
DOCDIR = docs
GHPDIR = gh-pages
TMPDIR = tmp

# Set/override some variables for Travis

# Travis cannot access our repo using just a username - a token is necessary to be exported into
# GH_TOKEN env variable
GH_USER := $(if ${GH_TOKEN},${GH_TOKEN},$(GH_USER))
# This will usually not change, but if someone forks our repo, this should make sure Travis will
# not try to update the source repo
GH_REPO := $(if ${TRAVIS_REPO_SLUG},${TRAVIS_REPO_SLUG},$(GH_REPO))

# Default - Run it all!
all: install lint docs

# Install dependencies (added for compatibility reasons with usual workflows with make,
# i.e. calling make && make install)
install: vendor

vendor:
	@composer install

# Lint all PHP files to conform to the PSR-2 coding style guide
lint: vendor
	@$(BIN)phpcs --standard=PSR2 --ignore=vendor/*,$(DOCDIR)/* -p $(LIBDIR)

# Generate API documentation (configuration available in phpdoc.dist.xml)
docs: vendor
	@$(BIN)phpdoc

# Update gh-pages branch with new docs
gh-pages: restrict-php-v clean-gh-pages docs
	$(eval COMMIT_MSG := $(if ${TRAVIS},\
		"Updated gh-pages from Travis build ${TRAVIS_JOB_NUMBER}",\
		"Updated gh-pages manually"))
ifeq (${TRAVIS}, true)
	git config --global user.name "Travis-CI"
	git config --global user.email "travis@travis-ci.org"
endif
	@git clone --branch=gh-pages \
			https://$(GH_USER)@github.com/$(GH_REPO).git $(GHPDIR) > /dev/null 2>&1; \
		cd $(GHPDIR); \
		rm -rf *; \
		cp -Rf ../$(DOCDIR)/* .; \
		git add -A;
		git commit -m $(COMMIT_MSG); \
		git push --quiet origin $(GHPDIR) > /dev/null 2>&1;

# Intermediate target to ensure that a task will only run on a specific PHP version
# PHP_V -> Current PHP version
# PHP_T -> Target PHP version
restrict-php-v:
ifneq ($(PHP_V), $(PHP_T))
	@echo "This task modifies remote resources and requires specific version of PHP runtime."
	@echo "PHP version required: $(PHP_T), got $(PHP_V) - bail out"
	@exit 1
endif

# Delete API docs
clean-docs:
	@rm -rf $(DOCDIR)

clean-tmp:
	@rm -rf $(TMPDIR)

# Clean gh-pages dir
clean-gh-pages:
	@rm -rf $(GHPDIR)

# Delete all generated files
clean: clean-docs clean-tmp clean-gh-pages

.PHONY: \
	lint \
	gh-pages \
	clean-docs \
	clean-gh-pages \
	clean
