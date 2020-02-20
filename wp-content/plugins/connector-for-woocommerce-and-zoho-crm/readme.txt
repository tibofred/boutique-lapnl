=== Connector for WooCommerce and Zoho CRM ===
Contributors: hearken
Donate link: https://potentplugins.com/donate/?utm_source=connector-for-woocommerce-and-zoho-crm&utm_medium=link&utm_campaign=wp-plugin-readme-donate-link
Tags: woocommerce, zoho crm, zoho, crm, customer relationship management, ecommerce, e-commerce, integration
Requires at least: 3.5
Tested up to: 5.3.2
Stable tag: 1.1.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Automatically add WooCommerce customers as contacts and/or leads in Zoho CRM.

== Description ==

The Connector for WooCommerce and Zoho CRM plugin automatically adds the customer as a contact and/or lead in your Zoho CRM account whenever an order is placed in WooCommerce.

As of v1.1.0, this plugin **supports Zoho CRM API version 2**. Users updating the plugin from v1.0.9 or earlier will need to re-authenticate their Zoho CRM account on this plugin's settings page.

Features:

* Add customers as contacts and/or leads in Zoho CRM when they place an order in your WooCommerce store.
* Optionally update contacts and/or leads for customers with existing records in Zoho CRM.

A [pro version](https://potentplugins.com/downloads/woocommerce-zoho-crm-connector-pro-plugin/?utm_source=connector-for-woocommerce-and-zoho-crm&utm_medium=link&utm_campaign=wp-repo-upgrade-link) with the following additional features is also available:

* Add order details (product names and quantities) as a note to the contact and/or lead corresponding to the customer.
* Create a potential based on the order and linked to the customer’s contact record (if one was found or created).
* Manually send individual orders to Zoho CRM from the Order Actions menu on the Edit Order page.
* Manually send orders to Zoho CRM individually or in bulk from the order list.

If you like this free plugin, please consider [making a donation](https://potentplugins.com/donate/?utm_source=connector-for-woocommerce-and-zoho-crm&utm_medium=link&utm_campaign=wp-plugin-repo-donate-link).

== Installation ==

1. Click "Plugins" > "Add New" in the WordPress admin menu.
1. Search for "Connector for WooCommerce and Zoho CRM".
1. Click "Install Now".
1. Click "Activate Plugin".

Alternatively, you can manually upload the plugin to your wp-content/plugins directory.

== Frequently Asked Questions ==

== Changelog ==

= 1.1.0 =
* Switched to Zoho CRM API version 2

= 1.0.8 =
* Added notice about API version 2
* License is now GPLv3+

= 1.0.7 =
* Added Zoho CRM URL setting

= 1.0.6 =
* Fixed issue with long site titles preventing authentication

= 1.0.5 =
* Fixed bug with ampersand in field values

= 1.0.2 =
* Fixed bug with non-alphanumeric characters in blog names
* Added option to disconnect Zoho CRM account

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.1.0 =
This plugin now supports Zoho CRM API version 2; please update by December 31, 2019 when API version 1 will be sunset. You will need to re-authenticate your Zoho CRM account under WooCommerce > Zoho CRM Integration after installing this update.
