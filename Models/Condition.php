<?php
/**
 * Created by PhpStorm.
 * User: TOD
 * Date: 20.08.2015
 * Time: 8:19
 */

namespace Models;


class Condition {

    private $leftArg;
    private $cond;
    private $rightArg;

    /**
     * @return mixed
     */
    public function getLeftArg()
    {
        return $this->leftArg;
    }

    /**
     * @param mixed $leftArg
     */
    public function setLeftArg(TypesInterface $leftArg)
    {
        $this->leftArg = $leftArg;
    }

    /**
     * @return mixed
     */
    public function getCond()
    {
        return $this->cond;
    }

    /**
     * @param mixed $cond
     */
    public function setCond($cond)
    {
        $this->cond = $cond;
    }

    /**
     * @return mixed
     */
    public function getRightArg()
    {
        return $this->rightArg;
    }

    /**
     * @param mixed $rightArg
     */
    public function setRightArg(TypesInterface $rightArg)
    {
        $this->rightArg = $rightArg;
    }


}