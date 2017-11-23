<?php

namespace Api\Controller;

use Api\ApiResponse;
use Api\Model\ModelTableInterface;
use Api\Model\Team;
use Zend\Stdlib\ResponseInterface;

/**
 * Class TeamController.
 * Controller for Team resource.
 *
 * @package Api\Controller
 */
class TeamController extends RestController
{
    /**
     * @var ModelTableInterface
     */
    protected $memberTable;

    /**
     * Constructor.
     *
     * @param ModelTableInterface $table
     * @param ModelTableInterface $memberTable
     */
    public function __construct(ModelTableInterface $table, ModelTableInterface $memberTable)
    {
        parent::__construct($table);
        $this->memberTable = $memberTable;
    }

    /**
     * Specific GET implementation for list of teams.
     *
     * @return ResponseInterface
     */
    protected function getAll()
    {
        $companyId    = (int) $this->params()->fromRoute('companyId', 0);
        $responseData = [];

        /* Get teams by company instead of fetching all teams. */
        foreach ($this->table->getByCompanyId($companyId) as $row) {
            $responseData[] = $row->toArray();
        }

        return $this->successResponse($responseData);
    }

    /**
     * Specific POST implementation for teams.
     *
     * @return ResponseInterface
     */
    public function post()
    {
        $request = $this->getRequest();
        $companyId = (int) $this->params()->fromRoute('companyId', 0);

        $data = json_decode($request->getContent(), true);
        $data['companyId'] = $companyId;
        $data['teamId']    = $this->table->save($data);

        $team = new Team();
        $team->exchangeArray($data);

        return $this->successResponse($team->toArray());
    }

    /**
     * Specific PUT implementation for teams.
     *
     * @return ResponseInterface
     */
    public function put()
    {
        $request   = $this->getRequest();
        $id        = (int) $this->params()->fromRoute('id', 0);
        $companyId = (int) $this->params()->fromRoute('companyId', 0);

        /* Check that the team exists. */
        try {
            $team = $this->table->get($id);
        } catch (\RuntimeException $e) {
            return $this->notFoundResponse();
        }

        /* Check that the team is assigned to the company. */
        if ($team->companyId != $companyId) {
            return $this->notFoundResponse();
        }

        $data = json_decode($request->getContent(), true);
        $data['companyId'] = $companyId;
        $data['teamId'] = $id;

        $this->table->save($data);

        $company = new Team();
        $company->exchangeArray($data);

        return $this->successResponse($company->toArray());
    }

    /**
     * Specific DELETE implementation for teams.
     *
     * @return ResponseInterface
     */
    public function delete()
    {
        /* Check that the team is empty before deleting. */
        $id = (int) $this->params()->fromRoute('id', 0);
        $members = $this->memberTable->getByTeamId($id);
        if (0 !== $members->count()) {
            return $this->deleteNotEmptyResponse();
        }

        return parent::delete();
    }

    /**
     * Build "not empty" response.
     *
     * @return ResponseInterface
     */
    protected function deleteNotEmptyResponse()
    {
        $apiResponse = new ApiResponse();
        $apiResponse->success = false;
        $apiResponse->error = 'Team is not empty';

        return $this->buildResponse($apiResponse, 405);
    }
}
