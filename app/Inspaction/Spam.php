<?php

namespace App\Inspaction;

class Spam {

    protected $inspactions = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    public function detect($body)
    {
        foreach($this->inspactions as $inspaction) {
            app($inspaction)->detect($body);
        }

        return false;
    }

}