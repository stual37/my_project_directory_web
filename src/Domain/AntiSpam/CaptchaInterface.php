<?php

namespace App\Domain\AntiSpam;


interface CaptchaInterface {
    /**
     * Undocumented function
     *
     * @return string
     */
    public function generateKey(): string;

    /**
     * Undocumented function
     *
     * @param string $key
     * @return array | null
     */
    public function getSolution(string $key): array | null;

    /**
     * Undocumented function
     *
     * @param string $key
     * @param string $answer
     * @return boolean
     */
    public function verify(string $key, string $answer): bool;
}