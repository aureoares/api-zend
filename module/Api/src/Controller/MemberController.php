<?php

namespace Api\Controller;

use Api\Model\Member;
use Api\Model\ModelTableInterface;
use Zend\Stdlib\ResponseInterface;

/**
 * Class MemberController.
 * Controller for Member resource.
 *
 * @package Api\Controller
 */
class MemberController extends RestController
{
    /**
     * @var ModelTableInterface
     */
    protected $teamTable;

    /**
     * Constructor.
     *
     * @param ModelTableInterface $table
     * @param ModelTableInterface $teamTable
     */
    public function __construct(ModelTableInterface $table, ModelTableInterface $teamTable)
    {
        parent::__construct($table);
        $this->teamTable = $teamTable;
    }

    /**
     * Specific GET implementation for list of members.
     *
     * @return ResponseInterface
     */
    protected function getAll()
    {
        $teamId       = (int) $this->params()->fromRoute('teamId', 0);
        $responseData = [];

        /* Get members by team instead of fetching all members. */
        foreach ($this->table->getByTeamId($teamId) as $row) {
            $responseData[] = $row->toArray();
        }

        return $this->successResponse($responseData);
    }

    /**
     * Specific POST implementation for members.
     *
     * @return ResponseInterface
     */
    public function post()
    {
        $request = $this->getRequest();
        $teamId  = (int) $this->params()->fromRoute('teamId', 0);

        $data = json_decode($request->getContent(), true);
        $data['teamId']   = $teamId;
        /* Join date is optional, current date by default. */
        if (!isset($data['joinDate'])) {
            $data['joinDate'] = (new \DateTime())->format('Y-m-d');
        }
        $data['memberId'] = $this->table->save($data);

        $team = new Member();
        $team->exchangeArray($data);

        return $this->successResponse($team->toArray());
    }

    /**
     * Specific PUT implementation for members.
     *
     * @return ResponseInterface
     */
    public function put()
    {
        $request   = $this->getRequest();
        $id        = (int) $this->params()->fromRoute('id', 0);
        $teamId    = (int) $this->params()->fromRoute('teamId', 0);
        $companyId = (int) $this->params()->fromRoute('companyId', 0);

        /* Check that member exists. */
        try {
            $member = $this->table->get($id);
        } catch (\RuntimeException $e) {
            return $this->notFoundResponse();
        }

        /* Check that member is assigned to the team. */
        if ($member->teamId != $teamId) {
            return $this->notFoundResponse();
        }

        /* Check that the team is assigned to the company. */
        $team = $this->teamTable->get($id);
        if ($team->companyId != $companyId) {
            return $this->notFoundResponse();
        }

        $data = json_decode($request->getContent(), true);
        $data['teamId'] = $teamId;
        $data['memberId'] = $id;

        $this->table->save($data);

        $company = new Member();
        $company->exchangeArray($data);

        return $this->successResponse($company->toArray());
    }
}
