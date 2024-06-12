#!/bin/sh
php phpDocumentor.phar -d ./app -t ./docs/laravel
php phpDocumentor.phar -d ./database -t ./docs/database