<?php

namespace Api\Model;

class CompanyTable extends Table
{
    public function get($companyId) // getCompany
    {
        $companyId = (int) $companyId;
        $rowset = $this->tableGateway->select(['companyId' => $companyId]);
        $row = $rowset->current();
        if (! $row) {
            throw new \RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $companyId
            ));
        }

        return $row;
    }

    public function save(array $companyData) // saveCompany(Company $company)
    {
        $data = [
            'name' => $companyData['name'],
        ];

        $companyId = 0;
        if (isset($companyData['companyId'])) {
            $companyId = (int) $companyData['companyId'];
        }

        if ($companyId === 0) {
            return $this->tableGateway->insert($data);
        }

        if (! $this->get($companyId)) {
            throw new \RuntimeException(sprintf(
                'Cannot update company with identifier %d; does not exist',
                $companyId
            ));
        }

        $this->tableGateway->update($data, ['companyId' => $companyId]);

        return $companyId;
    }

    public function delete($companyId) // deleteCompany
    {
        $this->tableGateway->delete(['companyId' => (int) $companyId]);
    }
}
