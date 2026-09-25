<?php

// Permet d'utiliser la classe ContactManager dans cette classe.
require_once('ContactManager.php');

class Command
{
    // ContactManager est utilisé par les commandes pour accéder
    // aux données stockées dans la base de données.
    private ContactManager $contactManager;

    // Le ContactManager est fourni lors de la création de Command.
    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    /**
     * Affiche l'aide générale ou l'aide détaillée d'une commande.
     *
     * Si aucune commande n'est fournie, l'aide générale est affichée.
     * Exemple : help
     *
     * Si une commande est fournie, son fonctionnement est détaillé.
     * Exemple : help create
     */
    public function help(?string $command = null): void
{
    switch ($command) {
        // Aucune commande précisée : affichage de la liste générale.
        case null:
            echo "Commandes disponibles :\n";
            echo "list          - Liste tous les contacts\n";
            echo "detail        - Affiche un contact\n";
            echo "create        - Crée un contact\n";
            echo "clone Contact  - Clone un contact\n";
            echo "delete        - Supprime un contact\n";
            echo "update        - Modifie un contact\n";
            echo "Compter       - Compte les contacts ou recherche les doublons\n";
            echo "help          - Affiche l'aide\n";
            echo "exit          - Quitte le programme\n";

            echo "\nTapez 'help <commande>' pour plus d'informations.\n";
            break;

        // Aide concernant la commande list.
        case 'list':
            echo "LIST\n";
            echo "Liste tous les contacts.\n";
            echo "Exemple :\n";
            echo "  list\n";
            break;

        // Aide concernant la commande detail.
        case 'detail':
            echo "DETAIL\n";
            echo "Affiche les informations d'un contact à partir de son ID, nom, email ou numéro de téléphone.\n";
            echo "La commande fonctionne de manière interactive.\n";
            echo "Syntaxe : detail\n";
            echo "Exemple : \n";
            echo "  detail\n";
            echo "  Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) :";
            echo "  name";
            echo "  Valeur : ";
            echo "  Hermione";
            break;

        // Aide concernant la création d'un contact.
        case 'create':
            echo "CREATE\n";
            echo "Crée un nouveau contact.\n";
            echo "Syntaxe : create <name> <email> <phone_number>\n";
            echo "Exemple : create Hermione hermione@magie.com 0627643782\n";
            break;

        // Aide concernant le clonage d'uncontact.
        case "clone contact":
            echo "CLONE CONTACT\n";
            echo "Clone un contact existant.\n";
            echo "La recherche du contact est interactive.\n";
            echo "Vous pouvez rechercher le contact par ID, nom, email ou téléphone.\n";
            echo "Vous pourrez ensuite modifier les champs avant de créer le clone.\n";
            echo "Exemple :\n";
            echo "  clone Contact\n";
            echo "  Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : id\n";
            echo "  Valeur : 5\n";
            echo "  Vous souhaitez cloner le contact suivant :\n";
            echo "  5 - Hermione Granger - hermione@magie.com - 0627643782\n";
            echo "  Souhaitez-vous modifier les champs (Y/N) ? N\n";
            echo "  Contact dupliqué avec succès.\n";
            break;

        // Aide concernant la modification d'un contact.
        case 'update':
            echo "UPDATE\n";
            echo "Modifie un champ d'un contact.\n";
            echo "Syntaxe : update\n";
            echo "Exemple :\n";
            echo "  update\n";
            echo "  ID du contact : 6\n";
            echo "  Quel champ voulez-vous modifier ? : email\n";
            echo "  Nouvelle valeur : hermione.granger@poudlard.com\n";
            break;        

        // Aide concernant la suppression d'un contact.
        case 'delete':
            echo "DELETE\n";
            echo "Supprime un contact à partir de son ID.\n";
            echo "Syntaxe : delete <id>\n";
            echo "Exemple : delete 6\n";
            break;

        // Aide concernant la commande de comptage.
        case "Compter":
            echo "COMPTER LES ENTRÉES\n";
            echo "Compte les contacts ou recherche les doublons.\n";
            echo "La commande fonctionne de manière interactive.\n";
            echo "\n";
            echo "Choix disponibles :\n";
            echo "  1 - Compter tous les contacts\n";
            echo "  2 - Compter les contacts correspondant à une valeur\n";
            echo "  3 - Afficher les doublons\n";
            echo "\n";
            echo "Pour le choix 2, vous pouvez rechercher par : name, email ou phone.\n";
            echo "Pour le choix 3, vous pouvez rechercher les doublons par : name, email ou phone.\n";
            echo "\n";
            echo "Exemple :\n";
            echo "  count\n";
            echo "  Votre choix : 2\n";
            echo "  Quel champ voulez-vous utiliser ? (name/email/phone) : name\n";
            echo "  Valeur : Hermione\n";
            echo "  2 contact(s) trouvé(s).\n";
            break;

        // Aide concernant la commande help.
        case 'help':
            echo "HELP\n";
            echo "Affiche la liste des commandes disponibles ou l'aide détaillée d'une commande.\n";
            echo "Syntaxe :\n";
            echo "  help\n";
            echo "  help <commande>\n";
            echo "Exemple :\n";
            echo "  help cloneContact\n";
            break;

        // Aide concernant la commande exit.
        case 'exit':
            echo "EXIT\n";
            echo "Quitte le programme.\n";
            echo "Exemple :\n";
            echo "  exit\n";
            break;

        // Commande passée à help mais inconnue.
        default:
            echo "Aucune aide disponible pour la commande '$command'.\n";
            echo "Tapez 'help' pour voir les commandes disponibles.\n";
            break;
    }
}

    /**
     * Affiche tous les contacts présents dans la base de données.
     */
    public function list(): void
    {
        // Récupération des contacts via ContactManager.
        $contacts = $this->contactManager->findAll();

        // Affichage de chaque objet Contact.
        foreach ($contacts as $contact) {
            echo $contact . "\n";
        }
    }

    /**
     * Recherche et affiche un ou plusieurs contacts.
     */
    public function detail(): void
    {
        try {

            // Demande à l'utilisateur quel champ utiliser pour la recherche.
            $field = readline(
                "Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : "
            );

            // Vérifie que le champ demandé fait partie des champs autorisés.
            while (!in_array($field, ['id', 'name', 'email', 'phone'], true)) {
                echo "Champ invalide. Utilisez id, name, email ou phone.\n";

                $field = readline(
                    "Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : "
                );
            }

            // Récupération de la valeur à rechercher.
            $value = readline("Valeur : ");


            // Choix de la méthode de recherche en fonction du champ.
            switch ($field) {

                case 'id':
                    $contact = $this->contactManager->findById((int) $value);

                    if ($contact === null) {
                        echo "Aucun contact trouvé avec l'ID $value.\n";
                        return;
                    }

                    echo $contact . "\n";
                    break;


                case 'name':
                    // Un nom peut correspondre à plusieurs contacts.
                    $contacts = $this->contactManager->findByName($value);

                    if (empty($contacts)) {
                        echo "Aucun contact trouvé avec le nom '$value'.\n";
                        return;
                    }

                    foreach ($contacts as $contact) {
                        echo $contact . "\n";
                    }

                    break;


                case 'email':
                    $contact = $this->contactManager->findByEmail($value);

                    if ($contact === null) {
                        echo "Aucun contact trouvé avec l'email '$value'.\n";
                        return;
                    }

                    echo $contact . "\n";
                    break;


                case 'phone':
                    $contact = $this->contactManager->findByPhone($value);

                    if ($contact === null) {
                        echo "Aucun contact trouvé avec le numéro de téléphone '$value'.\n";
                        return;
                    }

                    echo $contact . "\n";
                    break;
            }

        } catch (Exception $e) {
            // Gestion des erreurs provenant notamment de la base de données.
            echo "Erreur : " . $e->getMessage() . "\n";
        }
    }


    /**
     * Recherche un contact et retourne son objet Contact.
     *
     * Cette méthode est privée car elle sert uniquement aux autres commandes
     * de la classe Command.
     *
     * Elle est notamment utilisée par cloneContact() et delete().
     */
    private function searchContact(): ?Contact
    {
        // Demande du champ utilisé pour identifier le contact.
        $field = readline(
            "Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : "
        );

        // Vérification du champ saisi.
        while (!in_array($field, ['id', 'name', 'email', 'phone'], true)) {
            echo "Champ invalide. Utilisez id, name, email ou phone.\n";

            $field = readline(
                "Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : "
            );
        }

        // Demande de la valeur à rechercher.
        $value = readline("Valeur : ");


        // Appel de la méthode de recherche correspondant au champ choisi.
        switch ($field) {

            case 'id':
                return $this->contactManager->findById((int) $value);


            case 'email':
                return $this->contactManager->findByEmail($value);


            case 'phone':
                return $this->contactManager->findByPhone($value);


            case 'name':
                // findByName() retourne un tableau car plusieurs contacts
                // peuvent avoir un nom correspondant.
                $contacts = $this->contactManager->findByName($value);

                if (empty($contacts)) {
                    return null;
                }

                // Si plusieurs contacts correspondent au nom,
                // on les affiche mais aucune sélection n'est encore effectuée.
                if (count($contacts) > 1) {
                    echo "Plusieurs contacts correspondent à cette recherche.\n";

                    foreach ($contacts as $contact) {
                        echo $contact->getId() . " - " . $contact . "\n";
                    }

                    return null;
                }

                // Un seul contact correspond au nom.
                return $contacts[0];
        }

        return null;
    }


    /**
     * Clone un contact existant.
     *
     * Le contact original n'est jamais modifié.
     * Un nouvel objet Contact est créé puis enregistré en base.
     */
    public function cloneContact(): void
    {
        try {

            // Recherche du contact à cloner.
            $contact = $this->searchContact();

            if ($contact === null) {
                echo "Aucun contact selectionné pour le clonage.\n";
                return;
            }


            // Affichage du contact sélectionné avant confirmation.
            echo "\nContact sélectionné pour le clonage : " . $contact . "\n";

            $confirm = readline(
                "Voulez-vous vraiment cloner ce contact ? (oui/non) : "
            );


            // Vérification de la réponse de l'utilisateur.
            while (!in_array(strtoupper($confirm), ['OUI', 'NON'], true)) {
                echo "Réponse invalide. Utilisez 'oui' ou 'non'.\n";

                $confirm = readline(
                    "Voulez-vous vraiment cloner ce contact ? (oui/non) : "
                );
            }


            if (strtoupper($confirm) === "OUI") {

                // Demande des nouvelles valeurs.
                // Une entrée vide permet de conserver la valeur originale.
                $name = readline("Nouveau nom (Entrée pour conserver) : ");
                $email = readline("Nouvel email (Entrée pour conserver) : ");
                $phone = readline(
                    "Nouveau numéro de téléphone (Entrée pour conserver) : "
                );


                // Si aucune nouvelle valeur n'est saisie,
                // on conserve la valeur du contact original.
                if (empty($name)) {
                    $name = $contact->getName();
                }

                if (empty($email)) {
                    $email = $contact->getEmail();
                }

                if (empty($phone)) {
                    $phone = $contact->getPhoneNumber();
                }

            } else {

                // L'utilisateur a refusé de modifier les valeurs.
                $name = $contact->getName();
                $email = $contact->getEmail();
                $phone = $contact->getPhoneNumber();
            }


            // Création d'un nouvel objet Contact.
            // L'ID est mis à 0 car MySQL générera automatiquement le nouvel ID.
            $clone = new Contact(0, $name, $email, $phone);


            // Enregistrement du nouveau contact en base.
            // La méthode retourne le Contact avec son nouvel ID.
            $newContact = $this->contactManager->cloneContact($clone);


            echo "Contact dupliqué avec succès.\n";
            echo "Nouveau contact : " . $newContact . "\n";

        } catch (Exception $e) {
            echo "Erreur lors du clonage du contact : " . $e->getMessage() . "\n";
        }
    }


    /**
     * Création interactive d'un nouveau contact.
     */
    public function create(): void
    {
        try {

            // Demande des informations du nouveau contact.
            $name = readline("Nom : ");
            $email = readline("Email : ");
            $phoneNumber = readline("Numéro de téléphone : ");


            // Transmission des informations au ContactManager
            // qui se charge de l'insertion en base de données.
            $this->contactManager->create(
                $name,
                $email,
                $phoneNumber
            );

            echo "Contact créé avec succès.\n";

        } catch (Exception $e) {
            echo "Erreur lors de la création du contact : " . $e->getMessage() . "\n";
        }
    }


    /**
     * Recherche puis supprime un contact après confirmation.
     */
    public function delete(): void
    {
        try {

            // Recherche du contact à supprimer.
            $contact = $this->searchContact();

            if ($contact === null) {
                echo "Aucun contact sélectionné pour la suppression.\n";
                return;
            }


            // Affiche les informations du contact avant de demander confirmation.
            echo "\nVous souhaitez supprimer le contact suivant : "
                . $contact->getId() . ","
                . $contact->getName() . ","
                . $contact->getEmail() . ","
                . $contact->getPhoneNumber() . ",";


            // Demande une confirmation avant suppression.
            $confirm = readline(
                "Voulez-vous vraiment supprimer ce contact ? (oui/non) : "
            );


            // Vérification de la réponse.
            while (!in_array(strtoupper($confirm), ['OUI', 'NON'], true)) {
                echo "Réponse invalide. Utilisez 'oui' ou 'non'.\n";

                $confirm = readline(
                    "Voulez-vous vraiment supprimer ce contact ? (oui/non) : "
                );
            }


            // Si l'utilisateur refuse, aucune suppression n'est effectuée.
            if (strtoupper($confirm) === "NON") {
                echo "Suppression annulée.\n";
                return;
            }


            // Suppression du contact à partir de son ID.
            $this->contactManager->delete($contact->getId());

            echo "Contact supprimé avec succès.\n";

        } catch (Exception $e) {
            echo "Erreur lors de la suppression du contact : " . $e->getMessage() . "\n";
        }
    }


    /**
     * Modification interactive d'un contact.
     */
    public function update(): void
    {
        try {

            // Demande de l'ID du contact à modifier.
            $id = (int) readline("ID du contact : ");

            // Demande du champ à modifier.
            $field = readline(
                "Quel champ voulez-vous modifier ? (name/email/phone) : "
            );


            // Vérification du champ.
            while (!in_array($field, ['name', 'email', 'phone'], true)) {
                echo "Champ invalide.\n";

                $field = readline(
                    "Quel champ voulez-vous modifier ? (name/email/phone) : "
                );
            }


            // Demande de la nouvelle valeur.
            $value = readline("Nouvelle valeur : ");


            // Sélection de la méthode ContactManager correspondant au champ.
            switch ($field) {

                case 'name':
                    $this->contactManager->updateName($id, $value);
                    break;


                case 'email':
                    $this->contactManager->updateEmail($id, $value);
                    break;


                case 'phone':
                    $this->contactManager->updatePhoneNumber($id, $value);
                    break;


                default:
                    echo "Champ inconnu : $field.\n";
                    return;
            }


            echo "Contact mis à jour avec succès.\n";

        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour du contact : " . $e->getMessage() . "\n";
        }
    }


    /**
     * Propose différentes opérations de comptage.
     */
    public function count(): void
    {
        try {

            echo "Que voulez-vous faire ?\n";
            echo "1 - Compter le nombre de contacts\n";
            echo "2 - Compter les contacts correspondant à une valeur\n";
            echo "3 - Afficher les contacts en doublons\n";


            // Demande du choix de l'utilisateur.
            $choice = readline("Entrez votre choix (1/2/3) : ");


            // Vérification du choix.
            while (!in_array($choice, ['1', '2', '3'], true)) {
                echo "Choix invalide. Veuillez entrer 1, 2 ou 3.\n";
                $choice = readline("Entrez votre choix (1/2/3) : ");
            }


            // Exécution de l'opération correspondant au choix.
            switch ($choice) {

                case '1':
                    // Compte tous les contacts présents dans la BDD.
                    $total = $this->contactManager->count();

                    echo "Nombre total de contacts : $total\n";
                    break;


                case '2':
                    // Lance le comptage selon un champ.
                    $this->countByfield();
                    break;


                case '3':
                    // Recherche les valeurs en doublon.
                    $this->findDuplicates();
                    break;
            }

        } catch (Exception $e) {
            echo "Erreur lors du comptage des contacts : " . $e->getMessage() . "\n";
        }
    }


    /**
     * Compte les contacts correspondant à une valeur.
     */
    private function countByfield(): void
    {
        // Demande du champ à utiliser.
        $field = readline(
            "Quel champ voulez-vous utiliser pour le comptage ? (name/email/phone) : "
        );


        // Vérification du champ.
        while (!in_array($field, ['name', 'email', 'phone'], true)) {
            echo "Champ invalide. Utilisez name, email ou phone.\n";

            $field = readline(
                "Quel champ voulez-vous utiliser pour le comptage ? (name/email/phone) : "
            );
        }


        // Valeur recherchée.
        $value = readline("Valeur : ");


        // Tableau utilisé pour stocker le résultat de la recherche.
        $totalContact = [];


        // Recherche selon le champ sélectionné.
        switch ($field) {

            case 'name':
                $totalContact = $this->contactManager->findByName($value);
                break;


            case 'email':
                $totalContact = $this->contactManager->findByEmail($value);
                break;


            case 'phone':
                $totalContact = $this->contactManager->findByPhone($value);
                break;
        }


        // Affichage du nombre de résultats.
        echo "Nombre de contacts correspondant à la valeur '$value' : "
            . count($totalContact)
            . "\n";
    }


    /**
     * Recherche et affiche les valeurs présentes plusieurs fois.
     */
    private function findDuplicates(): void
    {
        // Demande du champ sur lequel rechercher les doublons.
        $field = readline(
            "Quel champ voulez-vous vérifier ? (name/email/phone) : "
        );


        // Vérification du champ.
        while (!in_array($field, ["name", "email", "phone"], true)) {
            echo "Champ invalide. Utilisez name, email ou phone.\n";

            $field = readline(
                "Quel champ voulez-vous vérifier ? (name/email/phone) : "
            );
        }


        // Recherche des doublons selon le champ sélectionné.
        switch ($field) {

            case 'name':
                $duplicates = $this->contactManager->findDuplicateNames();

                if (empty($duplicates)) {
                    echo "Aucun nom en double.\n";
                    return;
                }

                echo "Noms en double :\n";

                foreach ($duplicates as $duplicate) {
                    echo "Nom : "
                        . $duplicate['name']
                        . ", Total : "
                        . $duplicate['total']
                        . "\n";
                }

                break;


            case 'email':
                $duplicates = $this->contactManager->findDuplicateEmails();

                if (empty($duplicates)) {
                    echo "Aucun email en double.\n";
                    return;
                }

                echo "Emails en double :\n";

                foreach ($duplicates as $duplicate) {
                    echo "Email : "
                        . $duplicate['email']
                        . ", Total : "
                        . $duplicate['total']
                        . "\n";
                }

                break;


            case 'phone':
                $duplicates = $this->contactManager->findDuplicatePhoneNumbers();

                if (empty($duplicates)) {
                    echo "Aucun numéro de téléphone en double.\n";
                    return;
                }

                echo "Numéros de téléphone en double :\n";

                foreach ($duplicates as $duplicate) {
                    echo "Téléphone : "
                        . $duplicate['phone_number']
                        . ", Total : "
                        . $duplicate['total']
                        . "\n";
                }

                break;
        }
    }
}
