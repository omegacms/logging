<?php

declare(strict_types=1);

namespace Omega\Logging;

use Omega\Logging\Exception\LogArgumentException;
use Stringable;

interface LoggerInterface
{
    /**
     * System is unusable.
     *
     * @param string|Stringable    $message Holds the message for system is unusable.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     */
    public function emergency(string|Stringable $message, array $context = []): void;
    
    /**
     * Action must be taken immediately.
     *
     * @param string|Stringable    $message Holds the message for action must be taken immediately.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     */
    public function alert(string|Stringable $message, array $context = []): void;

    /**
     * Critical condition.
     *
     * @param string|Stringable    $message Holds the message for critical condition.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     */
    public function critical(string|Stringable $message, array $context = []): void;

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string|Stringable    $message Holds the message for runtime errors.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     */
    public function error(string|Stringable $message, array $context = []): void;

    /**
     * Exceptional occurrences that are not errors.
     *
     * @param string|Stringable    $message Holds the message for exceptional errors.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     */
    public function warning(string|Stringable $message, array $context = []): void;

    /**
     * Normal but significant events.
     *
     * @param string|Stringable    $message Holds the message for normal but significant events.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     */
    public function notice(string|Stringable $message, array $context = []): void;

    /**
     * Interesting events.
     *
     * @param string|Stringable    $message Holds the message for interesting events.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     */
    public function info(string|Stringable $message, array $context = []): void;

    /**
     * Detailed debug information.
     *
     * @param string|Stringable    $message Holds the message for detailed debug information.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     */
    public function debug(string|Stringable $message, array $context = []): void;
    
    /**
     * Logs with an arbitrary level.
     * 
     * @param mixed                $level   Holds the log level. 
     * @param string|Stringable    $message Holds the log message.
     * @param array<string, mixed> $context Holds the context of message.
     *
     * @return void
     * @throws LogArgumentException
     */
    public function log(mixed $level, string|Stringable $message, array $context = []): void;
}