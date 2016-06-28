# App SwiftMailer provider

[![Build Status](https://img.shields.io/travis/weew/php-app-swift-mailer.svg)](https://travis-ci.org/weew/php-app-swift-mailer)
[![Code Quality](https://img.shields.io/scrutinizer/g/weew/php-app-swift-mailer.svg)](https://scrutinizer-ci.com/g/weew/php-app-swift-mailer)
[![Test Coverage](https://img.shields.io/coveralls/weew/php-app-swift-mailer.svg)](https://coveralls.io/github/weew/php-app-swift-mailer)
[![Version](https://img.shields.io/packagist/v/weew/php-app-swift-mailer.svg)](https://packagist.org/packages/weew/php-app-swift-mailer)
[![Licence](https://img.shields.io/packagist/l/weew/php-app-swift-mailer.svg)](https://packagist.org/packages/weew/php-app-swift-mailer)


## Table of contents

- [Installation](#installation)
- [Introduction](#introduction)
- [Usage](#usage)
- [Example config](#example-config)

## Installation

`composer require weew/php-app-swift-mailer`

## Introduction

This package integrates the [swiftmailer/swiftmailer](https://github.com/swiftmailer/swiftmailer) library into the [weew/php-app](https://github.com/weew/php-app) package.


## Usage

To make SwiftMailer available inside your application, simply register `SwiftMailerProvider` on the kernel.

```php
$app->getKernel()->addProviders([
    SwiftMailerProvider::class
]);
```

You can retrieve a specific mailer by config name like this:

```php
$swiftMailerManager = $app->getContainer()->get(ISwiftMailerManager::class);

// returns the default mailer
$swiftMailerManager->getMailer();

// returns a mailer using the specific config
$swiftMailerManager->getMailer('config1');
```

## Example config

Currently supported transports are `null`, `sendmail`, `smtp`. This is how your configuration *might* look like:

```yaml
swift_mailer:
  transports:
    # this is the default transport that is used in case no
    # mailer config name has been specified
    default: "{swift_mailer.transports.config2}"

    config1:
      type: smtp
      # server settings
      host: localhost
      port: 25
      security: tls
      # server username and password
      username:
      password:

    config2:
      type: sendmail
      # override for the used sendmail command
      command:
```
