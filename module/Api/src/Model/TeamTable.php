<?php

namespace Api\Model;

class TeamTable extends Table
{
    public function getByCompanyId($companyId)
    {
        return $this->tableGateway->select(['companyId' => $companyId]);
    }

    public function get($teamId)
    {
        $teamId = (int) $teamId;
        $rowset = $this->tableGateway->select(['teamId' => $teamId]);
        $row = $rowset->current();
        if (! $row) {
            throw new \RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $teamId
            ));
        }

        return $row;
    }

    public function save(array $teamData)
    {
        $data = [
            'name'      => $teamData['name'],
            'companyId' => $teamData['companyId'],
        ];

        $teamId = 0;
        if (isset($teamData['teamId'])) {
            $teamId = (int) $teamData['teamId'];
        }

        if ($teamId === 0) {
            return $this->tableGateway->insert($data);
        }

        if (! $this->get($teamId)) {
            throw new \RuntimeException(sprintf(
                'Cannot update team with identifier %d; does not exist',
                $teamId
            ));
        }

        $this->tableGateway->update($data, ['teamId' => $teamId]);

        return $teamId;
    }

    public function delete($teamId)
    {
        $this->tableGateway->delete(['teamId' => (int) $teamId]);
    }
}
