<?php namespace Endeavors\DuplicateNameResolver\Tests\Mocks;

use Endeavors\DuplicateNameResolver\Contracts\INameDataSource;

class SequentialDataSource implements INameDataSource {
    
    protected $data;

    public function all()
    {
        return $this->getTestData();
    }

    public function isSequential()
    {
        return true;
    }

    public function key()
    {
        return null;
    }

    public function setTestData($data)
    {
        $this->data = $data;
    }

    public function getTestData()
    {
        return $this->data;
    }
}