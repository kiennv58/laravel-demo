<?php

namespace App\Helpers;

class Hashids {

    /**
     * User helper constructor
     */
    public static function getHashids()
    {
        return new \Hashids\Hashids(env('HASHID_SALT', 'xHCxu3aChcc3cQhAOr6h'), env('HASHID_LENGTH', 8), env('HASHID_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'));
    }

    public static function encode()
    {
        $numbers = func_get_args();
        return self::getHashids()->encode($numbers);
    }

    public static function decode($hash)
    {
        return self::getHashids()->decode($hash);
    }
}
