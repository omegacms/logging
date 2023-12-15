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
 * @use
 */
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Stream adapter class..
 *
 * @category    Omega
 * @package     Omega\Logging
 * @subpackage  Omega\Logging\Adapter
 * @link        https://omegacms.github.com
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.com)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class StreamAdapter extends AbstractLoggingAdapter
{
    /**
     * Configuration array.
     *
     * @var array $config Holds an array of configuration.
     */
    private array $config;

    /**
     * Logger instance.
     *
     * @var Logger $logger Holds an instance of Logger.
     */
    private Logger $logger;

    /**
     * StreamAdapter class constructor.
     *
     * @param  array $config Holds an array of configuration.
     * @return void
     */
    public function __construct( array $config )
    {
        $this->config = $config;
    }

    /**
     * Info method.
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    public function info( string $message ) : static
    {
        $this->logger()->info( $message );

        return $this;
    }

    /**
     * Warning method.
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    public function warning( string $message ) : static
    {
        $this->logger()->warning( $message );

        return $this;
    }

    /**
     * Error method.
     *
     * @param  string $message Holds the message.
     * @return $this
     */
    public function error( string $message ) : static
    {
        $this->logger()->error( $message );

        return $this;
    }

    /**
     * Error logger.
     *
     * @return Logger Return an instance of Logger.
     */
    private function logger() : Logger
    {
        if ( ! isset( $this->logger ) ) {
            $this->logger = new Logger( $this->config[ 'name' ] );
            $this->logger->pushHandler( new StreamHandler( $this->config[ 'path' ], $this->config[ 'minimum' ] ) );
        }

        return $this->logger;
    }
}