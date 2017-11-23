<?php

namespace Api\Controller;

use Api\ApiResponse;
use Api\Model\ModelTableInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\ResponseInterface;

/**
 * Class RestController.
 * Base controller for our REST API.
 *
 * @package Api\Controller
 */
abstract class RestController extends AbstractActionController // AbstractRestfulController
{
    /**
     * Table handler for the resource attached to this controller.
     *
     * @var ModelTableInterface
     */
    protected $table; // private

    /**
     * POST implementation will depend on the specific resource.
     *
     * @return ResponseInterface
     */
    public abstract function post();

    /**
     * POST implementation will depend on the specific resource.
     *
     * @return ResponseInterface
     */
    public abstract function put();

    /**
     * Constructor.
     *
     * @param ModelTableInterface $table
     */
    public function __construct(ModelTableInterface $table)
    {
        $this->table = $table;
    }

    /**
     * Main action, "redirects" to the method corresponding to the request.
     *
     * @return mixed
     */
    public function indexAction()
    {
        $action = strtolower($this->getRequest()->getMethod());

        return $this->{$action}();
    }

    /**
     * Basic GET implementation.
     *
     * @return ResponseInterface
     */
    public function get()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            $response = $this->getAll();
        } else {
            $response = $this->getSingle($id);
        }

        return $response;
    }

    /**
     * Basic GET implementation for single resource.
     *
     * @param int $id Resource ID.
     *
     * @return ResponseInterface
     */
    protected function getSingle($id)
    {
        try {
            $responseData = $this->table->get($id)->toArray();
        } catch (\RuntimeException $e) {
            return $this->notFoundResponse();
        }

        return $this->successResponse($responseData);
    }

    /**
     * Basic GET implementation for list of resources.
     *
     * @return ResponseInterface
     */
    protected function getAll()
    {
        $responseData = [];
        foreach ($this->table->fetchAll() as $row) {
            $responseData[] = $row->toArray();
        }

        return $this->successResponse($responseData);
    }

    /**
     * Basic DELETE method implementation.
     *
     * @return ResponseInterface
     */
    public function delete()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        try {
            $this->table->get($id);
        } catch (\RuntimeException $e) {
            return $this->notFoundResponse();
        }

        $this->table->delete($id);

        return $this->successResponse();
    }

    /**
     * PATCH method is not supported by our API.
     *
     * @return ResponseInterface
     */
    public function patch()
    {
        return $this->notAllowedResponse();
    }

    /**
     * HEAD method is not supported by our API.
     *
     * @return ResponseInterface
     */
    public function head()
    {
        return $this->notAllowedResponse();
    }

    /**
     * OPTIONS method is not supported by our API.
     *
     * @return ResponseInterface
     */
    public function options()
    {
        return $this->notAllowedResponse();
    }

    /**
     * Build action response.
     *
     * @param ApiResponse $content  Body.
     * @param int         $httpCode HTTP status code.
     *
     * @return ResponseInterface
     */
    protected function buildResponse($content, $httpCode = 200)
    {
        $response = $this->getResponse();
        $response->setStatusCode($httpCode);
        $response->setContent((string)$content);

        return $response;
    }

    /**
     * Build success response.
     *
     * @param mixed $data
     *
     * @return ResponseInterface
     */
    protected function successResponse($data = null)
    {
        $apiResponse = new ApiResponse();
        $apiResponse->success = true;
        $apiResponse->data = $data;

        return $this->buildResponse($apiResponse, 200);
    }

    /**
     * Build "not allowed" error response.
     *
     * @return ResponseInterface
     */
    protected function notAllowedResponse()
    {
        $apiResponse = new ApiResponse();
        $apiResponse->success = false;
        $apiResponse->error = 'Method not supported';

        return $this->buildResponse($apiResponse, 501);
    }

    /**
     * Build "not found" error response.
     *
     * @return ResponseInterface
     */
    protected function notFoundResponse()
    {
        $apiResponse = new ApiResponse();
        $apiResponse->success = false;
        $apiResponse->error = 'Not found';

        return $this->buildResponse($apiResponse, 404);
    }
}
