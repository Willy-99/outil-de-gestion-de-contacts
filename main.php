<?php

// Charge les différentes classes nécessaires au fonctionnement de l'application.
require_once('DBConnect.php');
require_once('Contact.php');
require_once('ContactManager.php');
require_once('Command.php');


// Création de la connexion à la base de données.
$db = new DBConnect();

// Vérification de la connexion à la base de données.
if ($db->getPDO() === null) {
    echo "La connexion à la base de données a échoué.\n";
    exit(1);
} else {
    echo "Connexion réussie !\n";
}


// Création du gestionnaire de contacts.
// Il reçoit la connexion PDO afin de pouvoir communiquer avec MySQL.
$contactManager = new ContactManager($db->getPDO());

// Création du gestionnaire de commandes.
// Il reçoit le ContactManager pour pouvoir effectuer les opérations sur les contacts.
$command = new Command($contactManager);


// Boucle principale de l'application.
// Elle continue tant que l'utilisateur ne saisit pas la commande "exit".
while (true) {

    // Demande une commande à l'utilisateur.
    $line = readline("Entrez votre commande : ");


    // Utilisation d'un switch(true) pour pouvoir utiliser des conditions
    // différentes dans chaque case.
    switch (true) {

        // Quitte complètement le programme.
        case $line === "exit":
            echo "Au revoir !\n";
            exit(0);


        // Affiche l'aide générale ou l'aide d'une commande précise.
        // Exemple : help ou help create
        case preg_match('/^help(?: (.+))?$/', $line, $matches):
            $command->help($matches[1] ?? null);
            break;


        // Affiche la liste de tous les contacts.
        case $line === "list":
            $command->list();
            break;


        // Lance la commande de comptage.
        // strtolower() permet d'accepter "Compter", "COMPTER", "compter", etc.
        case strtolower($line) === "compter":
            $command->count();
            break;


        // Recherche et affiche un contact.
        case $line === "detail":
            $command->detail();
            break;


        // Clone un contact existant.
        // Le nom de la méthode PHP reste cloneContact(),
        // mais la commande saisie par l'utilisateur est "clone contact".
        case strtolower($line) === "clone contact":
            $command->cloneContact();
            break;


        // Crée un nouveau contact de manière interactive.
        case $line === "create":
            $command->create();
            break;


        // Supprime un contact de manière interactive.
        case $line === "delete":
            $command->delete();
            break;


        // Modifie un contact de manière interactive.
        case $line === "update":
            $command->update();
            break;


        // Si aucune commande connue ne correspond à la saisie.
        default:
            echo "Commande inconnue. Tapez 'help' pour la liste des commandes.\n";
    }
}