<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 10.01.2020
 * Time: 8:58
 */

namespace Status\Database;


use mysql_xdevapi\Exception;
use Status\Service\Logger;

final class DBTools
{
    /**
     * @param array $parameters
     * @return string
     */
    public static function implode(array $parameters): string
    {
        return " " . implode(", ", $parameters) . " ";
    }

    /**
     * @param array $parameters
     * @return string
     */
    public static function values(array $parameters): string
    {
        $questions = [];
        for($i=0; $i<count($parameters); $i++){
            $questions[] = '?';
        }

        return " (" . self::implode($parameters) . ") VALUES (" . self::implode($questions) . ") ";
    }

    /**
     * @param array $parameters
     * @param string $where
     * @return string
     * @throws \Exception
     */
    public static function set(array $parameters, string $where = ''): string
    {
        $questions = [];
        for($i=0; $i<count($parameters); $i++){
            if(is_array($parameters[$i])){

                if(count($parameters[$i]) != 2){
                    Logger::make("Error parameters in DBTools");
                    throw new \Exception("Error parameters in DBTools", 500);
                }

                preg_match_all(
                    "/(?<l>[a-z_0-9]+\s*[\\+\\-\\/\\*]?)|(?<r>[\\+\\-\\/\\*]?\s*[a-z_0-9]+)/i",
                    $parameters[$i][1],
                    $match
                );

                $expression =
                    ($match['l'][0] ?? "") .
                    ' ? ' .
                    ($match['r'][0] ?? "") ;

                $questions[] = $parameters[$i][0] . ' = ' . $expression;
                continue;
            }
            else {
                $questions[] = $parameters[$i] . ' = ?';
            }

        }

        $where = empty($where) ? '' : ' WHERE ' . $where;

        return " SET " . self::implode($questions) . $where;
    }
}