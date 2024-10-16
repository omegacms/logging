<?php

declare(strict_types=1);

namespace Omega\Logging;

use Omega\Logging\Exception\LogArgumentException;
use function array_merge;
use function date;
use function file_exists;
use function floor;
use function fclose;
use function fflush;
use function fopen;
use function fwrite;
use function is_resource;
use function is_writable;
use function json_encode;
use function mkdir;
use function microtime;
use function preg_replace;
use function rtrim;
use function sprintf;
use function strlen;
use function str_contains;
use function str_repeat;
use function str_replace;
use function str_starts_with;
use function strtoupper;
use function trim;
use function var_export;
use DateTime;
use DateMalformedStringException;
use RuntimeException;
use Stringable;

class Logger extends AbstractLogger
{
    /**
     * Log array options.
     * 
     * @var array<string, mixed> Holds the log array options.
     */
    protected array $options = [
        'extension' => 'txt',
        'dateFormat' => 'Y-m-d G:i:s.u',
        'filename' => false,
        'flushFrequency' => false,
        'prefix' => 'log_',
        'logFormat' => false,
        'appendContext' => true,
    ];

    /**
     * @var string
     */
    private string $logFilePath;

    /**
     * @var string
     */
    protected string $logLevelThreshold = LogLevel::DEBUG;

    /**
     * @var int
     */
    private int $logLineCount = 0;

    /**
     * @var array<string, int>
     */
    protected array $logLevels = [
        LogLevel::EMERGENCY => 0,
        LogLevel::ALERT => 1,
        LogLevel::CRITICAL => 2,
        LogLevel::ERROR => 3,
        LogLevel::WARNING => 4,
        LogLevel::NOTICE => 5,
        LogLevel::INFO => 6,
        LogLevel::DEBUG => 7
    ];

    /**
     * @var mixed
     */
    private mixed $fileHandle;

    /**
     * @var string
     */
    private string $lastLine = '';

    /**
     * @var int
     */
    private int $defaultPermissions = 0777;

    /**
     * @param string               $logDirectory
     * @param string               $logLevelThreshold
     * @param array<string, mixed> $options
     *
     * @return void
     * @throws RuntimeException if the file not have appropriate permission.
     */
    public function __construct(
        string $logDirectory,
        string $logLevelThreshold = LogLevel::DEBUG,
        array $options = []
    ) {
        $this->logLevelThreshold = $logLevelThreshold;
        $this->options = array_merge($this->options, $options);

        $logDirectory = rtrim($logDirectory, DIRECTORY_SEPARATOR);
        if (!file_exists($logDirectory)) {
            if (!mkdir($logDirectory, $this->defaultPermissions, true) && !is_dir($logDirectory)) {
                throw new RuntimeException(
                    "Unable to create log directory: " . $logDirectory
                );
            }
        }

        if (str_starts_with($logDirectory, 'php://')) {
            $this->setLogToStdOut($logDirectory);
            $this->setFileHandle('w+');
        } else {
            $this->setLogFilePath($logDirectory);
            if (file_exists($this->logFilePath) && !is_writable($this->logFilePath)) {
                throw new RuntimeException(
                    'The file could not be written to. Check that appropriate permissions have been set.'
                );
            }
            $this->setFileHandle('a');
        }

        if (!$this->fileHandle) {
            throw new RuntimeException(
                'The file could not be opened. Check permissions.'
            );
        }
    }

    /**
     * @param string $stdOutPath
     *
     * @return void
     */
    public function setLogToStdOut(string $stdOutPath): void
    {
        $this->logFilePath = $stdOutPath;
    }

    /**
     * @param string $logDirectory
     *
     * @return void
     */
    public function setLogFilePath(string $logDirectory): void
    {
        if ($this->options['filename']) {
            if (is_string($this->options['filename'])
                && (str_contains($this->options['filename'], '.log')
                || str_contains($this->options['filename'], '.txt'))
            ) {
                $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['filename'];
            } else {
                $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['filename'] . '.' . $this->options['extension'];
            }
        } else {
            $this->logFilePath = $logDirectory . DIRECTORY_SEPARATOR . $this->options['prefix'] . date('Y-m-d') . '.' . $this->options['extension'];
        }
    }

    public function setFileHandle(string $writeMode): void
    {
        $handle = fopen($this->logFilePath, $writeMode);

        if ($handle === false) {
            throw new RuntimeException(
                'Failed to open log file for writing.'
            );
        }

        $this->fileHandle = $handle;
    }

    /**
     * @param string $dateFormat
     *
     * @return void
     */
    public function setDateFormat(string $dateFormat): void
    {
        $this->options['dateformat'] = $dateFormat;
    }

    /**
     * @param string $logLevelThreshold
     *
     * @return void
     */
    public function setLogLevelThreshold(string $logLevelThreshold): void
    {
        $this->logLevelThreshold = $logLevelThreshold;
    }

    /**
     * @param string $message
     *
     * @return void
     * @throws RuntimeException
     */
    public function write(string $message): void
    {
        if (null !== $this->fileHandle) {
            if (is_resource($this->fileHandle) && fwrite($this->fileHandle, $message) === false) {
                throw new RuntimeException('The file could not be written to. Check that appropriate permissions have been set.');
            } else {
                $this->lastLine = trim($message);
                $this->logLineCount++;

                if ($this->options['flushFrequency'] && $this->logLineCount % $this->options['flushFrequency'] === 0) {
                    if (is_resource($this->fileHandle)) {
                        fflush($this->fileHandle);
                    }
                }
            }
        }
    }


    /**
     * Get the file path that the log is currently writing to
     *
     * @return string
     */
    public function getLogFilePath(): string
    {
        return $this->logFilePath;
    }

    /**
     * Get the last line logged to the log file
     *
     * @return string
     */
    public function getLastLogLine(): string
    {
        return $this->lastLine;
    }

    /**
     * Formats the message for logging.
     *
     * @param string               $level   The Log Level of the message
     * @param string|Stringable    $message The message to log
     * @param array<string, mixed> $context The context
     *
     * @return string
     * @throws DateMalformedStringException
     */
    protected function formatMessage(string $level, string|Stringable $message, array $context): string
    {
        if ($message instanceof Stringable) {
            $message = $message->__toString();
        }

        $logFormat = is_string($this->options['logFormat']) ? $this->options['logFormat'] : '';

        if ($logFormat !== '') {
            $parts = [
                'date' => $this->getTimestamp(),
                'level' => strtoupper($level),
                'level-padding' => str_repeat(' ', 9 - strlen($level)),
                'priority' => $this->logLevels[$level],
                'message' => $message,
                'context' => json_encode($context),
            ];

            $formattedMessage = $logFormat;
            foreach ($parts as $part => $value) {
                if (is_string($value)) {
                    $formattedMessage = str_replace('{' . $part . '}', $value, $formattedMessage);
                }
            }
        } else {
            $formattedMessage = "[" . $this->getTimestamp() . "] [" . $level . "] " . $message;
        }

        if ($this->options['appendContext'] && !empty($context)) {
            $formattedMessage .= PHP_EOL . $this->indent($this->contextToString($context));
        }

        return $formattedMessage . PHP_EOL;
    }


    /**
     * Gets the correctly formatted Date/Time for the log entry.
     *
     * PHP DateTime is dump, and you have to resort to trickery to get microseconds
     * to work correctly, so here it is.
     *
     * @return string
     * @throws DateMalformedStringException
     */
    private function getTimestamp(): string
    {
        $originalTime = microtime(true);
        $micro = sprintf("%06d", ($originalTime - floor($originalTime)) * 1000000);
        $date = new DateTime(date('Y-m-d H:i:s.' . $micro, (int)$originalTime));

        if (is_string($this->options['dateFormat'])) {
            return $date->format($this->options['dateFormat']);
        }

        return $date->format('Y-m-d H:i:s.u');  // Default fallback
    }

    /**
     * Takes the given context and coverts it to a string.
     *
     * @param array<string, mixed> $context The Context
     *
     * @return string
     */
    protected function contextToString(array $context): string
    {
        $export = '';
        foreach ($context as $key => $value) {
            $export .= $key . ": ";
            $export .= preg_replace(
                [
                '/=>\s+([a-zA-Z])/im',
                '/array\(\s+\)/im',
                //'/^  |\G  /m'
                '/^\s{2}|\G\s{2}/m'
                ], [
                '=> $1',
                '[]',
                '    '
                ], str_replace('array (', 'array(', var_export($value, true))
            );
            $export .= PHP_EOL;
        }
        return str_replace(['\\\\', '\\\''], ['\\', '\''], rtrim($export));
    }

    /**
     * Indents the given string with the given indent.
     *
     * @param  string $string The string to indent
     * @param  string $indent What to use as the indent.
     * @return string
     */
    protected function indent(string $string, string $indent = '    '): string
    {
        return $indent . str_replace("\n", "\n" . $indent, $string);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed               $level
     * @param string|Stringable   $message
     * @param array<string,mixed> $context
     *
     * @return void
     * @throws DateMalformedStringException
     * @throws LogArgumentException
     */
    public function log(mixed $level, string|Stringable $message, array $context = []): void
    {
        if (!isset($this->logLevels[$level])) {
            throw new LogArgumentException(
                "Invalid log level: "
                . $level
            );
        }

        if ($this->logLevels[$this->logLevelThreshold] < $this->logLevels[$level]) {
            return;
        }

        if (!is_string($level)) {
            throw new LogArgumentException("Log level must be a string, " . gettype($level) . " given.");
        }

        $message = $this->formatMessage($level, $message, $context);
        $this->write($message);
    }

    public function __destruct()
    {
        if (is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
        }
    }
}