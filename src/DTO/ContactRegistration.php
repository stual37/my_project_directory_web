<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class ContactRegistration
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 200)]
    public string $username = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(min: 5, max: 200)]
    public string $email = '';

    #[Assert\NotBLank]
    #[Assert\Length(min: 5, max: 200)]
    public string $subject = 'Problème pour s\'enregistrer';

    #[Assert\NotBLank]
    #[Assert\Length(min: 15, max: 500)]
    public string $message = 'Bonjour, <br/> Pouvez-vous effectuer mon inscription ne pouvant pas le faire moi-même? \n\r Bien à vous';

}