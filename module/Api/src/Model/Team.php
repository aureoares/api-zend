<?php

namespace Api\Model;

class Team implements ModelInterface
{
    public $teamId;
    public $name;
    public $companyId;

    public function exchangeArray(array $data)
    {
        $this->teamId    = !empty($data['teamId'])    ? $data['teamId']    : null;
        $this->name      = !empty($data['name'])      ? $data['name']      : null;
        $this->companyId = !empty($data['companyId']) ? $data['companyId'] : null;
    }

    public function toArray()
    {
        $data = [
            'teamId'  => $this->teamId,
            'name'    => $this->name,
            'members' => '/companies/'.$this->companyId.'/teams/'.$this->teamId.'/members',
        ];

        return $data;
    }
}
