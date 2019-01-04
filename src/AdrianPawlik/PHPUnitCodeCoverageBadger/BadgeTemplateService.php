<?php

namespace AdrianPawlik\PHPUnitCodeCoverageBadger;

use AdrianPawlik\PHPUnitCodeCoverageBadger\Exceptions\FileNotFoundException;

class BadgeTemplateService
{
    /**
     * @var string
     */
    private $templatesDir;
    
    /**
     * BadgeTemplateService constructor.
     * @param null|string $templatesDir
     * @throws FileNotFoundException
     */
    public function __construct(?string $templatesDir = null)
    {
        $this->templatesDir = $this->resolveDir($templatesDir);
    }
    
    /**
     * @param string $name
     * @return string
     * @throws FileNotFoundException
     */
    public function getTemplateByName(string $name): string
    {
        $filename = sprintf("%s/%s.svg", $this->templatesDir, $name);
        if (!file_exists($filename)) {
            throw new FileNotFoundException(sprintf('File %s not found', $filename));
        }
        
        return file_get_contents($filename);
    }
    
    /**
     * @param null|string $templatesDir
     * @return string
     * @throws FileNotFoundException
     */
    private function resolveDir(?string $templatesDir = null): string
    {
        if (empty($templatesDir)) {
            return __DIR__ . '/templates';
        }
        
        if (!file_exists($templatesDir)) {
            throw new FileNotFoundException(sprintf('Directory %s not found', $templatesDir));
        }
        
        return $templatesDir;
    }
}
