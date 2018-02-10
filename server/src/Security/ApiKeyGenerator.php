<?php

namespace App\Security;

/**
 * Generates a random, non-reversible hash of set length from a given dictionary set of letters and numbers.
 */
class ApiKeyGenerator
{
    const LENGTH     = 8;
    const DICTIONARY = 'bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ234567890';

    /**
     * Generate and return an api key.
     *
     * @return string
     */
    public static function generate(): string
    {
        $hash = '';

        // create the hash of specified length
        for ($i = 0; $i < self::LENGTH; $i++)
        {
            // get a random character and add it to the hash
            $index = mt_rand(0, strlen(self::DICTIONARY) - 1);

            $hash .= self::DICTIONARY[$index];
        }

        return $hash;
    }
}