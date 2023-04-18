# Xpense
Application de gestion de budget et des dépenses

## Stack 
- Symfony 6.1
- PostgreSQL | MySQL
- Bootstrap 5
- Twig 


## Pré requis
PHP > 8.1 -> [Doc installation PHP](https://www.php.net/manual/fr/install.php)
 - Composer > 1.8 -> [Doc installation de Composer](https://getcomposer.org/download/)
 - Symfony console (facultatif) -> [Doc installation de la console de Symfony](https://symfony.com/doc/current/components/console.html)

## Installation 

### Clôner le projet 
```
git clone https://github.com/fagathe-dev/budget.git
```

### Installer les dépendances de Symfony et les dépendances du projet
```
composer install
```

## Configureation  

### Copier le fichier .env.template -> .env
```sh 
cp .env.template .env
```

### Configurer les variables d'environnement dans le fichier .env 
```sh 
APP_ENV=
APP_DEBUG=
DATABASE_URL=
MAILER_DSN=  
APP_VERSION=
```
**APP_ENV**      : l'env de l'application  
**APP_DEBUG**    : le mode débug  
**APP_VERSION**  : version de l'application  
**DATABASE_URL** : base de données de l'application   
**MAILER_DSN**   : SMTP de l'application  
    

## Data  

### Générer la base de données et les migrations 

```sh
php bin/console doctrine:database:create (facultatif)  
php bin/console make:migration  
php bin/console doctrine:migrations:migrate --no-interaction  
```

### Charger le jeu de données avec les Fixtures
```sh
php bin/console doctrine:fixtures:load --no-interaction
```

Une fois que tout est installé vider le cache avec la commande 
```sh
php bin/console cache:clear
```     

C'est parti ! 🚀
