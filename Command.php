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
            echo "list   - Liste tous les contacts\n";
            echo "detail - Affiche un contact\n";
            echo "create - Crée un contact\n";
            echo "delete - Supprime un contact\n";
            echo "update - Modifie un contact\n";
            echo "help   - Affiche l'aide\n";
            echo "exit   - Quitte le programme\n";
            echo "\nTapez 'help <commande>' pour plus d'informations.\n";
            break;

        case 'list':
            echo "LIST\n";
            echo "Liste tous les contacts.\n";
            echo "Exemple : list\n";
            break;

        case 'detail':
            echo "DETAIL\n";
            echo "Affiche les informations d'un contact à partir de son ID.\n";
            echo "Syntaxe : detail <id>\n";
            echo "Exemple : detail 6\n";
            break;

        case 'create':
            echo "CREATE\n";
            echo "Crée un nouveau contact.\n";
            echo "Syntaxe : create <name> <email> <phone_number>\n";
            echo "Exemple : create Hermione hermione@magie.com 0627643782\n";
            break;

        case 'delete':
            echo "DELETE\n";
            echo "Supprime un contact à partir de son ID.\n";
            echo "Syntaxe : delete <id>\n";
            echo "Exemple : delete 6\n";
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

    
}
