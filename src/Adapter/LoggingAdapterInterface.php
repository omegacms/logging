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
 * Logging adapter interface.
 *
 * The `LoggingAdapterInterface` defines the contract for logging adapters
 * that can be used with the Omega Logging Package. Implementing classes must
 * provide methods for logging informational, warning, and error messages.
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
interface LoggingAdapterInterface
{
    /**
     * Log informational message.
     *
     * @param  string $message Holds the message to log.
     * @return $this
     */
    public function info( string $message ) : static;

    /**
     * Log warning message.
     *
     * @param  string $message Holds the message to log.
     * @return $this
     */
    public function warning( string $message ) : static;

    /**
     * Log error message.
     *
     * @param  string $message Holds the message to log.
     * @return $this
     */
    public function error( string $message ) : static;
}