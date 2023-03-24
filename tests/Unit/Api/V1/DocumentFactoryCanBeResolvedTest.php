<?php

namespace Tests\Unit\Api\V1;

use App\Services\Api\V1\DocumentReader\ReadDocument;
use App\Services\Api\V1\DocumentReader\TestTypeFile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentFactoryCanBeResolvedTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testFactoryReturnsClassNameAndReturnsTestType()
    {
        $testFactorySolved = (new ReadDocument)->resolveFactory('test/type');
        $this->assertInstanceOf(TestTypeFile::class, $testFactorySolved);
    }

    public function testFactoryCallToMethodReturnsTrue()
    {
        $testFactorySolved = (new ReadDocument)->resolveFactory('test/type');
        $this->assertTrue($testFactorySolved->readFile('test'));
    }
}
