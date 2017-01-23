Directory Synchronizer
======================

[![Test passing](https://img.shields.io/travis/HopeUA/dir-sync.svg?style=flat-square)](https://travis-ci.org/HopeUA/dir-sync)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/HopeUA/dir-sync.svg?style=flat-square)](https://scrutinizer-ci.com/g/HopeUA/dir-sync/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/HopeUA/dir-sync.svg?style=flat-square)](https://scrutinizer-ci.com/g/HopeUA/dir-sync/?branch=master)
[![SensioLabs Insight](https://insight.sensiolabs.com/projects/d58bf4a1-e650-41eb-ac29-1a876fc9e888/mini.png)](https://insight.sensiolabs.com/projects/d58bf4a1-e650-41eb-ac29-1a876fc9e888)

Console tool to synchronize files in two directories.

### Usage
#### Install

    git clone https://github.com/HopeUA/dir-sync.git dir-sync
    cd dir-sync
    composer install --optimize-autoloader

#### Configure
Write your configuration to __app/config/parameters.yml__

    app.name: app
    
    app.master.storage: local
    app.master.path: /master
    app.master.filters:
        path:
            pattern: '~[a-z]\.mp4~'
        excludeEpisodes:
            path: '/path/to/file.json'
    
    app.slave.storage: local
    app.slave.path: /slave
    app.slave.path_tpl: /__program__/__uid__
    app.slave.filters: null

    app.logger: file
    file.log.path: %kernel.logs_dir%/sync.log

#### Run tests

    composer test
    
#### Schedule 
Add new cronjob to run synchronization periodically

    php bin/console sync:run
    
#### Monitor
Default logs location – *var/logs/sync.log*

