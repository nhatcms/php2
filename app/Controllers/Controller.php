<?php

namespace App\Controllers;

use eftec\bladeone\BladeOne;

class Controller
{
    protected $blade;
    public function __construct()
    {
        $views = ROOT_PATH . '/views';
        $cache = ROOT_PATH . '/cache';

        $this->blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
    }
}
