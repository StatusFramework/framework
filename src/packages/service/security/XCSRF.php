<?php
/**
 * Created by PhpStorm.
 * User: Win7
 * Date: 03.05.2019
 * Time: 20:08
 */

namespace Status\Security;

use Status\Cookie\Converter;
use Status\Service\Utils;
use Status\System\Cookie;
use Status\System\Session;

/**
 * Class XCSRF
 * @package Status\Security
 */
final class XCSRF
{
    /**
     * @var string
     */
    private static $token = '';

    /**
     * @throws \Exception
     */
    public static function start()
    {
        Session::setValue([
            "_csrf" => self::getToken()
        ]);

        $csrf = new CSRF();
        /*ajax + token*/
        if ($csrf->isAjax() AND $csrf->getServerXToken() != self::getToken())
        {
            throw new \Exception("X-TOKEN invalid [ajax]", 419);
        }
        /*not ajax + not safe + param*/
        else if(!$csrf->isAjax() AND !$csrf->isSafe() AND $csrf->request() != self::getToken())
        {
            throw new \Exception("X-TOKEN invalid [form]", 419);
        }
    }

    /**
     * @throws \Exception
     */
    public static function setCookie()
    {
        Cookie::set("X-CSRF-TOKEN", self::getToken());
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function getToken(): string
    {
        if(empty(self::$token)) {
            $_csrf = Session::getValue()->toJson('_csrf');
            self::$token = empty($_csrf) ? Utils::_code(42) : $_csrf;
        }

        return self::$token;
    }
}