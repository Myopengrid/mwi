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

Visit your domain to start the installation

    http://yourdomain.com/install


## Updating an Existing Installation
To update your application and keep all your data (database changes), you can copy all the files but you need to reset some settings on 3 files:

your_directory/application/config/application.php

```php

'installed' => false,
to
'installed' => true,

'key' => '',
to
'key' => 'youroldkey',

```

your_directory/application/config/database.php

```php

'connections' => array(

        'default' => array(
            'driver'   => 'mysql',
            'host'     => '127.0.0.1',
            'database' => 'your_database_name',
            'username' => 'your_database_username',
            'password' => 'your_database_password',
            'charset'  => 'utf8',
            'prefix'   => 'your_database_prefix', // default is _mwi
            'port'     => '3306',
        ),
    ),
```

your_directory/bundles.php

```php

return array(
    'install' => array(
        'auto'    => true,
        'handles' => 'install',
    ),
);
to
return array(
    'settings' => array(
        'auto'    => true,
        'handles' => 'settings',
    ),
    'modules' => array(
        'auto'    => true,
        'handles' => 'modules',
    ),
);
```
