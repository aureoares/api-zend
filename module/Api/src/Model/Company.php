<?php

namespace Api\Model;

class Company implements ModelInterface
{
    public $companyId;
    public $name;

    public function exchangeArray(array $data)
    {
        $this->companyId = !empty($data['companyId']) ? $data['companyId'] : null;
        $this->name      = !empty($data['name'])      ? $data['name']      : null;
    }

    public function toArray()
    {
        $data = [
            'companyId' => $this->companyId,
            'name'      => $this->name,
            'teams'     => '/companies/'.$this->companyId.'/teams',
        ];

        return $data;
    }
}
