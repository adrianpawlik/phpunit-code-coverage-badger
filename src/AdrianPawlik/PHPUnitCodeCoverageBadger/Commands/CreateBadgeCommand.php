<?php

namespace AdrianPawlik\PHPUnitCodeCoverageBadger\Commands;

use AdrianPawlik\PHPUnitCodeCoverageBadger\BadgeTemplateService;
use AdrianPawlik\PHPUnitCodeCoverageBadger\CoverageBadgeService;
use AdrianPawlik\PHPUnitCodeCoverageBadger\CoverageService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateBadgeCommand extends Command
{
    const METRICS = ['elements', 'methods'];
    
    protected function configure()
    {
        $this
            ->setName('badge:coverage:create')
            ->setDescription('Creates a badge from code coverage metrics')
            ->addArgument('clover-filename', InputArgument::REQUIRED)
            ->addArgument('output-dir', InputArgument::REQUIRED)
            ->addOption(
                'low-upper-bound',
                null,
                InputOption::VALUE_OPTIONAL,
                'Maximum coverage percentage to be considered "lowly" covered.',
                35
            )
            ->addOption(
                'high-lower-bound',
                null,
                InputOption::VALUE_OPTIONAL,
                'Minimum coverage percentage to be considered "highly" covered',
                70
            )
            ->addOption(
                'metric',
                null,
                InputOption::VALUE_OPTIONAL,
                'Kind of metrics: elements or methods',
                'methods'
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $metric = $input->getOption('metric');
        
        $this->validateMetrics($metric);
        
        $coverageService = new CoverageService($input->getArgument('clover-filename'));
        $coverageBadgeService = new CoverageBadgeService(new BadgeTemplateService(), $coverageService);
        $coverageBadgeService->setLowUpperBound($input->getOption('low-upper-bound'));
        $coverageBadgeService->setHighLowerBound($input->getOption('high-lower-bound'));
    
        $badge = $this->getBadge($coverageBadgeService, $metric);
        
        $this->saveBadge($input->getArgument('output-dir'), $metric, $badge);
        
        return 0;
    }
    
    private function getBadge(CoverageBadgeService $coverageBadgeService, string $metric): ?string
    {
        switch ($metric) {
            case 'elements':
                return $coverageBadgeService->createElementsCoverageBadge();
                
            case 'methods':
                return $coverageBadgeService->createMethodsCoverageBadge();
        }
        
        return null;
    }
    
    private function saveBadge(string $dir, string $name, string $content)
    {
        file_put_contents($dir . '/' . $name . '.svg', $content);
    }
    
    private function validateMetrics(string $metric)
    {
        if (!in_array($metric, self::METRICS)) {
            throw new InvalidArgumentException(sprintf(
                'Invalid metric. Available metrics: %s',
                join(', ', self::METRICS)
            ));
        }
    }
}
