<?php

namespace Api\Model;

class Member implements ModelInterface
{
    public $memberId;
    public $name;
    public $joinDate;
    public $teamId;

    public function exchangeArray(array $data)
    {
        $this->memberId  = !empty($data['memberId'])  ? $data['memberId']  : null;
        $this->name      = !empty($data['name'])      ? $data['name']      : null;
        $this->joinDate  = !empty($data['joinDate'])  ? $data['joinDate']  : null;
        $this->teamId    = !empty($data['teamId'])    ? $data['teamId']    : null;
    }

    public function toArray()
    {
        $data = [
            'memberId' => $this->memberId,
            'name'     => $this->name,
            'joinDate' => $this->joinDate,
        ];

        return $data;
    }
}
