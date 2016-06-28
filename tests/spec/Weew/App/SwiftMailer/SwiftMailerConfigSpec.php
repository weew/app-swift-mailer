<?php

namespace tests\spec\Weew\App\SwiftMailer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Weew\App\SwiftMailer\SwiftMailerConfig;
use Weew\Config\Config;

/**
 * @mixin SwiftMailerConfig
 */
class SwiftMailerConfigSpec extends ObjectBehavior {
    private function createConfig() {
        $config = new Config();
        $config->set(SwiftMailerConfig::TRANSPORTS, [
            'default' => [
                'type' => 'null',
            ],
            'null' => [
                'type' => 'null',
            ],
            'sendmail' => [
                'type' => 'sendmail',
                'command' => 'command',
            ],
            'smtp' => [
                'type' => 'smtp',
                'host' => 'host',
                'port' => 'port',
                'username' => 'username',
                'password' => 'password',
            ],
        ]);

        return $config;
    }

    function let() {
        $this->beConstructedWith($this->createConfig());
    }

    function it_is_initializable() {
        $this->shouldHaveType(SwiftMailerConfig::class);
    }

    function it_returns_default_transport_name() {
        $this->getDefaultTransportName()->shouldBe('default');
    }

    function it_returns_transports() {
        $this->getTransports()->shouldBeArray();
    }

    function it_returns_default_transport() {
        $this->getTransport('default')->shouldBe(['type' => 'null']);
    }

    function it_returns_null_transport() {
        $this->getTransport('null')->shouldBe([
            'type' => 'null',
        ]);
    }

    function it_returns_sendmail_transport() {
        $this->getTransport('sendmail')->shouldBe([
            'type' => 'sendmail',
            'command' => 'command',
        ]);
    }

    function it_returns_smtp_transport() {
        $this->getTransport('smtp')->shouldBe([
            'type' => 'smtp',
            'host' => 'host',
            'port' => 'port',
            'username' => 'username',
            'password' => 'password',
        ]);
    }
}
