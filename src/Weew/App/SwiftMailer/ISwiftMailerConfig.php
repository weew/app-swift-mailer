<?php

namespace Weew\App\SwiftMailer;

interface ISwiftMailerConfig {
    /**
     * @return string
     */
    function getDefaultTransportName();

    /**
     * @return array
     */
    function getTransports();

    /**
     * @param string $name
     *
     * @return array
     */
    function getTransport($name);

    /**
     * @param string $name
     *
     * @return string
     */
    function getTransportType($name);
}
