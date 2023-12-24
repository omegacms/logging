<?php
/**
 * Part of Omega CMS -  Logging Package
 *
 * @link       https://omegacms.github.io
 * @author     Adriano Giovannini <omegacms@outlook.com>
 * @copyright  Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 */

/**
 * @declare
 */
declare( strict_types = 1 );

/**
 * @namespace
 */
namespace Omega\Logging\Adapter;

/**
 * Abstract logging adapter class.
 *
 * The `AbstractLoggingAdapter` class is an abstract base class for logging adapters
 * within the Omega Logging Package. It implements the `LoggingAdapterInterface`,
 * providing a skeletal implementation of the interface's methods for logging
 * informational, warning, and error messages. Concrete logging adapters should
 * extend this class and provide specific implementations for each logging method.
 *
 * @category    Omega
 * @package     Omega\Logging
 * @subpackage  Omega\Logging\Adapter
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
abstract class AbstractLoggingAdapter implements LoggingAdapterInterface
{
    /**
     * @inheritdoc
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    abstract public function info( string $message ) : static;

    /**
     * @inheritdoc
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    abstract public function warning( string $message ) : static;

    /**
     * @inheritdoc
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    abstract public function error( string $message ) : static;
}