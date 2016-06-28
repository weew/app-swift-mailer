<?php

namespace tests\spec\Weew\App\SwiftMailer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Weew\App\SwiftMailer\ISwiftMailerConfig;
use Weew\App\SwiftMailer\ISwiftMailerManager;
use Weew\App\SwiftMailer\SwiftMailerManager;
use Weew\App\SwiftMailer\SwiftMailerProvider;
use Weew\Container\Container;
use Weew\Container\IContainer;

/**
 * @mixin SwiftMailerProvider
 */
class SwiftMailerProviderSpec extends ObjectBehavior {
    function it_is_initializable() {
        $this->beConstructedWith(new Container());
        $this->shouldHaveType(SwiftMailerProvider::class);
    }
    
    function it_setups() {
        $container = new Container();
        $this->beConstructedWith($container);
        $this->shouldHaveType(SwiftMailerProvider::class);
        it($container->has(ISwiftMailerConfig::class))->shouldBe(true);
    }

    function it_initializes(IContainer $container) {
        $container = new Container();
        $this->beConstructedWith($container);
        $this->initialize($container);

        it($container->has(ISwiftMailerManager::class))->shouldBe(true);
        it($container->has(SwiftMailerManager::class))->shouldBe(true);
    }
}
