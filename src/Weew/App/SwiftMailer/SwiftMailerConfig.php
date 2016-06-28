<?php

namespace Weew\App\SwiftMailer;

use Weew\Config\IConfig;

class SwiftMailerConfig implements ISwiftMailerConfig {
    const TRANSPORTS = 'swift_mailer.transports';

    public static function TRANSPORT($name) {
        return s('swift_mailer.transports.%s', $name);
    }

    public static function TRANSPORT_TYPE($name) {
        return s('swift_mailer.transports.%s.type', $name);
    }

    /**
     * @var IConfig
     */
    protected $config;

    /**
     * SwiftMailerConfig constructor.
     *
     * @param IConfig $config
     */
    public function __construct(IConfig $config) {
        $this->config = $config;

        $config
            ->ensure(self::TRANSPORTS, 'Missing array of available mailer transports.', 'array');

        foreach ($this->getTransports() as $name => $parameters) {
            $config
                ->ensure(self::TRANSPORT($name), 'Missing an array with parameters for mailer transport.', 'array')
                ->ensure(self::TRANSPORT_TYPE($name), 'Missing mailer transport type.');
        }
    }

    /**
     * @return string
     */
    public function getDefaultTransportName() {
        return 'default';
    }

    /**
     * @return array
     */
    public function getTransports() {
        return $this->config->get(self::TRANSPORTS, []);
    }

    /**
     * @param string $name
     *
     * @return array|null
     */
    public function getTransport($name) {
        return $this->config->get(self::TRANSPORT($name));
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getTransportType($name) {
        return $this->config->get(self::TRANSPORT_TYPE($name));
    }
}
