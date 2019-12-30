# Differece calculator
## Hexlet PHP project - level 2

### This repository contains Difference Calculator - utility for finding differences in configuration files
----

[![Maintainability](https://api.codeclimate.com/v1/badges/b77754f85f02fb7cda0b/maintainability)](https://codeclimate.com/github/Malcom1986/php-project-lvl2/maintainability) 
[![Test Coverage](https://api.codeclimate.com/v1/badges/b77754f85f02fb7cda0b/test_coverage)](https://codeclimate.com/github/Malcom1986/php-project-lvl2/test_coverage) 
[![Build Status](https://travis-ci.org/Malcom1986/php-project-lvl2.svg?branch=master)](https://travis-ci.org/Malcom1986/php-project-lvl2)

----
### Install:
`$ composer global require malcom/gendiff`
----
### Usage:
```
      gendiff (-h|--help)
      gendiff (-v|--version)
      gendiff [--format <fmt>] <file1> <file2>

    Options:
      -h --help                     Show this screen
      -v --version                  Show version
      --format <fmt>                Report format [default: pretty]
```

**Gendiff can works with different configuration files formats:**
1. JSON format
`$ gendiff before.json after.json`
 
[![asciicast](https://asciinema.org/a/9XUWV7uFG3rfqMKdWPLjK75vg.svg)](https://asciinema.org/a/9XUWV7uFG3rfqMKdWPLjK75vg)

2. YAML format
`$ gendiff before.yaml after.yaml`

[![asciicast](https://asciinema.org/a/zgOT9KgIXPHb81jC0eMNkqF11.svg)](https://asciinema.org/a/zgOT9KgIXPHb81jC0eMNkqF11)

**Gendiff can works with recursive configuration files:**

[![asciicast](https://asciinema.org/a/T4as3zKiypIpCLNjXQgmFhq76.svg)](https://asciinema.org/a/T4as3zKiypIpCLNjXQgmFhq76)


**Gendiff generates output data in different formats:**
1. Pretty format (default)

2. Text format
`gendiff --format plain before.yaml after.yaml`

[![asciicast](https://asciinema.org/a/VqnDwNZRGYI3SEg8V2KaI8mbI.svg)](https://asciinema.org/a/VqnDwNZRGYI3SEg8V2KaI8mbI)

3. JSON format
`gendiff --format json before.yaml after.yaml`

[![asciicast](https://asciinema.org/a/PR2fryK3hr7LMkYrFaGkiHjcv.svg)](https://asciinema.org/a/PR2fryK3hr7LMkYrFaGkiHjcv)

