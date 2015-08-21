<?php
namespace Services;

use DataProviders\CookieDataProvider;
use Interfaces\AuthInterface;

class AuthService implements AuthInterface{

    public function __construct()
    {

    }

    /**
     * Find identifier under which all records are signed
     */
    public function getUserIdentifier()
    {
        $cookieProvider = new CookieDataProvider();
        $pwd = $cookieProvider->getIdentifier();

        return $pwd;
    }
}