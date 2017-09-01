<?php namespace Endeavors\DuplicateNameResolver;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Endeavors\DuplicateNameResolver\NameResolver;
use Endeavors\DuplicateNameResolver\Tests\Mocks\SequentialDataSource as SequentialMockDataSource;

class NameResolverTest extends BaseTestCase
{
    public function testSequentialDuplicateNameIncrements()
    {
        $newName = "newname";

        $source = $this->getSequentialDataSource();

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(2)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(5)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(1)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(5)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'newname(1)(1)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(5)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve('newname(1)');

        $this->assertEquals($resolvedName, "newname(1)(1)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'newname(1)(1)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve('newname(1)');

        $this->assertEquals($resolvedName, "newname(1)(2)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'newname(1)(1)',
            'newname(1)(2)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve('newname(1)');

        $this->assertEquals($resolvedName, "newname(1)(3)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'newname(1)(1)',
            'newname(1)(2)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve('newname(2)');

        $this->assertEquals($resolvedName, "newname(2)(1)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'newname(1)(1)',
            'newname(1)(2)',
            'newname(2)(1)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve('newname(2)');

        $this->assertEquals($resolvedName, "newname(2)(2)");

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'newname(1)(1)',
            'newname(1)(2)',
            'newname(2)(1)',
            'newname(2)(2)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve('newname(2)(2)');

        $this->assertEquals($resolvedName, "newname(2)(2)(1)");
    }

    public function testNewNameRemainsTheSame()
    {
        $newName = "stays";

        $source = $this->getSequentialDataSource();

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "stays");
    }

    public function testNewNameWithSuffixRemainsTheSame()
    {
        $newName = "stays(1)";

        $source = $this->getSequentialDataSource();

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "stays(1)");
    }

    public function testSuffixThatIsHigherThanThreeCharacaters()
    {
        $newName = "newname";

        $source = $this->getSequentialDataSource();

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'newname(5)',
            'newname(6)',
            'newname(7)',
            'newname(8)',
            'newname(9)',
            'newname(10)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(11)");
    }

    public function testResolvingSuffixOutOfOrder()
    {
        $newName = "newname";

        $source = $this->getSequentialDataSource();

        $source->setTestData([
            'newname', // newname(1)
            'newname 1',
            'randomename',
            'newname(1)', // newname(2)
            'newname(10)', // skips, aren't equal
            'newname(2)',
            'newname(3)',
            'newname(4)',
            'newname(5)',
            'newname(6)',
            'newname(7)',
            'newname(8)',
            'newname(9)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(11)");
    }

    public function testResolvingOneHundredDuplicates()
    {
        $dups = [];

        for($i = 1; $i <= 100; $i++ ) {
            $dupes[] = 'newname(' . $i . ')';
        }
        
        $dupes[] = 'newname';

        $newName = "newname";

        $source = $this->getSequentialDataSource();

        $source->setTestData($dupes);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(100)(1)");
    }

    public function testResolvingOneHundredDuplicatesWithSecondPrefix()
    {
        $dups = [];

        for($i = 1; $i <= 100; $i++ ) {
            $dupes[] = 'newname(' . $i . ')';
        }

        $dupes[] = 'newname';

        $dupes[] = 'newname(100)(1)';
        $dupes[] = 'newname(100)(2)';
        $dupes[] = 'newname(100)(3)';
        $dupes[] = 'newname(100)(4)';

        $newName = "newname";

        $source = $this->getSequentialDataSource();

        $source->setTestData($dupes);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(100)(5)");
    }

    public function testResolvingOneHundredDuplicatesWithTwoPrefixes()
    {
        $dups = [];

        for($i = 1; $i <= 100; $i++ ) {
            $dupes[] = 'newname(' . $i . ')';
        }
        
        $dupes[] = 'newname';
        
        for($i = 1; $i <= 100; $i++ ) {
            $dupes[] = 'newname(100)(' . $i . ')';
        }

        $newName = "newname";

        $source = $this->getSequentialDataSource();

        $source->setTestData($dupes);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertEquals($resolvedName, "newname(100)(100)(1)");
    }

    protected function getSequentialDataSource()
    {
        return new SequentialMockDataSource();
    }

    protected function getDictionaryDataSource()
    {
        return new SequentialMockDataSource();
    }
}