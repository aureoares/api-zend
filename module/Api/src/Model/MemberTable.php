<?php

namespace Api\Model;

class MemberTable extends Table
{
    public function getByTeamId($teamId)
    {
        return $this->tableGateway->select(['teamId' => $teamId]);
    }

    public function get($memberId)
    {
        $memberId = (int) $memberId;
        $rowset = $this->tableGateway->select(['memberId' => $memberId]);
        $row = $rowset->current();
        if (! $row) {
            throw new \RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $memberId
            ));
        }

        return $row;
    }

    public function save(array $memberData)
    {
        $data = [
            'name'     => $memberData['name'],
            'joinDate' => $memberData['joinDate'],
            'teamId'   => $memberData['teamId'],
        ];

        $memberId = 0;
        if (isset($memberData['memberId'])) {
            $memberId = (int) $memberData['memberId'];
        }

        if ($memberId === 0) {
            return $this->tableGateway->insert($data);
        }

        if (! $this->get($memberId)) {
            throw new \RuntimeException(sprintf(
                'Cannot update member with identifier %d; does not exist',
                $memberId
            ));
        }

        $this->tableGateway->update($data, ['memberId' => $memberId]);

        return $memberId;
    }

    public function delete($memberId)
    {
        $this->tableGateway->delete(['memberId' => (int) $memberId]);
    }
}
