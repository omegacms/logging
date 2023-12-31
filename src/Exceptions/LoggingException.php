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
namespace Omega\Logging\Exceptions;

/**
 * @use
 */
use RuntimeException;

/**
 * Logging exception class.
 *
 * The `LoggingException` class extends the `RuntimeException` class and
 * serves as an exception specific to the Omega Logging Package. It is thrown
 * for logging-related errors.
 *
 * @category    Omega
 * @package     Omega\Logging
 * @subpackage  Omega\Logging\Exception
 * @link        https://omegacms.github.io
 * @author      Adriano Giovannini <omegacms@outlook.com>
 * @copyright   Copyright (c) 2022 Adriano Giovannini. (https://omegacms.github.io)
 * @license     https://www.gnu.org/licenses/gpl-3.0-standalone.html     GPL V3.0+
 * @version     1.0.0
 */
class LoggingException extends RuntimeException
{
}