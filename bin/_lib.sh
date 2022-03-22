#!/usr/bin/env bash

set +u

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
	wp config create --dbname="$DB_NAME" --dbuser="$DB_USER" --dbpass="$DB_PASS" --dbhost="$DB_HOST" --skip-check --force=true
	wp core install --url=$WP_DOMAIN --title=GFTests --admin_user=$ADMIN_USERNAME --admin_password=$ADMIN_PASSWORD --admin_email=$ADMIN_EMAIL
	wp rewrite structure '/%year%/%monthnum%/%postname%/'
}

install_gravityforms() {
	if [ ! -d $WP_CORE_DIR/wp-content/plugins/gravityforms ]; then
		echo "Cloning Gravity Forms"
		git clone -b master --single-branch $GF_REPO $WP_CORE_DIR/wp-content/plugins/gravityforms
	fi
	echo "Cloning Gravity Forms"
	wp plugin activate gravityforms --allow-root
}

install_gravityforms_signature() {
	if [ ! -d $WP_CORE_DIR/wp-content/plugins/gravityformssignature ]; then
		echo "Cloning Gravity Forms Signature"
		git clone -b master --single-branch $GF_SIGNATURE_REPO $WP_CORE_DIR/wp-content/plugins/gravityformssignature
	fi
	wp plugin activate gravityformssignature --allow-root
}

install_gravityforms_chainedselects() {
	if [ ! -d $WP_CORE_DIR/wp-content/plugins/gravityformschainedselects ]; then
		echo "Cloning Gravity Forms Chained Selects"
		git clone -b master --single-branch $GF_CHAINEDSELECTS_REPO $WP_CORE_DIR/wp-content/plugins/gravityformschainedselects
	fi
	wp plugin activate gravityformschainedselects --allow-root
}

install_gravityforms_quiz() {
	if [ ! -d $WP_CORE_DIR/wp-content/plugins/gravityformsquiz ]; then
		echo "Cloning Gravity Forms Quiz"
		git clone -b master --single-branch $GF_QUIZ_REPO $WP_CORE_DIR/wp-content/plugins/gravityformsquiz
	fi
	wp plugin activate gravityformsquiz --allow-root
}

install_plugins() {
	cd $WP_CORE_DIR

	wp plugin list --allow-root
	install_gravityforms
	install_gravityforms_signature
	install_gravityforms_chainedselects
	install_gravityforms_quiz

	# Install WPGraphQL Upload and Activate
	wp plugin install https://github.com/dre1080/wp-graphql-upload/archive/refs/heads/master.zip --allow-root
	wp plugin activate wp-graphql-upload --allow-root

	# Install WPGraphQL and Activate
	wp plugin install wp-graphql --allow-root
	wp plugin activate wp-graphql --allow-root

	# Install WPGatsby and Activate
	wp plugin install wp-gatsby --allow-root
	wp plugin activate wp-gatsby --allow-root

	# Install WPJamstack Deployments and Activate
	wp plugin install wp-jamstack-deployments --allow-root
	wp plugin activate wp-jamstack-deployments --allow-root
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
	wp plugin activate wp-graphql-gravity-forms --allow-root

	# Flush the permalinks
	wp rewrite flush --allow-root

	# Export the db for codeception to use
	wp db export $PLUGIN_DIR/tests/_data/dump.sql --allow-root

	echo "Installed plugins"
	wp plugin list --allow-root
}
