Directory Synchronizer
======================

[![Test passing](https://travis-ci.org/HopeUA/dir-sync.svg?branch=master)](https://travis-ci.org/HopeUA/dir-sync)

Console tool to synchronize files in two directories.

### Usage
Install

    git clone https://github.com/HopeUA/dir-sync.git dir-sync
    cd dir-sync
    composer install --optimize-autoloader

Configure
... in development

Run tests

    bin/phpunit -c app/
    
Add new cronjob to run synchronization periodically

    php app/console sync:run
    
Logs are stored in *app/logs/sync.log*

