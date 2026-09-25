<?php

// Classe représentant un contact.
// Elle permet de stocker les données d'un contact
// sous la forme d'un objet PHP.
class Contact
{
    // Propriétés contenant les données du contact.
    // Elles correspondent aux colonnes de la table "contact"
    // dans la base de données.
    private int $id;
    private string $name;
    private string $email;
    private string $phoneNumber;

    // Constructeur permettant de créer un objet Contact
    // avec les données récupérées ou fournies.
    public function __construct(
        int $id,
        string $name,
        string $email,
        string $phoneNumber
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    // Retourne l'identifiant du contact.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Retourne le nom du contact.
    public function getName(): string
    {
        return $this->name;
    }

    // Retourne l'adresse email du contact.
    public function getEmail(): string
    {
        return $this->email;
    }

    // Retourne le numéro de téléphone du contact.
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    // Méthode magique appelée automatiquement lorsqu'on utilise
    // l'objet Contact dans un contexte où PHP attend une chaîne.
    //
    // Exemple :
    // echo $contact;
    //
    // PHP utilisera automatiquement cette méthode.
    public function __toString(): string
    {
        return $this->name . ' , ' . $this->email . ' , ' . $this->phoneNumber;
    }
}