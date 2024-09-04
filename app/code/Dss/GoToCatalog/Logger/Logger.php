<?php

declare(strict_types=1);

/**
* Digit Software Solutions.
*
* NOTICE OF LICENSE
*
* This source file is subject to the EULA
* that is bundled with this package in the file LICENSE.txt.
*
* @category  Dss
* @package   Dss_GoToCatalog
* @author    Extension Team
* @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
*/
namespace Dss\GoToCatalog\Logger;

class Logger extends \Monolog\Logger
{

    /**
     * Custom Log for module
     *
     * @param string $message
     */
    public function customLog($message)
    {
        try {
            if ($message === null) {
                $message = 'NULL';
            }
            if (is_array($message)) {
                $message = \json_encode($message, JSON_PRETTY_PRINT);
            }
            if (is_object($message)) {
                $message = \json_encode($message, JSON_PRETTY_PRINT);
            }
            if (!empty(\json_last_error())) {
                $message = (string)\json_last_error();
            }
            $message = (string)$message;
        } catch (\Exception $e) {
            $message = 'INVALID MESSAGE::' . $e->getMessage();
        }
        $message .= PHP_EOL;
        $this->info($message);
    }
}
