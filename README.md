Myopengrid Web Interface
===

Mwi is a web interface and content management system for opensim.
## Installation

Set full permissions to files:

    chmod 777 application/config/application.php
    chmod 777 application/config/database.php
    chmod 777 application/bundles.php

Set full permissions to directories:

    chmod -R 777 storage
    chmod -R 777 bundles
    chmod -R 777 public/themes
    chmod -R 777 public/bundles
    
    
Upload the contents of the Mwi public directory to your webserver
public directory. In some host providers it's called public_html, 
httdocs, etc. All the other folders MUST be outside your server 
public directory.

Change the setting `$paths['public']` to your server pulbic directory, on the
file `paths.php`.

```php
$paths['public'] = 'public';
```
to
```php
$paths['public'] = 'your_server_public_directory';
```

Visit your domain to start the installation

    http://yourdomain.com/install
