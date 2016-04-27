# Omnipay: Alert

**Alert driver for the Omnipay PHP payment processing library**



This driver supports the remote Alert Payment Gateway (DPG) service. Payment information is sent and received via XML messages. Customers typically stay on the originating website with this method of integration.

## Installation

The Alert Omnipay driver is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "dignat/omnipay-alert": "~1.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

This driver supports two transaction types:
 * Purchase (including 3D Secure support if card holder is registered)
 
For general Omnipay usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug in this driver, please report it using the [GitHub issue tracker](https://github.com/omnipay/Alert/issues),
or better yet, fork the library and submit a pull request.
