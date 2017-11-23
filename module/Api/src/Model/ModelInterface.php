<?php

namespace Api\Model;

interface ModelInterface
{
    public function exchangeArray(array $data);
    public function toArray();
}
