<?php

namespace Tests\Unit\AdrianPawlik\PHPUnitCodeCoverageBadger;

use AdrianPawlik\PHPUnitCodeCoverageBadger\BadgeTemplateService;
use AdrianPawlik\PHPUnitCodeCoverageBadger\CoverageBadgeService;
use AdrianPawlik\PHPUnitCodeCoverageBadger\CoverageService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CoverageBadgeServiceTest extends TestCase
{
    /**
     * @var string
     */
    private $mockedCoverageFile;
    
    /**
     * @var MockObject
     */
    private $badgeTemplateService;
    
    /**
     * @var CoverageBadgeService
     */
    private $coverageService;
    
    /**
     * @var CoverageBadgeService
     */
    private $fixture;
    
    public function setUp()
    {
        $this->mockedCoverageFile = __DIR__ . '/mocks/clover.xml';
        $this->badgeTemplateService = $this->createMock(BadgeTemplateService::class);
        $this->coverageService = $this->createMock(CoverageService::class);
    
        $this->fixture = new CoverageBadgeService($this->badgeTemplateService, $this->coverageService);
    }
    
    /**
     * @test
     */
    public function shouldCreate()
    {
        $this->assertInstanceOf(CoverageBadgeService::class, $this->fixture);
    }
    
    /**
     * @test
     */
    public function shouldSetLowUpperBound()
    {
        $this->fixture->setLowUpperBound(5);
        $this->assertEquals(5, $this->fixture->getLowUpperBound());
    }
    
    /**
     * @test
     */
    public function shouldSetHighLowerBound()
    {
        $this->fixture->setHighLowerBound(75);
        $this->assertEquals(75, $this->fixture->getHighLowerBound());
    }
    
    /**
     * @test
     * @dataProvider provideCoverageBadge
     */
    public function shouldCreateMethodsCoverageBadge(string $badgeTemplate, int $coverage, string $expected)
    {
        $this->badgeTemplateService
            ->expects($this->once())
            ->method('getTemplateByName')
            ->with('coverage')
            ->willReturn($badgeTemplate);
        
        $this->coverageService
            ->expects($this->once())
            ->method('getMethodsCoverage')
            ->willReturn($coverage);
    
        $actual = $this->fixture->createMethodsCoverageBadge();
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @test
     * @dataProvider provideCoverageBadge
     */
    public function shouldCreateElementsCoverageBadge(string $badgeTemplate, int $coverage, string $expected)
    {
        $this->badgeTemplateService
            ->expects($this->once())
            ->method('getTemplateByName')
            ->with('coverage')
            ->willReturn($badgeTemplate);
        
        $this->coverageService
            ->expects($this->once())
            ->method('getElementsCoverage')
            ->willReturn($coverage);
        
        $actual = $this->fixture->createElementsCoverageBadge();
        $this->assertEquals($expected, $actual);
    }
    
    public function provideCoverageBadge()
    {
        return [
            [
                'Coverage {{ value }}',
                55,
                'Coverage 55%'
            ],

            [
                'Coverage {{ value }}',
                99.77,
                'Coverage 99%'
            ]
        ];
    }
}