<?php

namespace App\Validator;

use App\Domain\AntiSpam\CaptchaInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CaptchaValidator extends ConstraintValidator
{

    public function __construct(private readonly CaptchaInterface $captcha)
    {
        # code...
    }


    /**
     * Permet de valider la réponse de l'utilisateur
     *
     * @param array{captcha: string, answer: string}     $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var Captcha $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        //dd($value);
        if(!$this->captcha->verify($value['captcha_challenge'], $value['answer'] ?? '')) {
            $this->context->buildViolation($constraint->message)
            ->addViolation();
        }
        
    }
}
