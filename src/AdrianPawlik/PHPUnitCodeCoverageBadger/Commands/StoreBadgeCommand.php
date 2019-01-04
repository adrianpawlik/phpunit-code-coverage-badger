<?php

namespace AdrianPawlik\PHPUnitCodeCoverageBadger\Commands;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StoreBadgeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('badge:store:aws')
            ->setDescription('Uploads badge to Amazon S3 bucket')
            ->addArgument('badge-filename', InputArgument::REQUIRED)
            ->addArgument('aws-key', InputArgument::REQUIRED)
            ->addArgument('aws-secret', InputArgument::REQUIRED)
            ->addArgument('aws-region', InputArgument::REQUIRED)
            ->addArgument('aws-bucket', InputArgument::REQUIRED)
            ->addArgument('aws-bucket-key', InputArgument::REQUIRED);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->uploadFile($input, $output);
    }
    
    private function createS3Client(InputInterface $input): S3Client
    {
        return new S3Client([
            'region' => $input->getArgument('aws-region'),
            'version' => 'latest',
            'credentials' => [
                'key' => $input->getArgument('aws-key'),
                'secret' => $input->getArgument('aws-secret')
            ]
        ]);
    }
    
    private function checkFile(string $filename): bool
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(sprintf('File %s does not exist', $filename));
        }
        
        return true;
    }
    
    private function uploadFile(InputInterface $input, OutputInterface $output): int
    {
        $badgeFilename = $input->getArgument('badge-filename');
    
        $this->checkFile($badgeFilename);
    
        $s3 = $this->createS3Client($input);
    
        try {
            $result = $s3->putObject([
                'Bucket'     => $input->getArgument('aws-bucket'),
                'Key'        => $input->getArgument('aws-bucket-key'),
                'SourceFile' => $badgeFilename,
                'ACL'        => 'public-read'
            ]);
        }
        catch (S3Exception $exception) {
            $output->writeln(
                sprintf(
                    '<error>Cannot upload file: %s</error>',
                    $exception->getAwsErrorMessage()
                )
            );
            
            return 1;
        }
    
        $meta = $result->get('@metadata');
        $output->writeln(
            sprintf(
                '<info>File uploaded successfully. Effective URI: %s</info>',
                $meta['effectiveUri']
            )
        );
        
        return 0;
    }
}
