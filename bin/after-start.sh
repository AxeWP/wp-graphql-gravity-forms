#!/usr/bin/env bash


# Run the setup scripts for the plugin inside the wp-env environment
npm run wp-env run cli -- --env-cwd=wp-content/plugins/wp-graphql-gravity-forms -- bash bin/setup.sh
npm run wp-env run tests-cli -- --env-cwd=wp-content/plugins/wp-graphql-gravity-forms -- bash bin/setup.sh

# Install the pdo_mysql extension on the provided container
install_pdo_mysql() {
	CONTAINER_ID="$1"
	ENV_NAME="$2"

	if docker exec -u root "$CONTAINER_ID" php -m | grep -q pdo_mysql; then
		echo "pdo_mysql Extension on $ENV_NAME: Already installed."
		return 0
	fi

	echo "Installing: pdo_mysql Extension on $ENV_NAME."
	if ! docker exec -u root "$CONTAINER_ID" docker-php-ext-install pdo_mysql > /dev/null 2>&1; then
		echo "WARNING: pdo_mysql Extension on $ENV_NAME: Installation failed. This is expected on ephemeral containers." >&2
		return 0
	fi

	if ! docker exec -u root "$CONTAINER_ID" php -m | grep -q pdo_mysql; then
		echo "WARNING: pdo_mysql Extension on $ENV_NAME: Installation command succeeded but extension not loaded." >&2
		return 0
	fi

	echo "pdo_mysql Extension on $ENV_NAME: Installed."
}

# Install pdo_mysql extension in the cli
CONTAINER_ID_CLI="$(docker ps | grep tests-wordpress  | awk '{print $1}')"
if [[ -n "$CONTAINER_ID_CLI" ]]; then
	install_pdo_mysql "$CONTAINER_ID_CLI" "tests"
fi

# Install pdo_mysql extension in the tests-cli environment
CONTAINER_ID_TESTS_CLI="$(docker ps | grep tests-cli  | awk '{print $1}')"
if [[ -n "$CONTAINER_ID_TESTS_CLI" ]]; then
	install_pdo_mysql "$CONTAINER_ID_TESTS_CLI" "tests-cli"
fi

# Dump clean test database
npm run wp-env run tests-cli -- --env-cwd=wp-content/plugins/wp-graphql-gravity-forms wp db export tests/_data/dump.sql || true
