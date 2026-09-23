<?php

class Contact
{
    private int $id;
    private string $name;
    private string $email;
    private string $phoneNumber;

    public function __construct(int $id, string $name, string $email, string $phoneNumber)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function __toString(): string
    { 
        return $this->name . ' , ' . $this->email . ' , ' . $this->phoneNumber;
    }
}