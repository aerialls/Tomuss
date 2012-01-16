# Tomuss

Permet de visualiser rapidement l'ensemble de ses notes disponible sur Tomuss

## Installation

Nécessite `PHP 5.3` pour fonctionner ainsi que `cURL`.

    cd Tomuss
    wget http://getcomposer.org/composer.phar
    php composer.phar install

## Utilisation

Pour récupérer les notes :

    cd Tomuss
    php get.php username password

Il est possible d'afficher l'ensemble des notes simplement en rajoutant un
troisième paramètre `show` :

    cd Tomuss
    php get.php username password show

## Fonctionnement

Les notes sont stockées dans un fichier externe `notes`. Entre chaque mise à
jour du programme il est conseillé de le supprimer et de réaliser la commande
suivante :

    php composer.phar update