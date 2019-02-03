<?php
/**
 * Created by PhpStorm.
 * User: monir
 * Date: 2019-02-03
 * Time: 18:27
 */

namespace App\Inspaction;


class KeyHeldDown
{
    public function detect($body)
    {
        if (preg_match("/(.)\\1{4,}/", $body)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}