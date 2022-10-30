#!/usr/bin/env bash

set +u

export WP_CLI_ALLOW_ROOT=1

download() {
	if [ $(which curl) ]; then
		curl -s "$1" >"$2"
	elif [ $(which wget) ]; then
		wget -nv -O "$2" "$1"
	fi
}

install_wordpress() {
	
	if [ -d $WP_CORE_DIR ]; then
		return;
	fi

	mkdir -p $WP_CORE_DIR

	if [[ $WP_VERSION == 'nightly' || $WP_VERSION == 'trunk' ]]; then
		mkdir -p $TMPDIR/wordpress-nightly
		download https://wordpress.org/nightly-builds/wordpress-latest.zip	$TMPDIR/wordpress-nightly/wordpress-nightly.zip
		unzip -q $TMPDIR/wordpress-nightly/wordpress-nightly.zip -d $TMPDIR/wordpress-nightly/
		mv $TMPDIR/wordpress-nightly/wordpress/* $WP_CORE_DIR
	else
		if [ $WP_VERSION == 'latest' ]; then
			local ARCHIVE_NAME='latest'
		elif [[ $WP_VERSION =~ [0-9]+\.[0-9]+ ]]; then
			# https serves multiple offers, whereas http serves single.
			download https://api.wordpress.org/core/version-check/1.7/ $TMPDIR/wp-latest.json
			if [[ $WP_VERSION =~ [0-9]+\.[0-9]+\.[0] ]]; then
				# version x.x.0 means the first release of the major version, so strip off the .0 and download version x.x
				LATEST_VERSION=${WP_VERSION%??}
			else
				# otherwise, scan the releases and get the most up to date minor version of the major release
				local VERSION_ESCAPED=`echo $WP_VERSION | sed 's/\./\\\\./g'`
				LATEST_VERSION=$(grep -o '"version":"'$VERSION_ESCAPED'[^"]*' $TMPDIR/wp-latest.json | sed 's/"version":"//' | head -1)
			fi
			if [[ -z "$LATEST_VERSION" ]]; then
				local ARCHIVE_NAME="wordpress-$WP_VERSION"
			else
				local ARCHIVE_NAME="wordpress-$LATEST_VERSION"
			fi
		else
			local ARCHIVE_NAME="wordpress-$WP_VERSION"
		fi
		download https://wordpress.org/${ARCHIVE_NAME}.tar.gz	$TMPDIR/wordpress.tar.gz
		tar --strip-components=1 -zxmf $TMPDIR/wordpress.tar.gz -C $WP_CORE_DIR
	fi

	download https://raw.github.com/markoheijnen/wp-mysqli/master/db.php $WP_CORE_DIR/wp-content/db.php
}

install_db() {
	if [ ${SKIP_DB_CREATE} = "true" ]; then
		return 0
	fi

	# parse DB_HOST for port or socket references
	local PARTS=(${DB_HOST//\:/ })
	local DB_HOSTNAME=${PARTS[0]}
	local DB_SOCK_OR_PORT=${PARTS[1]}
	local EXTRA=""

	if ! [ -z $DB_HOSTNAME ]; then
		if [ $(echo $DB_SOCK_OR_PORT | grep -e '^[0-9]\{1,\}$') ]; then
			EXTRA=" --host=$DB_HOSTNAME --port=$DB_SOCK_OR_PORT --protocol=tcp"
		elif ! [ -z $DB_SOCK_OR_PORT ]; then
			EXTRA=" --socket=$DB_SOCK_OR_PORT"
		elif ! [ -z $DB_HOSTNAME ]; then
			EXTRA=" --host=$DB_HOSTNAME --protocol=tcp"
		fi
	fi

	# create database
	RESULT=$(mysql -u $DB_USER --password="$DB_PASS" --skip-column-names -e "SHOW DATABASES LIKE '$DB_NAME'"$EXTRA)
	if [ "$RESULT" != $DB_NAME ]; then
		mysqladmin create $DB_NAME --user="$DB_USER" --password="$DB_PASS"$EXTRA
	fi
}

configure_wordpress() {
	if [ "${SKIP_WP_SETUP}" = "true" ]; then
		echo "Skipping WordPress setup..."
		return 0
	fi

	cd $WP_CORE_DIR

	echo "Setting up WordPress..."
	export WP_CLI_CONFIG_PATH=${WP_CLI_CONFIG_PATH};
	
	wp config create --dbname="$DB_NAME" --dbuser="$DB_USER" --dbpass="$DB_PASS" --dbhost="$DB_HOST" --skip-check --force=true
	wp core install --url=$WP_DOMAIN --title=Test --admin_user=$ADMIN_USERNAME --admin_password=$ADMIN_PASSWORD --admin_email=$ADMIN_EMAIL
	wp rewrite structure '/%year%/%monthnum%/%postname%/' --hard
}

install_plugins() {
	cd $WP_CORE_DIR

	# Install GF plugins and Activate.
	echo "Installing and Activating Gravity Forms + extensions";
	if ! $( wp plugin is-installed gravityformscli ); then
		wp plugin install gravityformscli
	fi
	wp plugin activate gravityformscli

	
	if ! $( wp plugin is-installed gravityforms ); then
		echo "Installing Gravity Forms..."
		wp gf install --key=$GF_KEY --quiet
	fi
	wp plugin activate gravityforms

	if ! $( wp plugin is-installed gravityformssignature ); then
		echo "Installing Gravity Forms Signature..."
		wp gf install gravityformssignature --key=$GF_KEY --quiet
	fi
	wp plugin activate gravityformssignature --allow-root

	if ! $( wp plugin is-installed gravityformschainedselects ); then
		echo "Installing Gravity Forms Chained Selects..."
		wp gf install gravityformschainedselects --key=$GF_KEY --quiet
	fi
	wp plugin activate gravityformschainedselects

	if ! $( wp plugin is-installed gravityformsquiz ); then
		echo "Installing Gravity Forms Quiz..."
		wp gf install gravityformsquiz --key=$GF_KEY --quiet
	fi
	wp plugin activate gravityformsquiz

	# Install WPGraphQL and Activate
	if ! $( wp plugin is-installed wp-graphql ); then
		wp plugin install wp-graphql
	fi
	wp plugin activate wp-graphql

	# Install WPGraphQL Upload and Activate
	if ! $( wp plugin is-installed wp-graphql-upload ); then
		wp plugin install https://github.com/dre1080/wp-graphql-upload/archive/refs/heads/master.zip
	fi
	wp plugin activate wp-graphql-upload


	# Install WPGatsby and Activate
	if ! $( wp plugin is-installed wp-gatsby ); then
		wp plugin install wp-gatsby
	fi
	wp plugin activate wp-gatsby

	# Install WPJamstack Deployments and Activate
	if ! $( wp plugin is-installed wp-jamstack-deployments ); then
		wp plugin install wp-jamstack-deployments
	fi
	wp plugin activate wp-jamstack-deployments
}

setup_plugin() {
	if [ "${SKIP_WP_SETUP}" = "true" ]; then
		echo "Skipping WPGraphQL for GF installation..."
		return 0
	fi

	# Add this repo as a plugin to the repo
	if [ ! -d $WP_CORE_DIR/wp-content/plugins/wp-graphql-gravity-forms ]; then
		ln -s $PLUGIN_DIR $WP_CORE_DIR/wp-content/plugins/wp-graphql-gravity-forms
		cd $WP_CORE_DIR/wp-content/plugins
		pwd
		ls
	fi

	cd $PLUGIN_DIR

	composer install
}

post_setup() {
	cd $WP_CORE_DIR

	# activate the plugin
	wp plugin activate wp-graphql-gravity-forms

	# Flush the permalinks
	wp rewrite flush

	# Export the db for codeception to use
	wp db export $PLUGIN_DIR/tests/_data/dump.sql

	echo "Installed plugins"
	wp plugin list
}
