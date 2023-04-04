The flutterwaveShopware plugin is a Symfony-based plugin designed to integrate the Flutterwave payment system into Shopware 6. This documentation provides a technical overview of the plugin, including its architecture, functionality, installation, and configuration.

Architecture:
The flutterwaveShopware plugin is built on the latest Symfony version for Shopware 6. It uses the following core components:

Shopware Payment System: The plugin is integrated with Shopware's payment system and extends the existing payment provider infrastructure.
Symfony Framework: The plugin is developed on the Symfony Framework, which provides a robust and modular foundation for developing web applications.
Flutterwave Payment API: The plugin uses Flutterwave's Payment API to initiate and process payment transactions.
Functionality:
The flutterwaveShopware plugin provides the following functionality:

Configuration: The plugin provides a user-friendly interface for configuring the Flutterwave payment options, including setting up API keys, defining supported currencies and payment methods, and enabling/disabling the plugin.
Payment Processing: The plugin integrates with Shopware's payment system to process payments securely and efficiently. It uses Flutterwave's Payment API to initiate and process payment transactions and provides feedback to the customer about the status of their payment.
Order Management: The plugin updates the order status in the Shopware system based on the status of the payment transaction.
Installation:
The flutterwaveShopware plugin can be installed in the following way:

Download the plugin from the GitHub repository (https://github.com/Mjoel4708/flutterwaveShopware)
Extract the downloaded files and copy the "FlutterwaveShopware" folder into your Shopware 6 installation directory under "custom/plugins"
Run the following commands in the Shopware 6 root directory to install and activate the plugin:
./bin/console plugin:refresh
./bin/console plugin:install FlutterwaveShopware
./bin/console plugin:activate FlutterwaveShopware
Configuration:
The flutterwaveShopware plugin can be configured in the following way:

Log in to your Shopware 6 administration panel.
Go to "Configuration" -> "Plugins" and search for "FlutterwaveShopware".
Click on "Configuration" to access the plugin settings.
Enter your Flutterwave API keys and configure the payment options as desired.
Save the configuration settings.
Conclusion:
The flutterwaveShopware plugin is a valuable tool for Shopware 6 store owners looking to expand their payment options and provide their customers with a secure and convenient payment experience through Flutterwave. The plugin is easy to install, configure, and use, and provides a seamless integration with the Shopware payment system.
