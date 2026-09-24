<?php

require_once("Contact.php");

class ContactManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $query = $this->pdo->query(
            'SELECT id, name, email, phone_number FROM contact'
        );

        $contacts = [];

        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $contacts[] = new Contact(
                (int) $row['id'],                
                $row['name'],
                $row['email'],
                $row['phone_number']
            );
        }

        return $contacts;
    }

    public function findById(int $id): ?Contact
    {
        $query = $this->pdo->prepare(
            'SELECT id, name, email, phone_number FROM contact WHERE id = :id'
        );
        $query->execute(['id' => $id]);
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

    public function findByName(string $name): array
    {
        $query = $this->pdo->prepare(
            'SELECT id, name, email, phone_number FROM contact WHERE name LIKE :name'
        );
        $query->execute(['name' => '%' . $name . '%']);
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        $contacts = [];
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

    public function findByEmail(string $email): ?Contact
    {
        $query = $this->pdo->prepare(
            'SELECT id, name, email, phone_number FROM contact WHERE email = :email'
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

    public function findByPhone(string $phone): ?Contact
    {
        $query = $this->pdo->prepare(
            'SELECT id, name, email, phone_number FROM contact WHERE phone_number = :phone'
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

    public function create(string $name, string $email, string $phoneNumber): void
    {
        $query = $this->pdo->prepare(
            'INSERT INTO contact (name, email, phone_number) VALUES (:name, :email, :phone_number)'
        );
        $query->execute([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phoneNumber
        ]);
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare(
            'DELETE FROM contact WHERE id = :id'
        );
        $query->execute(['id' => $id]);
    }

    public function update(int $id, Contact $contact): void
    {
        $query = $this->pdo->prepare(
            'UPDATE contact SET name = :name, email = :email, phone_number = :phone_number WHERE id = :id'
        );
        $query->execute([
            'id' => $id,
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'phone_number' => $contact->getPhoneNumber()
        ]);
    }

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

    public function updatePhoneNumber(int $id, string $phoneNumber): void
    {
        $query = $this->pdo->prepare(
            'UPDATE contact SET phone_number = :phone_number WHERE id = :id'
        );
        $query->execute([
            'id' => $id,
            'phone_number' => $phoneNumber
        ]);
    }

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

    public function cloneContact(Contact $contact): ?Contact
    {
        $query = $this->pdo->prepare(
            'INSERT INTO contact (name, email, phone_number) VALUES (:name, :email, :phone_number)'
        );

        $query->execute([
            'name' => $contact->getName(),
            'email' => $contact->getEmail(),
            'phone_number' => $contact->getPhoneNumber()
        ]);

        $newId = (int) $this->pdo->lastInsertId();

        return new Contact(
            $newId,
            $contact->getName(),
            $contact->getEmail(),
            $contact->getPhoneNumber()
        );
    }

    public function count(): int
    {
        $query = $this->pdo->query(
            'SELECT COUNT(*) FROM contact'
        );

        return (int) $query->fetchColumn();
    }

    public function findDuplicateNames(): array
    {
        $query = $this->pdo->query(
            'SELECT name, COUNT(*) as total FROM contact GROUP BY name HAVING count(*) > 1 ORDER BY total DESC'
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findDuplicateEmails(): array
    {
        $query = $this->pdo->query(
            'SELECT email, COUNT(*) as total FROM contact GROUP BY email HAVING count(*) > 1 ORDER BY total DESC'
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findDuplicatePhoneNumbers(): array
    {
        $query = $this->pdo->query(
            'SELECT phone_number, COUNT(*) as total FROM contact GROUP BY phone_number HAVING count(*) > 1 ORDER BY total DESC'
        );

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByName(string $name): int
    {
        $query = $this->pdo->prepare(
            'SELECT COUNT(*) FROM contact WHERE name LIKE :name'
        );
        $query->execute(['name' => '%' . $name . '%']);

        return (int) $query->fetchColumn();
    }

    public function countByEmail(string $email): int
    {
        $query = $this->pdo->prepare(
            'SELECT COUNT(*) FROM contact WHERE email = :email'
        );
        $query->execute(['email' => $email]);

        return (int) $query->fetchColumn();
    }
    
    public function countByPhone(string $phone): int
    {
        $query = $this->pdo->prepare(
            'SELECT COUNT(*) FROM contact WHERE phone_number = :phone'
        );
        $query->execute(['phone' => $phone]);

        return (int) $query->fetchColumn();
    }
}
