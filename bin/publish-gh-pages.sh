#!/bin/bash

###############################################################################
# gh-pages automatic updater
#
# Automatically update gh-pages branch with newly generated documentation after
# successful Travis build.
#
# @AUTHOR           Robert Rossmann <rr.rossmann@me.com>
# @LICENSE          BSD-3-Clause
# @VERSION          0.1.0
#
###############################################################################

# CONFIG

GH_USER="Dreamscapes"
GH_REPO="Ldap-Core"
FOLDER="docs"

# CONDITIONS

# Only publish when running tests for PHP 5.5, when committing to master and when not building a pull request
if [ "$TRAVIS_BRANCH" != "master" ] \
|| [ "$TRAVIS_PHP_VERSION" != "5.5" ] \
|| [ "$TRAVIS_PULL_REQUEST" != "false" ]
then
  echo "Not updating gh-pages for this build"
  exit 0
fi

# PUBLISHING

echo -e "Updating gh-pages\n"

# Copy data we are interested in to other place
cp -R $FOLDER $HOME/$FOLDER

# Go to $HOME and setup git
cd $HOME
git config --global user.email "travis@travis-ci.org"
git config --global user.name "Travis"

# Clone gh-pages branch using Github token
git clone --quiet --branch=gh-pages https://${GH_TOKEN}@github.com/${GH_USER}/${GH_REPO}.git  gh-pages > /dev/null

# Copy data we are insterested in to the gh-pages directory
cd gh-pages
cp -Rf $HOME/$FOLDER/* .

# Add, commit and push
git add -f .
git commit -m "Travis build $TRAVIS_BUILD_NUMBER pushed to gh-pages"
git push -fq origin gh-pages > /dev/null

echo -e "Finished updating gh-pages\n"
