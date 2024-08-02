#!/bin/bash

function update() {

    echo "# Artisan Down"
    php artisan down
    rm composer.lock

    echo "# git PULL"
    git pull

    echo "# Remove Vite Hot File"
    rm public/hot

    #Ask: composer update?
    echo "Run composer update? (y/n) [n]"
    read yesNo
    #Check if empty string
    if test -z $yesNo
    then
        echo "Accepted Default: No"
    #Check Y / N
    elif test $yesNo == 'y' || test $yesNo == 'Y' || test $yesNo == 'Yes' || test $yesNo == 'yes' || test $yesNo == 'YES'
    then
        echo "Detected you answered: YES"
        echo "# Composer Update..."
        composer update
    else
        echo "Detected you answered something other than yes: Default no"
    fi

    # autoload classmap files
    echo "Composer dump Autoload classmap files"
    composer dump-autoload

    echo "migrate"
    php artisan migrate

    optimize

    echo "artisan up"
    php artisan up

    echo " "
    echo "*******************************************************"
    echo "!      Remember to SEED new data                      !"
    echo "! eg: php artisan db:seed --class=\"PermissionsSeeder\" !"
    echo "*******************************************************"
}

function pa() {
    #https://devhints.io/bash
    php artisan $@
}

function optimize() {
    echo "permission:cache-reset"
    php artisan permission:cache-reset
    echo "optimize:clear"
    php artisan optimize:clear
    echo "icons:clear"
    php artisan icons:clear
    echo "icons:cache"
    php artisan icons:cache
    echo "optimize"
    php artisan optimize
}

function cda() {
    composer dump-autoload
}

# Git
function gp() {
    git pull
}

function gs() {
    git status
}
function paoc() {
    php artisan optimize:clear
}
function pao() {
    php artisan optimize:clear
    php artisan optimize
}
# env is already used by BASH
function nenv() {
  nano .env
}

function la() {
    #https://devhints.io/bash
    ls -la $@
}
