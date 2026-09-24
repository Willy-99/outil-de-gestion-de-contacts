<?php

require_once('ContactManager.php');

class Command
{
    private ContactManager $contactManager;

    public function __construct(ContactManager $contactManager)
    {
        $this->contactManager = $contactManager;
    }

    public function help(?string $command = null): void
{
    switch ($command) {
        case null:
            echo "Commandes disponibles :\n";
            echo "list          - Liste tous les contacts\n";
            echo "detail        - Affiche un contact\n";
            echo "create        - Crée un contact\n";
            echo "cloneContact  - Clone un contact\n";
            echo "delete        - Supprime un contact\n";
            echo "update        - Modifie un contact\n";
            echo "Compter       - Compte les contacts ou recherche les doublons\n";
            echo "help          - Affiche l'aide\n";
            echo "exit          - Quitte le programme\n";

            echo "\nTapez 'help <commande>' pour plus d'informations.\n";
            break;

        case 'list':
            echo "LIST\n";
            echo "Liste tous les contacts.\n";
            echo "Exemple :\n";
            echo "  list\n";
            break;

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

        case 'create':
            echo "CREATE\n";
            echo "Crée un nouveau contact.\n";
            echo "Syntaxe : create <name> <email> <phone_number>\n";
            echo "Exemple : create Hermione hermione@magie.com 0627643782\n";
            break;

        case "clone contact":
            echo "CLONECONTACT\n";
            echo "Clone un contact existant.\n";
            echo "La recherche du contact est interactive.\n";
            echo "Vous pouvez rechercher le contact par ID, nom, email ou téléphone.\n";
            echo "Vous pourrez ensuite modifier les champs avant de créer le clone.\n";
            echo "Exemple :\n";
            echo "  cloneContact\n";
            echo "  Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : id\n";
            echo "  Valeur : 5\n";
            echo "  Vous souhaitez cloner le contact suivant :\n";
            echo "  5 - Hermione Granger - hermione@magie.com - 0627643782\n";
            echo "  Souhaitez-vous modifier les champs (Y/N) ? N\n";
            echo "  Contact dupliqué avec succès.\n";
            break;

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

        case 'delete':
            echo "DELETE\n";
            echo "Supprime un contact à partir de son ID.\n";
            echo "Syntaxe : delete <id>\n";
            echo "Exemple : delete 6\n";
            break;

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

        case 'help':
            echo "HELP\n";
            echo "Affiche la liste des commandes disponibles ou l'aide détaillée d'une commande.\n";
            echo "Syntaxe :\n";
            echo "  help\n";
            echo "  help <commande>\n";
            echo "Exemple :\n";
            echo "  help cloneContact\n";
            break;

        case 'exit':
            echo "EXIT\n";
            echo "Quitte le programme.\n";
            echo "Exemple :\n";
            echo "  exit\n";
            break;

        default:
            echo "Aucune aide disponible pour la commande '$command'.\n";
            echo "Tapez 'help' pour voir les commandes disponibles.\n";
            break;
    }
}

    public function list(): void
    {
        $contacts = $this->contactManager->findAll();

        foreach ($contacts as $contact) {
            echo $contact . "\n";
        }
    }

    public function detail(): void
    {
        try {
            $field = readline("Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : ");

            while (!in_array($field, ['id', 'name', 'email', 'phone'], true)) {
                echo "Champ invalide. Utilisez id, name, email ou phone.\n";
                $field = readline("Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : ");  
            }

            $value = readline("Valeur : ");

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
            
            if ($contact === null) { 
                echo "Aucun contact trouvé.\n";
                return;}
                
                echo $contact . "\n";
            
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage() . "\n";
        }
    }

    private function searchContact(): ?Contact
{
    $field = readline(
        "Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : "
    );

    while (!in_array($field, ['id', 'name', 'email', 'phone'], true)) {
        echo "Champ invalide. Utilisez id, name, email ou phone.\n";

        $field = readline(
            "Quel champ voulez-vous utiliser pour la recherche ? (id/name/email/phone) : "
        );
    }

    $value = readline("Valeur : ");

    switch ($field) {
        case 'id':
            return $this->contactManager->findById((int) $value);

        case 'email':
            return $this->contactManager->findByEmail($value);

        case 'phone':
            return $this->contactManager->findByPhone($value);

        case 'name':
            $contacts = $this->contactManager->findByName($value);

            if (empty($contacts)) {
                return null;
            }

            if (count($contacts) > 1) {
                echo "Plusieurs contacts correspondent à cette recherche.\n";

                foreach ($contacts as $contact) {
                    echo $contact->getId() . " - " . $contact . "\n";
                }

                return null;
            }

            return $contacts[0];
    }

    return null;
}

    public function cloneContact(): void
    {
        try {
            $contact = $this->searchContact();

            if ($contact === null) {
                echo "Aucun contact selectionné pour le clonage.\n";
                return;
            }
            echo"\nContact sélectionné pour le clonage : " . $contact . "\n";
            $confirm = readline("Voulez-vous vraiment cloner ce contact ? (oui/non) : ");
            
            while (!in_array(strtoupper($confirm), ['OUI', 'NON'], true)) {
                echo "Réponse invalide. Utilisez 'oui' ou 'non'.\n";
                $confirm = readline("Voulez-vous vraiment cloner ce contact ? (oui/non) : ");
            }

            if (strtoupper($confirm) === "OUI") {
            $name = readline("Nouveau nom (Entrée pour conserver) : ");
            $email = readline("Nouvel email (Entrée pour conserver) : ");
            $phone = readline("Nouveau numéro de téléphone (Entrée pour conserver) : ");

            if (!empty($name)) {
                $name = $contact->getName();
            }

            if (!empty($email)) {
                $email = $contact->getEmail();
            }

            if (!empty($phone)) {
                $phone = $contact->getPhoneNumber();
            }
            }else {
                $name = $contact->getName();
                $email = $contact->getEmail();
                $phone = $contact->getPhoneNumber();
            }

            $clone = new Contact(0, $name, $email, $phone);            

            $newContact = $this->contactManager->cloneContact($clone);

            echo "Contact dupliqué avec succès.\n";
            echo "Nouveau contact : " . $newContact . "\n";

        } catch (Exception $e) {
            echo "Erreur lors du clonage du contact : " . $e->getMessage() . "\n";
        }
    }   

    public function create(string $name, string $email, string $phoneNumber): void
    {
        try {
            $this->contactManager->create($name, $email, $phoneNumber);
            echo "Contact créé avec succès.\n";
        } catch (Exception $e) {
            echo "Erreur lors de la création du contact : " . $e->getMessage() . "\n";
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->contactManager->delete($id);
            echo "Contact supprimé avec succès.\n";
        } catch (Exception $e) {
            echo "Erreur lors de la suppression du contact : " . $e->getMessage() . "\n";
        }
    }

    public function update(): void
    {
        try {
            $id = (int) readline("ID du contact : ");
            $field = readline("Quel champ voulez-vous modifier ? (name/email/phone) : ");
            
            while (!in_array($field, ['name', 'email', 'phone'], true)) {
                echo "Champ invalide.\n";
                $field = readline("Quel champ voulez-vous modifier ? (name/email/phone) : ");
                }

            $value = readline("Nouvelle valeur : ");

            switch ($field) {
                case 'name':
                    $this->contactManager->updateName($id, $value);
                    break;
                case 'email':
                    $this->contactManager->updateEmail($id, $value);
                    break;
                case 'phone_number':
                    $this->contactManager->updatePhoneNumber($id, $value);
                    break;
                default:
                    echo "Champ inconnu : $field. Utilisez : name, email ou phone_number\n";
                    return;
            }

            echo "Contact mis à jour avec succès.\n";
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour du contact : " . $e->getMessage() . "\n";
        }
    }
    
    public function count(): void
    {
        try {
            echo "Que voulais vous faire ?\n";
            echo "1 - Compter le nombre de contacts\n";
            echo "2 - Compter les contacts correspondant à une valeur\n";
            echo "3 - Afficher les contacts en doublons\n";

            $choice = readline("Entrez votre choix (1/2/3) : ");

            while (!in_array($choice, ['1', '2', '3'], true)) {
                echo "Choix invalide. Veuillez entrer 1, 2 ou 3.\n";
                $choice = readline("Entrez votre choix (1/2/3) : ");
            }

            switch ($choice) {
                case '1':
                    $total = $this->contactManager->count();

                    echo "Nombre total de contacts : $total\n";
                    break;
                case '2':
                    $this->countByfield();
                    break;
                case '3':
                    $this->findDuplicates();
                    break;
            }

        } catch (Exception $e) {
            echo "Erreur lors du comptage des contacts : " . $e->getMessage() . "\n";
        }
    }

    private function countByfield(): void
    {
        $field = readline("Quel champ voulez-vous utiliser pour le comptage ? (name/email/phone) : ");

        while (!in_array($field, ['name', 'email', 'phone'], true)) {
            echo "Champ invalide. Utilisez name, email ou phone.\n";
            $field = readline("Quel champ voulez-vous utiliser pour le comptage ? (name/email/phone) : ");
        }

        $value = readline("Valeur : ");

        switch ($field) {
            case 'name':
                $totalContact = $this->contactManager->findByName($value);
                //echo "Nombre de contacts avec le nom '$value' : " . count($contacts) . "\n";
                break;
            case 'email':
                $totalContact = $this->contactManager->findByEmail($value);
                //if ($contact === null) {
                //    echo "Aucun contact trouvé avec l'email '$value'.\n";
                //} else {
                //    echo "Nombre de contacts avec l'email '$value' : 1\n";
                //}
                break;
            case 'phone':
                $totalContact = $this->contactManager->findByPhone($value);
                //if ($contact === null) {
                //    echo "Aucun contact trouvé avec le numéro de téléphone '$value'.\n";
                //} else {
                //    echo "Nombre de contacts avec le numéro de téléphone '$value' : 1\n";
                //}
                break;
        }

        echo "Nombre de contacts correspondant à la valeur '$value' : " . count($totalContact) . "\n";
    }

    private function findDuplicates(): void
    {
        $field = readline("Quel champ voulez-vous vérifier ? (name/email/phone) : ");

        while (!in_array($field, ["name","email","phone"], true)) {
            echo "Champ invalide. Utilisez name, email ou phone.\n";
            $field = readline("Quel champ voulez-vous vérifier ? (name/email/phone) : ");
        }

        switch ($field) {
            case 'name':
                $duplicates = $this->contactManager->findDuplicateNames();

                if (empty($duplicates)) {
                echo "Aucun nom en double.\n";
                return;
                }

                echo "Noms en double :\n";
                foreach ($duplicates as $duplicate) {
                    echo "Nom : " . $duplicate['name'] . ", Total : " . $duplicate['total'] . "\n";
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
                    echo "Email : " . $duplicate['email'] . ", Total : " . $duplicate['total'] . "\n";
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
                    echo "Téléphone : " . $duplicate['phone'] . ", Total : " . $duplicate['total'] . "\n";
                }
                break;
        }

        if (empty($duplicates)) {
            echo "Aucun contact en doublon trouvé.\n";
            return;
        }

        echo "Contacts en doublon :\n";
        foreach ($duplicates as $duplicate) {
            echo "Nom : " . $duplicate['name'] . ", Total : " . $duplicate['total'] . "\n";
        }
    }
}
