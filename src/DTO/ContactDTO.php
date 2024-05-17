<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class ContactDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 200)]
    public string $name = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(min: 5, max: 200)]
    public string $email = '';

    #[Assert\NotBLank]
    #[Assert\Length(min: 5, max: 200)]
    public string $subject = '';

    #[Assert\NotBLank]
    #[Assert\Length(min: 15, max: 500)]
    public string $message = '';

    #[Assert\NotBLank]
    public string $service = '';
}