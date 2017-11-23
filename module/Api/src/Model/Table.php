<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGatewayInterface;

abstract class Table implements ModelTableInterface
{
    protected $tableGateway; // private

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }
}