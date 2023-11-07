<?php
namespace jemdev\form;

/**
 * @author Cyrano
 *
 */
class formException extends \Exception
{
    /**
     *
     * @param   string      message[optional]
     * @param   int         code[optional]
     * @param               previous[optional]
     */
    public function __construct(string $message = null, int $code = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
