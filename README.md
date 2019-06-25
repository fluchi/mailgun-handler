# Mailgun handler

Mailgun handler is a [Monolog](https://github.com/Seldaek/monolog) handler that email log entries using [Mailgun API](https://github.com/mailgun/mailgun-php).

## Installation
### Requirements 
Mailgun handler requires [PHP](https://php.net/) 7.1.3 or higher, [Monolog](https://github.com/Seldaek/monolog) 1.X or higher and [Mailgun API](https://github.com/mailgun/mailgun-php) 2.8 or higher.

### Using composer

The easiest way to install Mailgun handler is via [composer](https://getcomposer.org/) typing the following command:

```console
$ composer require fluchi/mailgun-handler
```

### Configuration

On your project's root, create a file called _.env_, if there is any, and added the following content:
```
# /.env
MAILGUN_API_KEY="your mailgun API key"
MAILGUN_DOMAIN="your mailgun domain"
MAILGUN_FROM="default from"
MAILGUN_TO="default to"
```
_MAILGUN_FROM_ and _MAILGUN_TO_ are optional.

Default level to email is Logger::WARNING.
You can change as shown below.
## Usage
After installation, Mailgun handler will be available over `MailgunHandler` namespace.

```php
use Monolog\Logger;
use MailgunHandler\MailgunHandler;

require __DIR__ . '/vendor/autoload.php';

$logger = new Logger('foobar-channel');

// email based on .env config file
$logger->pushHandler(new MailgunHandler('email subject'));

// email WARNING level
$logger->pushHandler(new MailgunHandler('email subject', null, null, Logger::WARNING));

// you can change from and to on parameters 2 and 3, respectivelly
$logger->pushHandler(new MailgunHandler('email subject', 'foo@example.com', 'bar@example.com'));
```
## License
This library is licensed under the MIT license. See the [LICENSE](https://github.com/fluchi/mailgun-handler/blob/master/LICENSE) file for details.
## Chagelog
