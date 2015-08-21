<?php
namespace Interfaces;


interface AuthInterface {

    /**
     * Returns user identifier
     * @return string unique user identifier
     */
    public function getUserIdentifier();
}