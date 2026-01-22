#!/usr/bin/env bash

set +u

install_plugins() {
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

	# We install these locally so we can stan them.
	if ! $( wp plugin is-installed wp-gatsby ); then
		echo "Installing WP Gatsby..."
		wp plugin install wp-gatsby
	fi
	wp plugin activate wp-gatsby

	if ! $( wp plugin is-installed wp-jamstack-deployments ); then
		echo "Installing WP Jamstack Deployments..."
		wp plugin install wp-jamstack-deployments
	fi
	wp plugin activate wp-jamstack-deployments
}

post_setup() {

	# Flush the permalinks
	wp rewrite structure /%postname%/ --hard
	wp rewrite flush --hard


	echo "Installed plugins"
	wp plugin list

	wp config set WP_DEBUG true --raw --type=constant
	wp config set WP_DEBUG_LOG true --raw --type=constant
	wp config set GRAPHQL_DEBUG true --raw --type=constant

	wp option update gf_env_hide_setup_wizard true
}
