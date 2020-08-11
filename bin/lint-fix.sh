#!/bin/bash
# phpcbf error normalizer
#
# This script is to ensure phpcbf returns a 0 if it fixes errors, since it
# returns the following statuses:
#
# * Exit code 0 is used to indicate that no fixable errors were found, so
#   nothing was fixed
# * Exit code 1 is used to indicate that all fixable errors were fixed correctly
# * Exit code 2 is used to indicate that PHPCBF failed to fix some of the
#   fixable errors it found
# * Exit code 3 is used for general script execution errors
#
# For reference, see
# https://github.com/squizlabs/PHP_CodeSniffer/issues/1818#issuecomment-354420927
#

PHPCBF="./vendor/bin/phpcbf --cache=build/cache/phpcs.cache"

$PHPCBF $*

if [[ $? -gt 1 ]]; then
    exit $?
fi

exit 0
