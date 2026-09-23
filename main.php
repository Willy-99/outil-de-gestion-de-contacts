<?php

require_once('DBConnect.php');
require_once('Contact.php');
require_once('ContactManager.php');
require_once('Command.php');

$db = new DBConnect();

if ($db->getPDO() === null) {
    echo "La connexion à la base de données a échoué.\n";
    }
 else {
    echo "Connexion réussie !\n";
}

$contactManager = new ContactManager($db->getPDO());
$command = new Command($contactManager);

while (true) {
    $line = readline("Entrez votre commande : ");

    switch (true) {
        case $line === "exit":
            echo "Au revoir !\n";
            exit(0);
        case preg_match('/^help(?: (.+))?$/', $line, $matches):
            $command->help($matches[1] ?? null);
            break;
        case $line === "list":
            $command->list();
            break;
        case $line === "detail":
            $command->detail();
            break;
        case preg_match('/^create (.+) (.+) (.+)$/', $line, $matches):
            $command->create($matches[1], $matches[2], $matches[3]);
            break;
        case preg_match('/^delete (\d+)$/', $line, $matches):
            $command->delete((int) $matches[1]);
            break;
        case $line === "update":
            $command->update();
            break;
        default:
            echo "Commande inconnue. Tapez 'help' pour la liste des commandes.\n";
    }  
}