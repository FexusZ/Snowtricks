# Snowtricks
## Projet n°6 OpenClassroom DAPHP
Réalisation d'un site en php/Symfony (Snowtricks)

### Objectifs :
* Pages pour les utilisateur non connecté
	* Page d'accueil listant les figures
	* Page pour visualiser le details d'un post, ainsi que les commentaires lié au post
	* Page de connexion
	* Page de création de compte
	* Page pour réinitialiser le mot de passe
* Pages pour les utilisateur connecté
	* Page d'accueil listant les figures avec la possibilité de modifier/supprimer un post
	* Page pour la création de post
	* Page d'edition de post
	* Possibilité de supprimé un post
	* Page pour la gestion du profil
	* Page pour visualiser le details d'un post avec la possibilité de modifier/supprimer le post, ainsi que les commentaires lié au post et un formulaire pour ajouter un commentaires
### Language utilisés ?
	* HTLM5, CSS3
	* PHP 7.4.7, MySQL, Symfony
### Comment utilisé le projet ?
* Installation :
	* Importer le projet sur votre serveur
	* Telecharger composer, et faite un 'composer install', pour generer le vendor et l'autoloadeur de composer.
	* Modifier le fichier .env pour y mettre les informations de connexion a la base de données, ainsi que le serveur SMTP
	* Mettre en place la base de données :
		* Par ligne de commande, passer le serveur en mode developpeur pour acceder au maker de symfony, faite un php bin/console ou symfony console make:migration, puis un doctrine:migrations:migrate
		* Sinon, directement en bdd, créé une nouvelle base de données, et importer la base de données fournis avec le projet (le fichier contient un jeu de données avec 10 post)
* Explication :
	* Le projet suis une architecture MVC :
		* Modèle : Va chercher les données brute. (Dans notre cas se sont les dossiers Entity et Repository)
		* Vue : Affiche les données (Dossier Template)
		* Contrôleur : Gère la liaison entre Modèles et Vues, et gère toute la logique. (Dossier Controller)
	* Le fichier .env contient des paramètres d'environnement.
### Lien Codacy
* https://app.codacy.com/manual/FexusZ/Projet_5/dashboard?bid=17711610
### V.1.0.0
* Initial release
