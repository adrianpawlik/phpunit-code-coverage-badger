<?php

namespace AdrianPawlik\PHPUnitCodeCoverageBadger;

use AdrianPawlik\PHPUnitCodeCoverageBadger\Exceptions\FileNotFoundException;
use AdrianPawlik\PHPUnitCodeCoverageBadger\Exceptions\InvalidXmlException;

class CoverageService
{
    /**
     * @var string
     */
    private $coverageFilename;
    
    /**
     * @var \SimpleXMLElement
     */
    private $coverageXml;
    
    /**
     * @var array
     */
    private $metrics;
    
    /**
     * CoverageService constructor.
     * @param string $coverageFilname
     * @throws FileNotFoundException
     */
    public function __construct(string $coverageFilname)
    {
        $this->checkFile($coverageFilname);
        $this->coverageFilename = $coverageFilname;
    }
    
    /**
     * @return float
     */
    public function getMethodsCoverage(): float
    {
        $this->loadMetrics();
        
        return $this->metrics['methods'] === 0 ?
            0 :
            round(($this->metrics['coveredmethods'] / $this->metrics['methods']) * 100, 2);
    }
    
    /**
     * @return float
     */
    public function getElementsCoverage(): float
    {
        $this->loadMetrics();
        
        return $this->metrics['elements'] === 0 ?
            0 :
            round(($this->metrics['coveredelements'] / $this->metrics['elements']) * 100, 2);
    }
    
    /**
     * @param string $filename
     * @return bool
     * @throws FileNotFoundException
     */
    private function checkFile(string $filename): bool
    {
        if (!file_exists($filename)) {
            throw new FileNotFoundException(sprintf("File %s not found", $filename));
        }
        
        return true;
    }
    
    private function loadMetrics(): void
    {
        if (null !== $this->metrics) {
            return;
        }
        
        $this->loadFile();
        $metrics = $this->coverageXml->xpath('//metrics');
        
        foreach ($metrics as $metric) {
            
            foreach ($metric->attributes() as $name => $value) {
                if (!isset($this->metrics[$name])) {
                    $this->metrics[$name] = 0;
                }

                $this->metrics[$name] += (int)$value;
            }
        }
    }
    
    /**
     * @throws InvalidXmlException
     */
    private function loadFile(): void
    {
        try {
            $this->coverageXml = new \SimpleXMLElement(file_get_contents($this->coverageFilename));
        }
        catch (\Exception $exception) {
            throw new InvalidXmlException('Provided XML file is invalid');
        }
    }
}
