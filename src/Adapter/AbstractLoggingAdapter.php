<?php
/**
 * Part of Banco Omega CMS -  Logging Package
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
 * Abstract logging adapter class..
 *
 * @category    Omega
 * @package     Framework\Logging
 * @subpackage  Omega\Logging\Adapter
 * @link        https://omegacms.github.com
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.com)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
abstract class AbstractLoggingAdapter implements LoggingAdapterInterface
{
    /**
     * Info method.
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    abstract public function info( string $message ) : static;

    /**
     * Warning method.
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    abstract public function warning( string $message ) : static;

    /**
     * Error method.
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    abstract public function error( string $message ) : static;
}