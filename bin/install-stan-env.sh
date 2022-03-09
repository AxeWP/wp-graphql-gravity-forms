#!/usr/bin/env bash

if [[ ! -f ".env" ]]; then
	echo "No .env file was detected. .env.dist has been copied to .env"
	echo "Open the .env file and enter values to match your local environment"
	cp .env.dist .env
fi

source .env

BASEDIR=$(dirname "$0");
source ${BASEDIR}/_env.sh
source ${BASEDIR}/_lib.sh

install_wordpress
install_db
configure_wordpress
install_plugins
post_setup
