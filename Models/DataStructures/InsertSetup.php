<?php

namespace Models\DataStructures;


class InsertSetup {

    protected $insert = [];

    /**
     * @return array
     */
    public function getInsert()
    {
        return $this->insert;
    }

    /**
     * Add another field to insert array
     */
    public function addField(array $keyValArr)
    {
        $this->insert[] = $keyValArr;
    }
}