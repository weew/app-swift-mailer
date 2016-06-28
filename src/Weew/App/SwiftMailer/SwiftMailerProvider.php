<?php

namespace Weew\App\SwiftMailer;

use Weew\Container\IContainer;

class SwiftMailerProvider {
    /**
     * SwiftMailerProvider constructor.
     *
     * @param IContainer $container
     */
    public function __construct(IContainer $container) {
        $container->set(ISwiftMailerConfig::class, SwiftMailerConfig::class);
    }

    /**
     * @param IContainer $container
     */
    public function initialize(IContainer $container) {
        $container->set([
            ISwiftMailerManager::class, SwiftMailerManager::class
        ], SwiftMailerManager::class)->singleton();
    }
}
