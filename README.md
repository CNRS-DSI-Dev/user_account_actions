# User Account Actions

Owncloud 7 app that acts on user creation and deletion

The actions are :
* send mail
* automatic files backup before user deletion

## Mails

A parameter *MUST* be configured in global owncloud `config.php`
```php
'monitoring_admin_email' => 'monitoring@youdomain.tld',
```

This parameter is used as recipient address to send the emails to.

The "From" mail address have to be configured in your admin panel. See http://doc.owncloud.org/server/7.0/admin_manual/configuration/configuration_mail.html

The body of both mails may be changed : modify the templates (mail and html) which are in the `templates` app directory. The template's names are pretty straightforward.

## Backup

The backup is not activated by default. If you want to activate the automatic backup, please add these two keys in your `config.php`

```php
'backup_file_before_user_deletion' => true,
'backup_file_dir' => '/path/to/backup/dir',
```

All files (and only files) from datadir for the user will be saved into a specific `backup_file_dir` (`tmp` by default). Please be sure that your web server has write access on this `backup_file_dir` dir !

**The user custom parameters and datas (as contact datas, tags metadatas, share infos, etc.) will NOT be saved.**

## License and authors

|                      |                                          |
|:---------------------|:-----------------------------------------|
| **Author:**          | Patrick Paysant (<ppaysant@linagora.com>)
| **Copyright:**       | Copyright (c) 2014 CNRS DSI
| **License:**         | AGPL v3, see the COPYING file.
