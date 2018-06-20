# pkgsrch

RESTful API for searching for YUM packages.

To Test:
```
composer install
php -S localhost:8080 -t public public/index.php
```

Example URL: `http://localhost:8080/centos/v7/primary/SEARCHTERM`

Repo data was pulled from http://mirror.centos.org/centos/7/os/x86_64/repodata/
