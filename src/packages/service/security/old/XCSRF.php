<?php

/**
 * Class XCSRF
 * @package Status\Security
 */
final class XCSRF
{
    /**
     * @var string|NULL
     */
    private static $xtoken = NULL;

    /**
     * @return XCSRF
     */
    public static function make()
    {
        if (empty(self::$xtoken)) {
            self::$xtoken = Utils::_code(128);
        }

        return new self();
    }

    /**
     * @param CSRF $csrf
     * @return XCSRF
     * @throws \Exception
     */
    public function check(CSRF $csrf)
    {
        if($csrf->isAjax()){
            file_put_contents("csrf", $this->viewToken(getallheaders()['X-CSRF-TOKEN']));
        }
        return new self();
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return self::$xtoken;
    }

    /**
     * @return string
     */
    public function getVerifyToken()
    {
        return md5(self::$xtoken . $_SERVER["REMOTE_ADDR"] . Env::get('APP_KEY'));
    }

    /**
     * @return string
     */
    public function viewToken(string $code)
    {
        return md5($code . $_SERVER["REMOTE_ADDR"] . Env::get('APP_KEY'));
    }

    public function headers()
    {
        if(!Env::get('CSRF_ENABLED')) return;

        header("X-CSRF-TOKEN: ". self::$xtoken);
    }

    public function cookie()
    {
        Cookie::create()->key('X-CSRF-TOKEN', base64_encode(self::$xtoken))->save();
        return new self();
    }

    /**
     * @param string $token
     * @return bool
     */
    private function equalsToken(string $token)
    {
        return (
            Session::read()->make()->getValue('_csrf')
            ===
            md5($token . $_SERVER["REMOTE_ADDR"] . Env::get('APP_KEY'))
        );
    }

    /**
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    private function equalsCookie(string $token)
    {
        return (
            Session::read()->make()->getValue('_csrf')
            ===
            md5(Cookie::read('X-CSRF-TOKEN')->value() . $_SERVER["REMOTE_ADDR"] . Env::get('APP_KEY'))
        );
    }
}
