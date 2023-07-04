## Installation

Installation des dépendances composer
```bash
composer install
```

Création du schema de base de données à partir des entités
```bash
php bin/console doctrine:schema:create
```

Création des données nécessaires au fonctionnement de l'application ainsi que des données de test
```bash
php bin/console doctrine:fixtures:load
```

Génération de clé publique et privée pour la signature des JWT 
```bash
php bin/console lexik:jwt:generate-keypair
```
