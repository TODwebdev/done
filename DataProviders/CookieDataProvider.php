<?php
namespace DataProviders;


use Interfaces\AuthProviderInterface;
use Traits\ShortcutsTrait;

class CookieDataProvider implements AuthProviderInterface
{
    use ShortcutsTrait;

    protected $identifierCookieName;

    /**
     * @return string
     */
    public function getIdentifierCookieName()
    {
        return $this->identifierCookieName;
    }



    public function __construct()
    {
        $this->identifierCookieName = 'uid';
    }

   public function getIdentifier()
   {
        if (!isset($_COOKIE[$this->getIdentifierCookieName()])) {
            $pwd = $this->pwdGen(4);
            $this->setIdentifier($pwd);
        } else {
            $pwd = $_COOKIE[$this->getIdentifierCookieName()];
        }
       return $pwd;
   }

    public function setIdentifier($pwd)
    {
        $monthAhead = strtotime('+30 days');
        setcookie('uid', $pwd, $monthAhead);
    }
}