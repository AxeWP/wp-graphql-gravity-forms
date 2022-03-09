#!/usr/bin/env bash

set +u

print_usage_instruction() {
	echo "ERROR!"
	echo "Values in the .env file are missing or incorrect."
	echo "Open the .env file at the root of this plugin and enter values to match your local environment settings"
	exit 1
}

if [[ -z "$TEST_DB_NAME" ]]; then
	echo "TEST_DB_NAME not found"
	print_usage_instruction
else
	DB_NAME=$TEST_DB_NAME
fi
if [[ -z "$TEST_DB_USER" ]]; then
	echo "TEST_DB_USER not found"
	print_usage_instruction
else
	DB_USER=$TEST_DB_USER
fi

DB_HOST=${TEST_DB_HOST-localhost}
DB_PASS=${TEST_DB_PASSWORD-""}
WP_VERSION=${WP_VERSION-latest}
TMPDIR=${TMPDIR-/tmp}
TMPDIR=$(echo $TMPDIR | sed -e "s/\/$//")
WP_TESTS_DIR=${WP_TESTS_DIR-$TMPDIR/wordpress-tests-lib}
WP_CORE_DIR=${TEST_WP_ROOT_FOLDER-$TMPDIR/wordpress/}
PLUGIN_DIR=$(pwd)
SKIP_DB_CREATE=${SKIP_DB_CREATE-false}
