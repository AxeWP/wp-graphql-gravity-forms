#!/bin/bash

install_gf() {
	# Install GF plugins and Activate.
	echo "Installing and Activating Gravity Forms + extensions";
	if ! $( wp plugin is-installed gravityformscli --allow-root ); then
		wp plugin install gravityformscli --allow-root
	fi
	wp plugin activate gravityformscli --allow-root

	
	if ! $( wp plugin is-installed gravityforms --allow-root ); then

		echo "Installing Gravity Forms..."
		wp gf install --key=$GF_KEY --allow-root --quiet
	fi
	wp plugin activate gravityforms --allow-root

	if ! $( wp plugin is-installed gravityformssignature --allow-root ); then
		echo "Installing Gravity Forms Signature..."
		wp gf install gravityformssignature --key=$GF_KEY --allow-root --quiet
	fi
	wp plugin activate gravityformssignature --allow-root

	if ! $( wp plugin is-installed gravityformschainedselects --allow-root ); then
		echo "Installing Gravity Forms Chained Selects..."
		wp gf install gravityformschainedselects --key=$GF_KEY --allow-root --quiet
	fi
	wp plugin activate gravityformschainedselects --allow-root

	if ! $( wp plugin is-installed gravityformsquiz --allow-root ); then
		echo "Installing Gravity Forms Quiz..."
		wp gf install gravityformsquiz --key=$GF_KEY --allow-root --quiet
	fi
	wp plugin activate gravityformsquiz --allow-root
}

# Install plugins
install_gf || true

# Install WPGraphQL and Activate
if ! $( wp plugin is-installed wp-graphql --allow-root ); then
	wp plugin install wp-graphql --allow-root
fi
wp plugin activate wp-graphql --allow-root

# Install WPGraphQL Upload and Activate
if ! $( wp plugin is-installed wp-graphql-upload --allow-root ); then
	wp plugin install https://github.com/dre1080/wp-graphql-upload/archive/refs/heads/master.zip --allow-root
fi
wp plugin activate wp-graphql-upload --allow-root

# Install WPGatsby and Activate
if ! $( wp plugin is-installed wp-gatsby --allow-root ); then
	wp plugin install wp-gatsby --allow-root
fi
wp plugin activate wp-gatsby --allow-root

# Install WPJamstack Deployments and Activate
if ! $( wp plugin is-installed wp-jamstack-deployments --allow-root ); then
	wp plugin install wp-jamstack-deployments --allow-root
fi
wp plugin activate wp-jamstack-deployments --allow-root

# activate the plugin
wp plugin activate wp-graphql-gravity-forms --allow-root

# Set pretty permalinks.
wp rewrite structure '/%year%/%monthnum%/%postname%/' --allow-root

wp plugin list --allow-root

# Export the db for codeception to use
wp db export "${DATA_DUMP_DIR}/dump.sql" --allow-root

# If maintenance mode is active, de-activate it
if $(wp maintenance-mode is-active --allow-root); then
	echo "Deactivating maintenance mode"
	wp maintenance-mode deactivate --allow-root
fi


chmod 777 -R .
chown -R $(id -u):$(id -g) .
