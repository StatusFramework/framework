<?php

namespace Status\Session;

use Status\System\Cookie;
use Status\Session\Founder;
use Status\System\Session;
use Status\Session\Storage;
use Status\Service\Utils;

/**
 * Class Creator
 * @package Status\Session
 */
final class Creator extends Founder
{
    /**
     * @var string
     */
    protected $path = '';
    /**
     * @var string
     */
    protected $sessionID = '';
    /**
     * @var string
     */
    protected $sessionPath = '';
    /**
     * @var string
     */
    private $nameAppCookie = '';

    /**
     * Creator constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        /*получаем название куки*/
        $this->nameAppCookie = env("COOKIE_NAME");
    }

    /**
     * @throws \Exception
     */
    public function make()
    {
        $cookieCode = Cookie::get($this->nameAppCookie)->toJson('code', false);

        if (empty($cookieCode)) {
            $this->createSession();
            return;
        }

        $this->sessionID = $this->getSessionID($cookieCode);

        if (!$this->exists($this->sessionID)) {
            $this->createSession();
        } else {
            $this->rewriteSession($cookieCode);
        }

    }

    /**
     * @return string|null
     */
    private function getCodeCookie(): ?string
    {
        $code = Cookie::get($this->nameAppCookie);
        return $code->isEmpty() ? NULL : $code->toJson('code');
    }

    /**
     * @param string $cookie
     */
    private function setCookie(string $code)
    {
        Cookie::set($this->nameAppCookie, $this->convert([
            "code" => $code,
            "check" => $this->getCheck()
        ]))->toSave();
    }

    /**
     *
     */
    private function createSession()
    {
        self::$sessionCode = $this->getCode();
        $this->sessionID = $this->getSessionID(self::$sessionCode);
        Storage::setCache("session_id", $this->sessionID);
        $path = $this->sessionPath($this->sessionID);
        $this->write($path, [
            "check" => $this->getCheck(),
            "_flush" => NULL,
            "_csrf" => NULL
        ]);
        $this->setCookie(self::$sessionCode);
    }

    /**
     * @param string $code
     * @throws \Exception
     */
    private function rewriteSession(string $code)
    {
        self::$sessionCode = $code;
        Storage::setCache("session_id", $this->sessionID);
        $sessionCheck = Session::getValue()->toJson('check');
        $cookieCheck = Cookie::get($this->nameAppCookie)->toJson('check');
        if ($sessionCheck != $cookieCheck) {
            throw new \Exception('error checking session', 403);
        }
        //перезаписываем данные для проверки
        Session::setValue([
            "check" => $this->getCheck()
        ]);
        $this->setCookie(self::$sessionCode);
    }
}