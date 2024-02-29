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
namespace Omega\Logging;

/**
 * @use
 */
use Closure;
use Omega\Logging\Exceptions\LoggingException;
use Omega\Logging\Adapter\LoggingAdapterInterface;
use Omega\Container\ServiceProvider\ServiceProviderInterface;

/**
 * Logging factory class.
 *
 * The `LoggingFactory` class serves as a factory for creating and managing logging drivers.
 * It allows the registration of different logging drivers through aliases and provides
 * a method to bootstrap and connect the appropriate driver based on the provided configuration.
 *
 * @category    Omega
 * @package     Omega\Logging
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class LoggingFactory implements ServiceProviderInterface
{
    /**
     * Drivers array.
     *
     * @var array $drivers Holds an array of registered driver.
     */
    protected array $drivers;

    /**
     * @inheritdoc
     *
     * @param  string  $alias  Holds the driver alias.
     * @param  Closure $driver Holds an instance of Closure.
     * @return $this
     */
    public function register( string $alias, Closure $driver ) : static
    {
        $this->drivers[ $alias ] = $driver;
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param  array $config Holds an array of configuration.
     * @return mixed
     * @throws LoggingException if the driver type is not defined or unrecognised.
     */
    public function bootstrap( array $config ) : LoggingAdapterInterface
    {
        if ( ! isset( $config[ 'type' ] ) ) {
            throw new LoggingException(
                'Type is not defined.'
            );
        }

        $type = $config[ 'type' ];

        if ( isset( $this->drivers[ $type ] ) ) {
            return $this->drivers[ $type ]( $config );
        }

        throw new LoggingException(
            'Unrecognised type.'
        );
    }
}