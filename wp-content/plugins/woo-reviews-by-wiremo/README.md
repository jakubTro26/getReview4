# Wiremo WooCommerce plugin

This is Wiremo plugin for WooCommerce. 

## Prerequisites

This service use: PHP, JavaScript and NodeJS.

## Starting

For development should be installed XAMPP PHP development environment.

1. Create a local WooCommerce site using XAMPP. Using [guide](https://www.wpbeginner.com/wp-tutorials/how-to-create-a-local-wordpress-site-using-xampp).
2. Archive `woocommerce-plugin` and insert as plugin in the site.
3. Activate Wiremo plugin from plugins page.

## Installation

1. Go to your admin area and select Plugins -> Add new from the menu.
2. Search for "Product & Site Reviews by Wiremo".
3. Click install then activate.
4. In WP main menu click on Wiremo then press on "Connect your Wiremo account"
5. Now you can configure your Wiremo plugin as you like and test.

## CI

CI workflow name is `woocommerce-plugin` (see .circleci/config.yml)

## Deployment

Deployment is done using docker swarm (see `.deploy/wiremo/deploy.sh` for more details).

## FAQ

For frequently issues see: [WooCommerce FAQ](https://wiremo.atlassian.net/wiki/spaces/WIR/pages/318898177/Woo+WordPress+FAQ)
