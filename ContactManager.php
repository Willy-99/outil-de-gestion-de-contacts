<?php

// La classe ContactManager utilise la classe Contact.
require_once("Contact.php");


class ContactManager
{
    // Connexion PDO utilisée pour toutes les requêtes SQL.
    private PDO $pdo;


    // La connexion PDO est injectée dans le constructeur.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * Récupère tous les contacts.
     *
     * Retourne un tableau d'objets Contact.
     */
    public function findAll(): array
    {
        // Exécution d'une requête SQL simple car aucun paramètre utilisateur
        // n'est utilisé dans cette requête.
        $query = $this->pdo->query(
            'SELECT id, name, email, phone_number FROM contact'
        );


        $contacts = [];


        // fetchAll() retourne toutes les lignes sous forme de tableaux associatifs.
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {

            // Transformation de chaque ligne SQL en objet Contact.
            $contacts[] = new Contact(
                (int) $row['id'],
                $row['name'],
                $row['email'],
                $row['phone_number']
            );
        }


        return $contacts;
    }


    /**
     * Recherche un contact par son ID.
     *
     * Retourne un Contact ou null si aucun résultat n'est trouvé.
     */
    public function findById(int $id): ?Contact
    {
        // prepare() permet d'utiliser un paramètre sécurisé.
        $query = $this->pdo->prepare(
            'SELECT id, name, email, phone_number
             FROM contact
             WHERE id = :id'
        );

        $query->execute(['id' => $id]);


        // fetch() retourne une seule ligne ou false.
        $contact = $query->fetch(PDO::FETCH_ASSOC);


        if ($contact) {
            return new Contact(
                (int) $contact['id'],
                $contact['name'],
                $contact['email'],
                $contact['phone_number']
            );
        }


        return null;
    }


    /**
     * Recherche les contacts dont le nom contient la valeur recherchée.
     *
     * Plusieurs contacts peuvent être retournés.
     */
    public function findByName(string $name): array
    {
        $query = $this->pdo->prepare(
            'SELECT id, name, email, phone_number
             FROM contact
             WHERE name LIKE :name'
        );

        // Les % permettent de rechercher la valeur n'importe où dans le nom.
        $query->execute([
            'name' => '%' . $name . '%'
        ]);


        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $contacts = [];


        // Transformation des lignes SQL en objets Contact.
        foreach ($rows as $row) {
            $contacts[] = new Contact(
                (int) $row['id'],
                $row['name'],
                $row['email'],
                $row['phone_number']
            );
        }


        return $contacts;
    }


    /**
     * Recherche un contact par son email.
     */
    public function findByEmail(string $email): ?Contact
    {
        $query = $this->pdo->prepare(
            'SELECT id, name, email, phone_number
             FROM contact
             WHERE email = :email'
        );

        $query->execute(['email' => $email]);


        $contact = $query->fetch(PDO::FETCH_ASSOC);


        if ($contact) {
            return new Contact(
                (int) $contact['id'],
                $contact['name'],
                $contact['email'],
                $contact['phone_number']
            );
        }


        return null;
    }


    /**
     * Recherche un contact par son numéro de téléphone.
     */
    public function findByPhone(string $phone): ?Contact
    {
        $query = $this->pdo->prepare(
            'SELECT id, name, email, phone_number
             FROM contact
             WHERE phone_number = :phone'
        );

        $query->execute(['phone' => $phone]);


        $contact = $query->fetch(PDO::FETCH_ASSOC);


        if ($contact) {
            return new Contact(
                (int) $contact['id'],
                $contact['name'],
                $contact['email'],
                $contact['phone_number']
            );
        }


        return null;
    }


    /**
     * Crée un nouveau contact dans la base de données.
     */
    public function create(
        string $name,
        string $email,
        string $phoneNumber
    ): void
    {
        // prepare() permet d'utiliser des paramètres dans la requête SQL.
        $query = $this->pdo->prepare(
            'INSERT INTO contact (name, email, phone_number)
             VALUES (:name, :email, :phone_number)'
        );


        // Les valeurs sont transmises séparément de la requête SQL.
        $query->execute([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber
        ]);
    }


    /**
     * Supprime un contact à partir de son ID.
     */
    public function delete(int $id): void
    {
        $query = $this->pdo->prepare(
            'DELETE FROM contact WHERE id = :id'
        );

        $query->execute(['id' => $id]);
    }


    /**
     * Met à jour l'ensemble des informations d'un contact.
     */
    public function update(int $id, Contact $contact): void
    {
        $query = $this->pdo->prepare(
            'UPDATE contact
             SET name = :name,
                 email = :email,
                 phone_number = :phone_number
             WHERE id = :id'
        );


        $query->execute([
            'id' => $id,
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'phone_number' => $contact->getPhoneNumber()
        ]);
    }


    /**
     * Modifie uniquement l'email d'un contact.
     */
    public function updateEmail(int $id, string $email): void
    {
        $query = $this->pdo->prepare(
            'UPDATE contact SET email = :email WHERE id = :id'
        );

        $query->execute([
            'id' => $id,
            'email' => $email
        ]);
    }


    /**
     * Modifie uniquement le numéro de téléphone d'un contact.
     */
    public function updatePhoneNumber(int $id, string $phoneNumber): void
    {
        $query = $this->pdo->prepare(
            'UPDATE contact
             SET phone_number = :phone_number
             WHERE id = :id'
        );

        $query->execute([
            'id' => $id,
            'phone_number' => $phoneNumber
        ]);
    }


    /**
     * Modifie uniquement le nom d'un contact.
     */
    public function updateName(int $id, string $name): void
    {
        $query = $this->pdo->prepare(
            'UPDATE contact SET name = :name WHERE id = :id'
        );

        $query->execute([
            'id' => $id,
            'name' => $name
        ]);
    }


    /**
     * Clone un contact en créant une nouvelle entrée dans la BDD.
     *
     * Le contact fourni ne contient pas nécessairement son véritable ID :
     * l'ID est généré automatiquement par MySQL.
     *
     * La méthode retourne ensuite un nouvel objet Contact contenant
     * le nouvel ID généré.
     */
    public function cloneContact(Contact $contact): ?Contact
    {
        $query = $this->pdo->prepare(
            'INSERT INTO contact (name, email, phone_number)
             VALUES (:name, :email, :phone_number)'
        );


        $query->execute([
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'phone_number' => $contact->getPhoneNumber()
        ]);


        // Récupération de l'ID généré automatiquement par MySQL.
        $newId = (int) $this->pdo->lastInsertId();


        // Création d'un nouvel objet Contact avec le nouvel ID.
        return new Contact(
            $newId,
            $contact->getName(),
            $contact->getEmail(),
            $contact->getPhoneNumber()
        );
    }


    /**
     * Compte le nombre total de contacts.
     */
    public function count(): int
    {
        $query = $this->pdo->query(
            'SELECT COUNT(*) FROM contact'
        );


        // fetchColumn() récupère directement la première colonne du résultat.
        return (int) $query->fetchColumn();
    }


    /**
     * Recherche les noms présents plusieurs fois.
     */
    public function findDuplicateNames(): array
    {
        // GROUP BY regroupe les contacts ayant le même nom.
        // COUNT(*) compte le nombre d'occurrences.
        // HAVING conserve uniquement les groupes présents plus d'une fois.
        $query = $this->pdo->query(
            'SELECT name, COUNT(*) AS total
             FROM contact
             GROUP BY name
             HAVING COUNT(*) > 1
             ORDER BY total DESC'
        );


        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Recherche les emails présents plusieurs fois.
     */
    public function findDuplicateEmails(): array
    {
        $query = $this->pdo->query(
            'SELECT email, COUNT(*) AS total
             FROM contact
             GROUP BY email
             HAVING COUNT(*) > 1
             ORDER BY total DESC'
        );


        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Recherche les numéros de téléphone présents plusieurs fois.
     */
    public function findDuplicatePhoneNumbers(): array
    {
        $query = $this->pdo->query(
            'SELECT phone_number, COUNT(*) AS total
             FROM contact
             GROUP BY phone_number
             HAVING COUNT(*) > 1
             ORDER BY total DESC'
        );


        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Compte les contacts dont le nom contient la valeur recherchée.
     */
    public function countByName(string $name): int
    {
        $query = $this->pdo->prepare(
            'SELECT COUNT(*) FROM contact WHERE name LIKE :name'
        );

        $query->execute([
            'name' => '%' . $name . '%'
        ]);


        return (int) $query->fetchColumn();
    }


    /**
     * Compte les contacts correspondant exactement à un email.
     */
    public function countByEmail(string $email): int
    {
        $query = $this->pdo->prepare(
            'SELECT COUNT(*) FROM contact WHERE email = :email'
        );

        $query->execute(['email' => $email]);


        return (int) $query->fetchColumn();
    }


    /**
     * Compte les contacts correspondant exactement à un numéro.
     */
    public function countByPhone(string $phone): int
    {
        $query = $this->pdo->prepare(
            'SELECT COUNT(*) FROM contact WHERE phone_number = :phone'
        );

        $query->execute(['phone' => $phone]);


        return (int) $query->fetchColumn();
    }
}
