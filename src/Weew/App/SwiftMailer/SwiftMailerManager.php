<?php

namespace Weew\App\SwiftMailer;

use Swift_NullTransport;
use Swift_SendmailTransport;
use Swift_SmtpTransport;
use Swift_Transport;
use Weew\App\SwiftMailer\Exceptions\MailerTransportConfigNotFoundException;
use Weew\App\SwiftMailer\Exceptions\MailerTransportTypeIsNotSupported;

class SwiftMailerManager implements ISwiftMailerManager {
    /**
     * @var ISwiftMailerConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $mailers = [];

    /**
     * SwiftMailerManager constructor.
     *
     * @param ISwiftMailerConfig $config
     */
    public function __construct(ISwiftMailerConfig $config) {
        $this->config = $config;
    }

    /**
     * @param string $transportName
     *
     * @return SwiftMailer
     * @throws MailerTransportConfigNotFoundException
     */
    public function getMailer($transportName = null) {
        if ($transportName === null) {
            $transportName = $this->config->getDefaultTransportName();
        }

        if (array_has($this->mailers, $transportName)) {
            return array_get($this->mailers, $transportName);
        }

        $parameters = $this->config->getTransport($transportName);
        
        if ($parameters === null) {
            throw new MailerTransportConfigNotFoundException(s(
                'Could not find config for mailer transport with name "%s".',
                $transportName
            ));
        }

        $transportType = $this->config->getTransportType($transportName);
        $transport = $this->createTransportForType($transportType, $parameters);
        $mailer = $this->createMailer($transport);

        $this->mailers[$transportName] = $mailer;

        return $mailer;
    }

    /**
     * @param $transportType
     * @param array $parameters
     *
     * @return Swift_Transport
     * @throws MailerTransportTypeIsNotSupported
     */
    protected function createTransportForType($transportType, array $parameters) {
        if ($transportType === 'null') {
            return $this->createNullTransport($parameters);
        } else if ($transportType === 'sendmail') {
            return $this->createSendmailTransport($parameters);
        } else if ($transportType === 'smtp') {
            return $this->createSmtpTransport($parameters);
        }

        throw new MailerTransportTypeIsNotSupported(s(
            'Transport type "%s" is not supported.',
            $transportType
        ));
    }

    /**
     * @param array $parameters
     *
     * @return Swift_NullTransport
     */
    protected function createNullTransport(array $parameters) {
        return Swift_NullTransport::newInstance();
    }

    /**
     * @param array $parameters
     *
     * @return Swift_SendmailTransport
     */
    protected function createSendmailTransport(array $parameters) {
        $transport = Swift_SendmailTransport::newInstance();
        $command = array_get($parameters, 'command');

        if ($command !== null) {
            $transport->setCommand($command);
        }

        return $transport;
    }

    /**
     * @param array $parameters
     *
     * @return Swift_SmtpTransport
     */
    protected function createSmtpTransport(array $parameters) {
        $transport = Swift_SmtpTransport::newInstance();
        $host = array_get($parameters, 'host');
        $port = array_get($parameters, 'port');
        $username = array_get($parameters, 'username');
        $password = array_get($parameters, 'password');

        if ($host !== null) {
            $transport->setHost($host);
        }

        if ($port !== null) {
            $transport->setPort($port);
        }

        if ($username !== null) {
            $transport->setUsername($username);
        }

        if ($password !== null) {
            $transport->setPassword($password);
        }

        return $transport;
    }

    /**
     * @param Swift_Transport $transport
     *
     * @return SwiftMailer
     */
    protected function createMailer(Swift_Transport $transport) {
        return new SwiftMailer($transport);
    }
}
