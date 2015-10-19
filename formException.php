<?php
namespace mje\form;

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
    public function __construct($message = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
