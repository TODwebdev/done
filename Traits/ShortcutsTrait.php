<?php
namespace Traits;


trait ShortcutsTrait {

    /**
     * Method generates password of required length
     * @param int $half length of pass will be twice this value
     */
    public static function pwdGen($half=3)
    {
        if (!is_int($half) || $half<=0) {
            $half = 3;
        }
        return  bin2hex(openssl_random_pseudo_bytes($half));
    }

}