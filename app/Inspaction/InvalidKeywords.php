<?php
/**
 * Created by PhpStorm.
 * User: monir
 * Date: 2019-02-03
 * Time: 18:22
 */

namespace App\Inspaction;

use Exception;

class InvalidKeywords
{

    protected $keywords = [
        'Yahoo customer support'
    ];


    public function detect($body)
    {
        foreach($this->keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Your reply contains spam.');
            }
        }
    }
}