<?php


namespace App\Controller;

use App\Domain\AntiSpam\CaptchaGenerator;
use App\Domain\AntiSpam\CaptchaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CaptchaController extends AbstractController
{
    #[Route('/captcha', name: 'captcha')]
    public function captcha(Request $request, CaptchaGenerator $captchaGenerator): Response
    {
        return $captchaGenerator->generate($request->query->get('captcha_challenge', ''));
    }
}