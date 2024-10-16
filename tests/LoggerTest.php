<?php

declare(strict_types=1);

namespace Omega\Logging\Tests;

use DateMalformedStringException;
use Omega\Logging\Logger;
use Omega\Logging\LoggerInterface;
use Omega\Logging\LogLevel;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use function fclose;
use function fgetc;
use function fgets;
use function file_exists;
use function filesize;
use function fopen;
use function fseek;
use function rewind;
use function trim;

/**
 * Class LoggerTest
 *
 * This class contains unit tests for the Logger class in the Omega\Logging namespace.
 * It utilizes PHPUnit to validate the functionality and behavior of the Logger implementation,
 * ensuring that it adheres to the expected standards and provides the necessary logging features.
 *
 * The LoggerTest class covers the following aspects:
 * - Validation of PSR-3 compliance
 * - Testing log file naming conventions (extension and prefix)
 * - Writing basic logs and validating their existence and correctness
 */
class LoggerTest extends TestCase
{
    private Logger $_logger;
    private Logger $_errLogger;

    /**
     * Sets up the Logger instances before each test.
     *
     * This method initializes two Logger objects: one for general debug logs
     * and another specifically for error logs. The log path, log level,
     * and additional configuration options such as flush frequency are defined.
     */
    public function setUp(): void
    {
        $logPath         = __DIR__.'/logs';
        $this->_logger    = new Logger($logPath, LogLevel::DEBUG, ['flushFrequency' => 1]);
        $this->_errLogger = new Logger(
            $logPath, LogLevel::ERROR, [
            'extension'      => 'log',
            'prefix'         => 'error_',
            'flushFrequency' => 1
            ]
        );
    }

    /**
     * Tests if the Logger class implements the LoggerInterface from PSR-3.
     *
     * This test checks that the instance of the Logger class is indeed
     * an implementation of the LoggerInterface, ensuring compatibility with PSR-3 standards.
     *
     * @return void
     */
    public function testImplementsPsr3LoggerInterface(): void
    {
        $this->assertInstanceOf(LoggerInterface::class, $this->_logger);
    }

    /**
     * Tests if the error logger accepts the correct file extension.
     *
     * This method verifies that the log file created for error logging
     * has the expected '.log' file extension, confirming that the Logger
     * can be configured to use the correct file format.
     *
     * @return void
     */
    public function testAcceptsExtension(): void
    {
        $this->assertStringEndsWith('.log', $this->_errLogger->getLogFilePath());
    }

    /**
     * Tests if the error logger accepts the correct file prefix.
     *
     * This test checks that the log file created for error logging starts with
     * the specified prefix ('error_'), ensuring that log files are appropriately
     * named according to the configuration.
     *
     * @return void
     */
    public function testAcceptsPrefix(): void
    {
        $filename = basename($this->_errLogger->getLogFilePath());
        $this->assertStringStartsWith('error_', $filename);
    }

    /**
     * Tests the logging functionality by writing basic logs.
     *
     * This method logs messages at both DEBUG and ERROR levels, then verifies
     * that the log files exist and contain the expected log entries.
     * It ensures that logging works as intended and that the last log lines
     * can be accurately retrieved and compared.
     *
     * @throws DateMalformedStringException If the date format in the log is invalid.
     */
    public function testWritesBasicLogs(): void
    {
        $this->_logger->log(LogLevel::DEBUG, 'This is a test');
        $this->_errLogger->log(LogLevel::ERROR, 'This is a test');

        $this->assertTrue(file_exists($this->_errLogger->getLogFilePath()));
        $this->assertTrue(file_exists($this->_logger->getLogFilePath()));

        $this->assertLastLineEquals($this->_logger);
        $this->assertLastLineEquals($this->_errLogger);
    }

    /**
     * Asserts that the last log line in the specified log file equals the expected log line.
     *
     * This method retrieves the last log line from the log file and compares it
     * to the last log line stored in the Logger object, ensuring that they match.
     *
     * @param Logger $log The Logger instance to check.
     * @return void
     */
    public function assertLastLineEquals(Logger $log): void
    {
        $this->assertEquals($log->getLastLogLine(), $this->_getLastLine($log->getLogFilePath()));
    }

    /**
     * Asserts that the last log line in the specified log file does not equal the expected log line.
     *
     * This method checks that the last log line in the log file is not equal
     * to the last log line stored in the Logger object, ensuring that the logs are
     * accurately capturing different log messages as intended.
     *
     * @param Logger $log The Logger instance to check.
     * 
     * @return void
     */
    public function assertLastLineNotEquals($log): void
    {
        $this->assertNotEquals($log->getLastLogLine(), $this->_getLastLine($log->getLogFilePath()));
    }

    /**
     * Retrieves the last line of a given log file.
     *
     * This method opens the specified log file, seeks to the end, and reads
     * backwards to find the last line. It handles potential errors when opening
     * or reading from the file and throws exceptions as necessary.
     *
     * @param string $filename The path to the log file.
     * 
     * @return string The last line of the log file.
     * @throws RuntimeException If the file cannot be opened or read.
     */
    private function _getLastLine(string $filename): string
    {
        $size = filesize($filename);
        $fp = fopen($filename, 'r');

        if ($fp === false) {
            throw new RuntimeException(
                "Unable to open file: $filename"
            );
        }

        $pos = -2;
        $t = ' ';

        while ($t != "\n") {
            fseek($fp, $pos, SEEK_END);
            $t = fgetc($fp);
            $pos = $pos - 1;

            if ($size + $pos < -1) {
                rewind($fp);
                break;
            }
        }

        $t = fgets($fp);
        fclose($fp);

        if ($t === false) {
            throw new RuntimeException(
                "Unable to read last line from file: $filename"
            );
        }

        return trim($t);
    }

    /**
     * Cleans up after each test by deleting log files.
     *
     * This method removes the log files created during the test, ensuring a clean
     * state for subsequent tests and preventing leftover files from affecting results.
     *
     * @return void
     */
    public function tearDown(): void
    {
        @unlink($this->_logger->getLogFilePath());
        @unlink($this->_errLogger->getLogFilePath());
    }
}
