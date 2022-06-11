#!/bin/bash


install_gf() {
	# Install GF plugins and Activate.
	wp plugin install gravityformscli --activate --allow-root

	echo "Installing and Activating Gravity Forms + extensions";
	wp gf install --key=$GF_KEY --allow-root --quiet
	wp plugin activate gravityforms --allow-root

	wp gf install gravityformssignature --key=$GF_KEY --allow-root --quiet
	wp plugin activate gravityformssignature --allow-root

	wp gf install gravityformschainedselects --key=$GF_KEY --allow-root --quiet
	wp plugin activate gravityformschainedselects --allow-root

	wp gf install gravityformsquiz --key=$GF_KEY --allow-root --quiet
	wp plugin activate gravityformsquiz --allow-root
}

# Install plugins
install_gf || true


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

# activate the plugin
wp plugin activate wp-graphql-gravity-forms --allow-root

# Set pretty permalinks.
wp rewrite structure '/%year%/%monthnum%/%postname%/' --allow-root

# Export the db for codeception to use
wp db export "${DATA_DUMP_DIR}/dump.sql" --allow-root

# If maintenance mode is active, de-activate it
if $(wp maintenance-mode is-active --allow-root); then
	echo "Deactivating maintenance mode"
	wp maintenance-mode deactivate --allow-root
fi


chmod 777 -R .
chown -R $(id -u):$(id -g) .
