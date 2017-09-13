<?php 

namespace Endeavors\DuplicateNameResolver;

use Endeavors\DuplicateNameResolver\Contracts\INameDataSource;

/**
 * @todo refactor
 */
class NameResolver
{
    protected $suffixNumbers = [];

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
        $dataSourceElements = $this->dataSource->all();

        if( $this->originalExists($name, $dataSourceElements) ) {
            return $this->createNewName($name, $dataSourceElements);
        }

        return $name;
    }

    protected function createNewName($name, $dataSourceElements)
    {
        $resolvedName = $name;

        if( $this->suffixExists($resolvedName) ) {
            $resolvedName = $name . $this->firstNameSuffix($name);
        }

        foreach($dataSourceElements as $existingName) {
            $resolvedName = $this->makeResolvedName($resolvedName, $existingName, $dataSourceElements);
        }

        return $resolvedName;
    }

    protected function makeResolvedName($new, $old, $dataSourceElements)
    {
        $oldName = $old;

        if( ! $this->dataSource->isSequential() ) {
            $oldName = $old[$this->dataSource->key()];
        }

        if( in_array($new, $dataSourceElements) ) {
            $new = $this->appendOrReplaceSuffix($new, $old);
        }

        return $new;
    }

    protected function originalExists($originalName, $dataSourceElements)
    {
        $exists = false;

        foreach($dataSourceElements as $existingName) {

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
            $name = mb_substr($new,0, $this->getPositionOfOpeningSuffix($new)) . $this->getNameSuffix($old);
        }

        return $name;
    }

    private function getNameSuffix($str, $increment = true)
    {
        $incrementer = 1;
        
        if( $increment ) {
            $num = (int)mb_substr($str, $this->getPositionAfterOpeningSuffix($str), strlen($str));

            $this->setSuffixNumbers($num);

            $incrementer = $this->getNextIncrementer($num, $incrementer);
        }

        return '(' . $incrementer . ')';
    }

    private function suffixExists($str)
    {
        $openPosition = $this->getPositionOfOpeningSuffix($str);
        
        $suffix = mb_substr($str, $openPosition, strlen($str));

        $lastSubstrPosition = strlen($suffix) - 1;

        if( isset($suffix[0]) && isset($suffix[$lastSubstrPosition]) 
            && $suffix[0] === '(' && $suffix[$lastSubstrPosition] === ')' 
        ) {
            return true;
        }

        return false;
    }

    private function getPositionAfterOpeningSuffix($str)
    {
        $pos = strlen($str)-2;

        if( isset($str[strlen($str)-3]) && is_numeric($str[strlen($str)-3]) )
            $pos = strlen($str)-3;

        return $pos;
    }

    private function getPositionOfOpeningSuffix($str)
    {
        return $this->getPositionAfterOpeningSuffix($str) - 1;
    }

    private function setSuffixNumbers($suffixNumber)
    {
        $this->suffixNumbers[] = $suffixNumber;
    }

    private function getSuffixNumbers()
    {
        return $this->suffixNumbers;
    }

    private function getNextIncrementer($num, $incrementer)
    {
        if ($this->isDifferenceGreaterThanOne() ) {
            $incrementer = $this->getPreviousSuffixNumber() + 1;
        } else {
            $incrementer = $num + 1;
        }

        return $incrementer;   
    }

    private function isDifferenceGreaterThanOne()
    {
        return $this->getDifferenceBetweenPreviousAndLastSuffixNumber() > 1;
    }

    private function getDifferenceBetweenPreviousAndLastSuffixNumber()
    {
        if( $this->getLastSuffixNumber() > $this->getPreviousSuffixNumber() ) {
            return $this->getLastSuffixNumber() - $this->getPreviousSuffixNumber();
        }

        return 0;
    }

    private function getPreviousSuffixNumber()
    {
        if( count($this->suffixNumbers) > 1 ) {
            return $this->suffixNumbers[count($this->suffixNumbers)-2];
        }

        return 0;
    }

    private function getLastSuffixNumber()
    {
        if( count($this->suffixNumbers) > 0 ) {
            return $this->suffixNumbers[count($this->suffixNumbers)-1];
        }

        return 0;
    }
}