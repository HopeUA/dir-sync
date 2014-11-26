Directory Synchronizer
======================

[![Test passing](https://img.shields.io/travis/HopeUA/dir-sync.svg?style=flat-square)](https://travis-ci.org/HopeUA/dir-sync)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/HopeUA/dir-sync.svg?style=flat-square)](https://scrutinizer-ci.com/g/HopeUA/dir-sync/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/HopeUA/dir-sync.svg?style=flat-square)](https://scrutinizer-ci.com/g/HopeUA/dir-sync/?branch=master)

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

