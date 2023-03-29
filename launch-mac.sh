#!/bin/sh
api_dir="/Users/fagathe/workspace/perso/budget"
api_host="dev.budget.agathefrederick.fr"
port='5500'
# echo "127.0.0.1\t${api_host}" | sudo tee -a /etc/hosts

# lance le service postgresql
brew services start postgresql@14
cd $api_dir
echo 'cd api dir'
echo 'ouvrir le projet sur vscode'
code .
bin/console c:c -n
while getopts ':i:b' options; do
    case $options in 
        i) 
            mv /Users/fagathe/workspace/perso/.env .
            bin/console d:d:d -f
            composer install
            rm -f migrations/*
	        bin/console d:d:c 
            bin/console m:migration
            bin/console d:m:m -n 
            bin/console d:f:l -n 
            bin/console c:c -n;;
        b) 
            echo "open http://${api_host}:${port} in browser"
            open http://$api_host:$port;;
    esac
done
            
# lance le serveur interne de php
php -S $api_host:$port -t public

# stop le service postgres lorsqu'on stop le script
trap "brew services stop postgresql@14" EXIT
