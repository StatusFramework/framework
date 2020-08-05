<?php
/**
 * @return string
 * @throws Exception
 */
function csrf_token()
{
    return \Status\Security\XCSRF::getToken();
}

/**
 * @param string $key
 * @return mixed|string
 * @throws Exception
 */
function session_get_value(string $key)
{
    return \Status\System\Session::getValue('')->toJson($key);
}

/**
 * @param array $data
 * @throws Exception
 */
function session_set_value(array $data)
{
    \Status\System\Session::setValue($data);
}

/**
 * @param string $name
 * @return \Status\Core\View|null
 * @throws Exception
 */
function view(string $name)
{
    return \Status\Core\View::make($name);
}

/**
 * @param string $key
 * @return string
 */
function env(string $key)
{
    return \Status\System\Env::get(strtoupper($key));
}

/**
 * @param $link
 */
function redirect(string $link)
{
    header("LOCATION: $link");
    exit;
}

/**
 * @param int $statusCode
 * @param string $statusText
 */
function setHeader(int $statusCode = 200, string $statusText = 'no-text')
{
    header("HTTP/2.0 $statusCode");
    header("StatusText: $statusText");
}

/**
 * @param array $array
 * @param array $symbols
 * @return array
 */
function array_clear(array $array, array $symbols = array(''))
{
    return array_diff($array, $symbols);
}
