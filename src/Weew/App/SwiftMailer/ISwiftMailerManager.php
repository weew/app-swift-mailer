<?php

namespace Weew\App\SwiftMailer;

use Swift_Mailer;

interface ISwiftMailerManager {
    /**
     * @param string $transportName
     *
     * @return Swift_Mailer
     */
    function getMailer($transportName = null);
}
