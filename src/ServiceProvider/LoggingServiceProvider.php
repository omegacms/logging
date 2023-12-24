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
namespace Omega\Logging\ServiceProvider;

/**
 * @use
 */
use Omega\Logging\LoggingFactory;
use Omega\Logging\Adapter\StreamAdapter;
use Omega\ServiceProvider\AbstractServiceProvider;
use Omega\ServiceProvider\ServiceProviderInterface;

/**
 * Logging service provider.
 *
 * The `LoggingServiceProvider` class extends the `AbstractServiceProvider` and
 * is responsible for providing logging-related services. It defines the service name,
 * factory method, and drivers available for the logging service.
 *
 * @category    Omega
 * @package     Omega\Logging
 * @subpackage  Omega\Logging\ServiceProvider
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class LoggingServiceProvider extends AbstractServiceProvider
{
    /**
     * @inheritdoc
     *
     * @return string Return the service name.
     */
    protected function name() : string
    {
        return 'logging';
    }

    /**
     * @inheritdoc
     *
     * @return mixed
     */
    protected function factory() : ServiceProviderInterface
    {
        return new LoggingFactory();
    }

    /**
     * @inheritdoc
     *
     * @return array Return an array of drivers for the service.
     */
    protected function drivers() : array
    {
        return [
            'stream' => function ( $config ) {
                return new StreamAdapter( $config );
            },
        ];
    }
}