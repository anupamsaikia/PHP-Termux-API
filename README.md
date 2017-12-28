# PHP-Termux-API
A PHP Single Page Web Application to execute Termux:API commands remotely from any device

# Requirements
* [Termux App](https://play.google.com/store/apps/details?id=com.termux) and [Termux:API](https://play.google.com/store/apps/details?id=com.termux.api) installed with all necessary permissions granted.
* `php`, `php-apache` and `apache2` package must be installed in Termux app.

#Usage
1. Configure your webserver in Termux as shown [here](https://github.com/termux/termux-packages/issues/1074) or you may setup in your own way.
1. Place the api.php file in the configured DocumentRoot folder.
1. Run the server using `apachectl start` .
1. You can now browse the file from any connected device and execute commands.
![Screenshot](https://github.com/anupamsaikia/PHP-Termux-API/raw/master/assets/1.jpg "Screenshot of the User Interface")




