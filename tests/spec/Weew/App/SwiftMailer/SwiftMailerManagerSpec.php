<?php

namespace tests\spec\Weew\App\SwiftMailer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Swift_NullTransport;
use Swift_SendmailTransport;
use Swift_SmtpTransport;
use Weew\App\SwiftMailer\Exceptions\MailerTransportConfigNotFoundException;
use Weew\App\SwiftMailer\Exceptions\MailerTransportTypeIsNotSupported;
use Weew\App\SwiftMailer\SwiftMailer;
use Weew\App\SwiftMailer\SwiftMailerConfig;
use Weew\App\SwiftMailer\SwiftMailerManager;
use Weew\Config\Config;

/**
 * @mixin SwiftMailerManager
 */
class SwiftMailerManagerSpec extends ObjectBehavior {
    function let() {
        $config = new Config();
        $config->set(SwiftMailerConfig::TRANSPORTS, [
            'default' => [
                'type' => 'null',
            ],
            'sendmail' => [
                'type' => 'sendmail',
                'command' => 'command',
            ],
            'smtp' => [
                'type' => 'smtp',
                'host' => 'localhost',
                'port' => 25,
                'username' => 'username',
                'password' => 'password',
            ],
            'not_supported' => [
                'type' => 'not_supported',
            ],
        ]);
        $swiftMailerConfig = new SwiftMailerConfig($config);

        $this->beConstructedWith($swiftMailerConfig);
    }

    function it_is_initializable() {
        $this->shouldHaveType(SwiftMailerManager::class);
    }

    function it_returns_default_mailer_instance() {
        $mailer = $this->getMailer();
        $mailer->shouldHaveType(SwiftMailer::class);
        $mailer->getTransport()->shouldHaveType(Swift_NullTransport::class);
    }

    function it_caches_mailer_instances() {
        $mailer1 = $this->getMailer();
        $mailer2 = $this->getMailer();
        $mailer1->shouldBe($mailer2->getWrappedObject());
    }

    function it_returns_sendmail_mailer_instance() {
        $mailer = $this->getMailer('sendmail');
        $mailer->shouldHaveType(SwiftMailer::class);
        /** @var Swift_SendmailTransport $transport */
        $transport = $mailer->getTransport();
        $transport->shouldHaveType(Swift_SendmailTransport::class);
        $transport->getCommand()->shouldBe('command');
    }

    function it_returns_smtp_mailer_instance() {
        $mailer = $this->getMailer('smtp');
        $mailer->shouldHaveType(SwiftMailer::class);
        /** @var Swift_SmtpTransport $transport */
        $transport = $mailer->getTransport();
        $transport->shouldHaveType(Swift_SmtpTransport::class);
        $transport->getHost()->shouldBe('localhost');
        $transport->getPort()->shouldBe(25);
        $transport->getUsername()->shouldBe('username');
        $transport->getPassword()->shouldBe('password');
    }

    function it_throws_an_error_if_transport_config_can_not_be_found() {
        $this->shouldThrow(MailerTransportConfigNotFoundException::class)
            ->during('getMailer', ['unknown_transport']);
    }

    function it_throws_an_error_if_transport_is_not_supported() {
        $this->shouldThrow(MailerTransportTypeIsNotSupported::class)
            ->during('getMailer', ['not_supported']);
    }
}
