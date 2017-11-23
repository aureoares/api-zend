<?php

namespace Api;

/**
 * Class ApiResponse.
 * Represents the body of a response to be returned by the API.
 *
 * @package Api
 */
class ApiResponse
{
    public $success = true;
    public $error = null;
    public $data = null;

    /**
     * Our API will use JSON format, so the string representation of this class will be a JSON string.
     *
     * @return string Return JSON representation.
     */
    public function __toString()
    {
        $dataArray = [];
        $dataArray['success'] = $this->success;
        if (null !== $this->error) {
            $dataArray['error'] = (string)$this->error;
        }
        if (null !== $this->data) {
            $dataArray['data'] = $this->data;
        }

        return json_encode($dataArray);
    }
}
