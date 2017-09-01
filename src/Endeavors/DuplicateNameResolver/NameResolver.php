<?php 

namespace Endeavors\DuplicateNameResolver;

use Endeavors\DuplicateNameResolver\Contracts\INameDataSource;

class NameResolver
{
    public function __construct(INameDataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }
    
    /**
     * Resolves the conflicting name
     * Null if nothing to resolve
     * @return string
     */
    public function resolve($name)
    {
        if( $this->originalExists($name) ) {
            return $this->createNewName($name);
        }

        return $name;
    }

    protected function createNewName($name)
    {
        $resolvedName = $name;

        if( $this->suffixExists($resolvedName) ) {
            $resolvedName = $name . $this->firstNameSuffix($name);
        }

        foreach($this->dataSource->all() as $existingName) {
            $resolvedName = $this->makeResolvedName($resolvedName, $existingName);
        }

        return $resolvedName;
    }

    protected function makeResolvedName($new, $old)
    {
        $oldName = $old;

        if( ! $this->dataSource->isSequential() ) {
            $oldName = $old[$this->dataSource->nameIndex()];
        }

        if( $new === $oldName ) {
            $new = $this->appendOrReplaceSuffix($new, $old);
        }

        return $new;
    }

    protected function originalExists($originalName)
    {
        $exists = false;

        foreach($this->dataSource->all() as $existingName) {

            if( $exists ) {
                return $exists;
            }

            $exists = $originalName === $existingName;
        }

        return $exists;
    }

    protected function incrementNameSuffix($str)
    {
        return $this->getNameSuffix($str, true);
    }

    protected function firstNameSuffix($str)
    {
        return $this->getNameSuffix($str, false);
    }

    private function appendOrReplaceSuffix($new, $old)
    {
        $name = $new . $this->getNameSuffix($old);
        
        if( $this->suffixExists($new) ) {
            $name = mb_substr($new,0, strlen($new)-3) . $this->getNameSuffix($old);
        }

        return $name;
    }

    private function getNameSuffix($str, $increment = true)
    {
        $incrementer = 1;
        
        if( $increment ) {
            $incrementer = (int)mb_substr($str,strlen($str)-2, strlen($str)) + 1;
        }

        return '(' . $incrementer . ')';
    }

    private function suffixExists($str)
    {
        $lastThreeCharacters = mb_substr($str,strlen($str)-3, strlen($str));

        if( isset($lastThreeCharacters[0]) && isset($lastThreeCharacters[2]) && $lastThreeCharacters[0] === '(' && $lastThreeCharacters[2] === ')' ) {
            return true;
        }

        return false;
    }
}