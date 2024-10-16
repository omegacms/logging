<?php
/**
 * Part of Omega CMS - Logging Package
 *
 * @link       https://omegacms.github.io
 * @author     Adriano Giovannini <omegacms@outlook.com>
 * @copyright  Copyright (c) 2024 Adriano Giovannini. (https://omegacms.github.io)
 * @license    https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 */

/**
 * @declare
 */
declare( strict_types = 1 );

/**
 * @namespace
 */
namespace Omega\Database\Logging;

/**
 * @use
 */
use Omega\Logging\LoggerInterface;
use Omega\Logging\Logger;

/**
 * Logging factory class.
 *
 * The `LoggingFactory` class is responsible for registering and creating session
 * drivers based on configurations. It acts as a factory for different logging
 * system and provides a flexible way to connect to various logger engines.
 *
 * @category    Omega
 * @package     Logging
 * @subpackage  Factory
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2024 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class LoggingFactory implements LoggingFactoryInterface
{
    /**
     * @inheritdoc
     * 
     * @param ?array $config Holds an optional configuration array that may be used to influence the creation of the object. If no configuration is provided, default settings may be applied.
     * @return mixed Return the created object or value. The return type is flexible, allowing for any type to be returned, depending on the implementation.
     */
    public function create( ?array $config = null ) : LoggerInterface
    {
        if ( ! isset( $config[ 'type' ] ) ) {
            throw new Exception(
                'Type is not defined.'
            );
        }

        return match( $config[ 'type' ] ) {
            'stream' => new Logger( $config ),
            default  => throw new Exception( 'Unrecognised type.' )
        };
    }    
}
