<?php

namespace App\Domain\AntiSpam;

use Symfony\Component\HttpFoundation\Response;

interface CaptchaGenerator {
    /**
     * Undocumented function
     *
     * @param string $key
     * @return Response
     */
    public function generate(string $key): Response;

}