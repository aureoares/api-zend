<?php

namespace Api\Model;

interface ModelTableInterface
{
    public function fetchAll();
    public function get($id);
    public function save(array $data);
    public function delete($id);
}
