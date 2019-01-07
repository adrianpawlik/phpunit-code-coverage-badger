# phpunit-code-coverage-badger
Generates a badge showing test coverage of the code.

![Code Coverage](https://ap-code-coverage.s3.eu-west-2.amazonaws.com/elements.svg)

The package provides 2 commands: to create a badge and to store it on Amazon S3 bucket.

### Creating a badge
    phpunit-code-coverage-badger badge:coverage:create tests/clover.xml badges/ --low-upper-bound=80 --high-lower-bound=20 --metric=elements

### Uploading the badge to S3
    phpunit-code-coverage-badger badge:store:aws badges/elements.svg AWS_KEY AWS_SECRET eu-west-2 name_of_the_bucket coverage.svg
