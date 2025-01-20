00 -Pour faire tourner le projet en local les prérequis sont : 
- un Ide classique (ex. vscode, phpstorm, etc,..)
- un serveur apache ( wampserver64, mamp, etc,..)
- composer <= v2.8.0
- php <= v8.2
- phpMyadmin <= v5.2.0
- Git
- Symfony CLI pour la gestion du serveur local

01 - Récupération du projet : 
- Récuperer le lien de repo distant : https://github.com/maponch/webcourses
- Lancer l'éditeur de code
- Lancer son serveur apache
- via son terminale, se rendre dans le dossier de developpement de votre serveur apache : www / localhost
- Cloner le repo, entrer cette commande dans votre terminale : git clone https://github.com/maponch/webcourses.git
- Via le terminale se rendre dans le dossier fraîchement cloné

02 - Base de donnée :
- Lancer le serveur Apache
- Dans phpMyAdmin, créer une base de données du même nom que celle du projet : webcourses
- Vérifier le fichier .env et faire correspondre :
   DATABASE_URL="mysql://root:@127.0.0.1:3306/webcourses?serverVersion=8.0.31&charset=utf8mb4"
- Une fois que la connexion entre la DB et le projet est effectuer
- Lancer la commande pour créer la base données : php bin/console doctrine:database:create
- Appliquer les migration : php bin/console doctrine:migrations:migrate

03 - initialisation du projet : 
- Installer les dépendances via la commande dans le terminale de votre ide : composer install
- Générer les données factice à l'aide la commande dans le terminale de votre ide : php bin/console doctrine:fixtures:load
- lancer votre serveur local à l'aide la commande dans le terminale de votre ide : symfony server:start

04 - projet :
- Se rendre sur l'url indiquer dans le terminale pour accéder au projet : http://127.0.0.1:8000/
- Choisir dans la base de données l'email d'un utilisateur qui à le role de votre choix (admin ou user) de la table user
- Se connecter avec l'email, mot de passe : password

05 - fermeture : 
- Entrer 'ctrl + c' dans le terminale pour pouvoir a nouveeau écrire dedans
- Entrer : symfony server:stop
- Couper votre serveur apache 
