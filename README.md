# User Account Actions

Owncloud 7 app that send emails on user creation and deletion

The body of the mails may be changed : modify the templates (mail and html) which are in the `templates` directory. The template's names are pretty straightforward.

## Install

A parameter *MUST* be configured in global owncloud config.php
```php
'monitoring_admin_email' => 'monitoring@youdomain.tld',
```

This parameter is used as recipient address to send the emails to.

The "From" mail address have to be configured in your admin panel. See http://doc.owncloud.org/server/7.0/admin_manual/configuration/configuration_mail.html

## License and authors

|                      |                                          |
|:---------------------|:-----------------------------------------|
| **Author:**          | Patrick Paysant (<ppaysant@linagora.com>)
| **Copyright:**       | Copyright (c) 2014 CNRS DSI
| **License:**         | AGPL v3, see the COPYING file.