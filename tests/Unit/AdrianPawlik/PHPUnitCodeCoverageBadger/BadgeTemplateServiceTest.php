<?php

namespace Tests\Unit\AdrianPawlik\PHPUnitCodeCoverageBadger;

use AdrianPawlik\PHPUnitCodeCoverageBadger\BadgeTemplateService;
use AdrianPawlik\PHPUnitCodeCoverageBadger\Exceptions\FileNotFoundException;
use PHPUnit\Framework\TestCase;

class BadgeTemplateServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $mockedTemplatesDir;
    
    public function setUp()
    {
        $this->mockedTemplatesDir = __DIR__ . '/mocks/templates/';
    }
    
    /**
     * @test
     */
    public function shouldCreateForDefaultDir()
    {
        $fixture = new BadgeTemplateService();
        $this->assertInstanceOf(BadgeTemplateService::class, $fixture);
    }
    
    /**
     * @test
     */
    public function shouldCreateForProvidedDir()
    {
        $fixture = new BadgeTemplateService($this->mockedTemplatesDir);
        $this->assertInstanceOf(BadgeTemplateService::class, $fixture);
    }
    
    /**
     * @test
     */
    public function shouldThrowExceptionWhenDirDoesNotExist()
    {
        $this->expectException(FileNotFoundException::class);
        new BadgeTemplateService('non_existing_dir');
    }
    
    /**
     * @test
     */
    public function shouldReturnTemplate()
    {
        $fixture = new BadgeTemplateService($this->mockedTemplatesDir);
        $actual = $fixture->getTemplateByName('coverage');
        $expected = file_get_contents($this->mockedTemplatesDir . 'coverage.svg');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     */
    public function shouldThrowExceptionWhenTemplateDoesNotExist()
    {
        $this->expectException(FileNotFoundException::class);
        $fixture = new BadgeTemplateService($this->mockedTemplatesDir);
        $fixture->getTemplateByName('not_existing_template');
    }
}