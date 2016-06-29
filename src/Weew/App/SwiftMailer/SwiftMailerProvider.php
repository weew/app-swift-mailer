<?php

namespace Weew\App\SwiftMailer;

use Swift_Mailer;
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
        
        $container->set([
            SwiftMailer::class, Swift_Mailer::class
        ], function(SwiftMailerManager $swiftMailerManager) {
            return $swiftMailerManager->getMailer();
        });
    }
}
