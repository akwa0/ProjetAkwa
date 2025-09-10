## Allumer le server
---
symfony serve 
symfony server:start
symfony serve: stop
---

## Après clonage repo
---

composer install
(si dépendances Js -npù install )
---

## git 
---

git add .
git commit -m "message"
git remote add origin http/repotgit
git remote remove origin # efface lien avec le repo remote
---

# Synfony
apres avec configuré le fichier .env avec la connexion
symfony composer req symfony/orm-pack
symfony composer req symfony/maker-bundle --dev

# lancer la création de la BD
symfony console doctrine:database:create

# Creation /update des entités
symfony console make:entity

(valable pour créer une nouvelle ou rajouter des propriété à une existante)

# creer une migration, la lancer
symfony console make:migration
symfony console doctrine:migration:migrate




