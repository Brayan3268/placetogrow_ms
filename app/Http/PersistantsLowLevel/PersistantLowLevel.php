<?php

namespace App\Http\PersistantsLowLevel;

abstract class PersistantLowLevel
{
    abstract public static function forget_cache(string $name_cache);
}
