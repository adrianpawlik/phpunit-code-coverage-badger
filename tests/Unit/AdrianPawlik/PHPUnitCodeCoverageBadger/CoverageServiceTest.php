<?php

namespace Tests\Unit\AdrianPawlik\PHPUnitCodeCoverageBadger;

use AdrianPawlik\PHPUnitCodeCoverageBadger\Exceptions\FileNotFoundException;
use AdrianPawlik\PHPUnitCodeCoverageBadger\Exceptions\InvalidXmlException;
use AdrianPawlik\PHPUnitCodeCoverageBadger\CoverageService;
use PHPUnit\Framework\TestCase;

class CoverageServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $mockedCoverageFile;
    
    /**
     * @var string
     */
    private $mockedBrokenCoverageFile;
    
    /**
     * @var string
     */
    private $mockedZeroCoverageFile;
    
    public function setUp()
    {
        $this->mockedCoverageFile = __DIR__ . '/mocks/clover.xml';
        $this->mockedBrokenCoverageFile = __DIR__ . '/mocks/broken.xml';
        $this->mockedZeroCoverageFile = __DIR__ . '/mocks/clover-zero.xml';
    }
    
    /**
     * @test
     */
    public function shouldCreate()
    {
        $fixture = new CoverageService($this->mockedCoverageFile);
        $this->assertInstanceOf(CoverageService::class, $fixture);
    }
    
    /**
     * @test
     */
    public function shouldThrowExceptionWhenFileDoesNotExist()
    {
        $this->expectException(FileNotFoundException::class);
        new CoverageService('not_existing_file');
    }
    
    /**
     * @test
     */
    public function shouldThrowExceptionWhenFileIsBroken()
    {
        $this->expectException(InvalidXmlException::class);
        $fixture = new CoverageService($this->mockedBrokenCoverageFile);
        $fixture->getMethodsCoverage();
    }
    
    /**
     * @test
     */
    public function shouldReturnMethodsCoverage()
    {
        $fixture = new CoverageService($this->mockedCoverageFile);
        $actual = $fixture->getMethodsCoverage();
        $this->assertEquals(8.57, $actual);
    }
    
    /**
     * @test
     */
    public function shouldReturnElementsCoverage()
    {
        $fixture = new CoverageService($this->mockedCoverageFile);
        $actual = $fixture->getElementsCoverage();
        $this->assertEquals(6.6, $actual);
    }
    
    /**
     * @test
     */
    public function shouldReturnZeroMethodsCoverage()
    {
        $fixture = new CoverageService($this->mockedZeroCoverageFile);
        $actual = $fixture->getMethodsCoverage();
        $this->assertEquals(0, $actual);
    }
    
    /**
     * @test
     */
    public function shouldReturnZeroElementsCoverage()
    {
        $fixture = new CoverageService($this->mockedZeroCoverageFile);
        $actual = $fixture->getElementsCoverage();
        $this->assertEquals(0, $actual);
    }
}