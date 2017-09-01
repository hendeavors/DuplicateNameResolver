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

    public function testDictionaryDuplicateNameResolves()
    {
        $newName = "newname";

        $source = $this->getDictionaryDataSource();

        $source->setTestData([
            'newname',
            'newname 1',
            'randomename',
            'newname(1)',
            'test'
        ]);

        $nameResolver = new NameResolver($source);

        $resolvedName = $nameResolver->resolve($newName);

        $this->assertNotEquals($resolvedName, $newName);
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