<?php

namespace App\Http\Controllers;

abstract class Controller
{
    abstract protected function write_file(array $info);
    
}
