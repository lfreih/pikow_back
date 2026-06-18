# Pikow - API Backend

API centrale du projet Pikow développée avec Symfony 7 et API Platform. Elle gère la persistance des données, l'authentification JWT, la validation des comptes utilisateurs et la logique liée aux parties.

## Prérequis
- PHP 8.3+
- Composer
- MySQL / MariaDB

## Installation

### Dev

```bash
git clone https://github.com/lfreih/pikow_back.git pikow_back
cd pikow_back
composer install
cp .env .env.local
# Éditer .env.local avec vos valeurs + DATABASE_URL="mysql://user:password@127.0.0.1:3306/pikow"

# Générer les clés JWT
php bin/console lexik:jwt:generate-keypair

# Créer la base de données et exécuter les migrations :
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Prod

```bash
sudo git clone https://github.com/lfreih/pikow_back.git pikow_back
sudo chown -R CURRENT_USER:www-data pikow_back
cd pikow_back
cp .env .env.local
# Éditer .env.local avec vos valeurs de production + APP_ENV=prod
# Générer une suite de lettres/chiffres pour APP_SECRET

composer install --no-dev --optimize-autoloader
php bin/console lexik:jwt:generate-keypair

# Sécurisation des clés JWT en production
sudo chown -R www-data:www-data config/jwt
sudo chmod 744 config/jwt/private.pem
sudo chmod 744 config/jwt/public.pem

find . -type d -exec chmod 775 {} +
find . -type f -exec chmod 664 {} +

# Avoir créé un utilisateur pour la base de données au préalable
sudo php bin/console doctrine:migrations:migrate --env=prod --no-interaction

php bin/console cache:clear
```

## Variables d'environnement

| Variable | Description | Exemple |
|----------|-------------|---------|
| APP_ENV | Environnement de l'application | dev/prod |
| DATABASE_URL | Connexion BDD | mysql://user:pass@127.0.0.1:3306/pikow |
| JWT_SECRET_KEY | Chemin clé privée JWT | config/jwt/private.pem |
| JWT_PUBLIC_KEY | Chemin clé publique JWT | config/jwt/public.pem |
| JWT_PASSPHRASE | Passphrase JWT | votre_passphrase |
| CORS_ALLOW_ORIGIN | Origines autorisées | ^https?://(localhost\|127\.0\.0\.1)(:[0-9]+)?$ |


## Architecture & Fonctionnalités

### Modélisation des données
L'application s'appuie sur deux entités principales : 
- `User` : Représente les comptes utilisateurs. L'authentification utilise l'adresse email unique et un mot de passe haché.
- `Game` : Représente les parties de jeu créées par les utilisateurs. Une relation de type OneToMany lie `User` à `Game` (un utilisateur peut posséder plusieurs parties).

### Authentification JWT
Sécurisée par LexikJWTAuthenticationBundle. Le cycle d'authentification s'organise ainsi :
- Soumission des identifiants à `/api/login` pour obtenir un jeton.
- Transmission du jeton via l'en-tête HTTP `Authorization: Bearer <token>` pour l'accès aux points d'accès protégés.

Le décodage, le hachage sécurisé à l'inscription et l'interception des requêtes sont consolidés par l'intermédiaire du composant personnalisé `UserRegisterProcessor`.

### Points d'API
cf API_CONTRACT.md


## Configuration serveur

```apache
<VirtualHost *:80>
    ServerName api.mondomaine.fr
    DocumentRoot /var/www/pikow_back/public
    DirectoryIndex index.php
    <Directory /var/www/pikow_back/public>
        AllowOverride All
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^ index.php [L]
        </IfModule>
    </Directory>
</VirtualHost>

# Activer le site
sudo a2ensite pikow_back.conf

# Redémarrer Apache
sudo systemctl restart apache2
```

## HTTPS (prod)

```bash
# Installer Certbot
sudo apt install certbot python3-certbot-apache

# Générer le certificat
sudo certbot --apache -d api.mondomaine.fr

# Renouvellement automatique (vérifie que le cron est actif)
sudo certbot renew --dry-run
```
