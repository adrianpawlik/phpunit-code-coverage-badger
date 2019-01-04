<?php

namespace AdrianPawlik\PHPUnitCodeCoverageBadger;

class CoverageBadgeService
{
    /**
     * @var BadgeTemplateService
     */
    private $badgeTemplateService;
    
    /**
     * @var CoverageService
     */
    private $coverageService;
    
    /**
     * @var float
     */
    private $lowUpperBound = 35;
    
    /**
     * @var float
     */
    private $highLowerBound = 70;
    
    public function __construct(BadgeTemplateService $badgeTemplateService, CoverageService $coverageService)
    {
        $this->badgeTemplateService = $badgeTemplateService;
        $this->coverageService = $coverageService;
    }
    
    /**
     * @return float
     */
    public function getLowUpperBound(): float
    {
        return $this->lowUpperBound;
    }
    
    /**
     * @param float $lowUpperBound
     */
    public function setLowUpperBound(float $lowUpperBound): void
    {
        $this->lowUpperBound = $lowUpperBound;
    }
    
    /**
     * @return float
     */
    public function getHighLowerBound(): float
    {
        return $this->highLowerBound;
    }
    
    /**
     * @param float $highLowerBound
     */
    public function setHighLowerBound(float $highLowerBound): void
    {
        $this->highLowerBound = $highLowerBound;
    }
    
    public function createMethodsCoverageBadge(): string
    {
        return $this->createBadge(
            $this->badgeTemplateService->getTemplateByName('coverage'),
            $this->coverageService->getMethodsCoverage()
        );
    }
    
    public function createElementsCoverageBadge(): string
    {
        return $this->createBadge(
            $this->badgeTemplateService->getTemplateByName('coverage'),
            $this->coverageService->getElementsCoverage()
        );
    }
    
    private function formatValue(float $value): string
    {
        return ceil($value) . '%';
    }
    
    private function createBadge(string $template, float $value): string
    {
        return str_replace(
            [
               '{{ value }}',
               '{{ coverage-level-class }}'
            ],
            [
                $this->formatValue($value),
                $this->getCoverageBoundColorClass($value)
            ],
            $template
        );
    }
    
    private function getCoverageBoundColorClass(float $value): string
    {
        $class = 'medium';
        
        if($value <= $this->getLowUpperBound()) {
            $class =  'low';
        }
        else if($value >= $this->getHighLowerBound()) {
            $class = 'high';
        }
        
        return $class;
    }
}
