# WooCommerce XVantage Products

<a href="https://www.buymeacoffee.com/adamlyncc" target="_blank"><img src="https://cdn.buymeacoffee.com/buttons/default-orange.png" alt="Buy Me A Coffee" height="41" width="174"></a>


**Description:** This WooCommerce plugin populates the product catalog from Ingram Micro using API calls based on specified product numbers.

## Prerequisites

- Must be an authorized reseller with Ingram Micro
- Must have an active Ingram Micro Developer account and app

## Features

- Fetch product details using Ingram Part Numbers.
- Populate WooCommerce catalog with products from an external API.
- Generate and use access tokens for API authentication.

## Installation

1. **Download the Plugin Files**: Clone or download the repository files.
2. **Upload to WordPress**: Upload the `woocommerce-external-products` directory to the `/wp-content/plugins/` directory.
3. **Activate the Plugin**: Activate the plugin through the 'Plugins' menu in WordPress.

## Configuration

1. **Navigate to Settings**: Go to WooCommerce > External Products Settings.
2. **Enter API Credentials**:
   - API ID
   - API Secret
   - IM Customer Number
   - IM Correlation ID
   - IM Country Code
3. **Enter Product Numbers**: Add a list of Ingram Part Numbers, each on a new line, in the provided text area.

## Usage

- The plugin will automatically fetch product details from the API for each specified product number and populate the WooCommerce catalog.

## Development

### Repository Structure