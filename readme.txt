This plugin allows you to store login data (including the username, login time, IP address, and country) for users who log into your WordPress site. The login data is stored in a custom database table and is displayed as a dashboard widget on the WordPress dashboard. This can be useful for tracking login activity and identifying potential security issues.

To use the plugin, simply install and activate it on your WordPress site. The login data will be automatically recorded and displayed in the dashboard widget. You can customize the display of the login data by modifying the login_data_dashboard_widget_display() function.

The plugin uses the MaxMind GeoIP2 API to determine the country of an IP address. You will need to sign up for a free trial of the MaxMind GeoIP2 API and enter your API key in the get_country_from_ip() function in order to use this feature.

This plugin is easy to use and requires no configuration. Simply install and activate it to start tracking login data on your WordPress site.

The MaxMind GeoIP2 API is a paid service that provides detailed geolocation data for IP addresses, including the country, region, city, latitude, and longitude. It is used in this plugin to determine the country of an IP address when a user logs into the WordPress site.

In accordance with the General Data Protection Regulation (GDPR) and other privacy laws, it is important to ensure that any personal data collected and processed on your WordPress site is handled in a secure and transparent manner. If you use the MaxMind GeoIP2 API in this plugin, you will need to disclose this in your privacy policy and explain how the API is used to process users' IP addresses.

Here is some sample language that you can use in your privacy policy to disclose the use of the MaxMind GeoIP2 API:

"We use the MaxMind GeoIP2 API to determine the country of an IP address when a user logs into our site. This helps us track login activity and identify potential security issues. The MaxMind GeoIP2 API is a paid service that provides detailed geolocation data for IP addresses. By using our site, you consent to the processing of your IP address by the MaxMind GeoIP2 API in accordance with their privacy policy."

You should also include a link to the MaxMind GeoIP2 API privacy policy in your own privacy policy, so that users can learn more about how their data is being processed by the API. The MaxMind GeoIP2 API privacy policy can be found here: https://www.maxmind.com/en/privacy_policy.