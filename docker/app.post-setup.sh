#!/bin/bash

# activate the plugin
wp plugin activate wp-graphql-gravity-forms --allow-root

# Set pretty permalinks.
wp rewrite structure '/%year%/%monthnum%/%postname%/' --allow-root
wp rewrite flush --allow-root

# Export the db for codeception to use
wp db export "${DATA_DUMP_DIR}/dump.sql" --allow-root

# If maintenance mode is active, de-activate it
if $(wp maintenance-mode is-active --allow-root); then
	echo "Deactivating maintenance mode"
	wp maintenance-mode deactivate --allow-root
fi

chmod 777 -R .
chown -R $(id -u):$(id -g) .
