<?php

declare(strict_types=1);

namespace Omega\Logging;

trait LoggerAwareTrait
{
    /**
     * The logger instance.
     *
     * @var ?LoggerInterface Holds the current logger instance.
     */
    protected ?LoggerInterface $logger = null;

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}