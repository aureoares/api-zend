<?php

namespace Api\Controller;

use Api\Model\Company;
use Zend\Stdlib\ResponseInterface;

/**
 * Class CompanyController.
 * Controller for Company resource.
 *
 * @package Api\Controller
 */
class CompanyController extends RestController
{
    /**
     * Specific POST implementation for companies.
     *
     * @return ResponseInterface
     */
    public function post()
    {
        $request = $this->getRequest();

        $data = json_decode($request->getContent(), true);
        $data['companyId'] = $this->table->save($data);
        $company = new Company();
        $company->exchangeArray($data);

        return $this->successResponse($company->toArray());
    }

    /**
     * Specific PUT implementation for companies.
     *
     * @return ResponseInterface
     */
    public function put()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);

        /* Check that the company exists. */
        try {
            $this->table->get($id);
        } catch (\RuntimeException $e) {
            return $this->notFoundResponse();
        }

        $data = json_decode($request->getContent(), true);
        $data['companyId'] = $id;

        $this->table->save($data);

        $company = new Company();
        $company->exchangeArray($data);

        return $this->successResponse($company->toArray());
    }

    /**
     * DELETE method is not supported for companies.
     *
     * @return ResponseInterface
     */
    public function delete()
    {
        return $this->notAllowedResponse();
    }
}
