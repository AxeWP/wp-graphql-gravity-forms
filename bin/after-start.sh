#!/usr/bin/env bash


# Run the setup scripts for the plugin inside the wp-env environment in parallel
(npm run wp-env run cli -- --env-cwd=wp-content/plugins/wp-graphql-gravity-forms -- bash bin/setup.sh) &
(npm run wp-env run tests-cli -- --env-cwd=wp-content/plugins/wp-graphql-gravity-forms -- bash bin/setup.sh) &
wait

# Install the pdo_mysql extension on the provided container
# Arguments:
#   $1 - Docker Container ID
#   $2 - wp-env Environment Name
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

# Install PCOV
# Arguments:
#   $1 - wp-env Environment Name
install_pcov() {
	ENV_NAME="$1"

	if npm run wp-env run $ENV_NAME -- php -m | grep -q pcov; then
		echo "pcov Extension on $ENV_NAME: Already installed."
		return 0
	fi

	echo "Installing: pcov Extension on $ENV_NAME."
	if ! npm run wp-env run $ENV_NAME -- sudo pecl install pcov > /dev/null 2>&1; then
		echo "WARNING: pcov Extension on $ENV_NAME: Installation failed. This is expected on ephemeral containers." >&2
		return 0
	fi

	npm run wp-env run $ENV_NAME -- bash -- -c 'echo "extension=pcov" | sudo tee /usr/local/etc/php/conf.d/99-pcov.ini > /dev/null'
	npm run wp-env run $ENV_NAME -- bash -- -c 'echo "pcov.enabled=1" | sudo tee -a /usr/local/etc/php/conf.d/99-pcov.ini > /dev/null'

	echo "pcov Extension on $ENV_NAME: Installed."
}

# Install pdo_mysql extension in the tests-cli environment
CONTAINER_ID="$(docker ps | grep tests-cli  | awk '{print $1}')"
if [[ -n "$CONTAINER_ID" ]]; then
	install_pdo_mysql "$CONTAINER_ID" "tests-cli"
fi

if [[ "$PCOV_ENABLED" == "1" ]]; then
	# Install pcov extension in the tests-cli environment
	install_pcov "tests-cli"
fi

# Dump clean test database
npm run wp-env run tests-cli -- --env-cwd=wp-content/plugins/wp-graphql-gravity-forms wp db export tests/_data/dump.sql || true
