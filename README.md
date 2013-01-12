Myopengrid Web Interface
===

Mwi is a web interface and content management system for opensim.
## Installation

Make sure to set full permissions on the Mwi storage folder

    chmod -R 777 storage
    
Upload the contents of the Mwi public directory to your webserver
public directory in some host providers it's called public_html, 
httdocs, etc. All the other folders MUST be outside your server 
public directory.

Change the setting `$paths['public']` to your server pulbic folder, on the
file `paths.php`.

```php
$paths['public'] = 'public';
```
to
```php
$paths['public'] = 'your_server_public_directory';
```

Visit your domain name to start the installation

    http://yourdomain.com/install
