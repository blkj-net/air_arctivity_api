<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Exception;

use App\Constants\ErrorCode;
use Hyperf\Server\Exception\ServerException;
use Throwable;

class PaymentException extends BusinessException
{
    public function __construct(string $message = null, $code = 10003, Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = ErrorCode::getMessage($code);
        }
        parent::__construct($code, $message, $previous);
    }
}