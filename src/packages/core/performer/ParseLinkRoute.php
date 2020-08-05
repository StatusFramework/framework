<?php
namespace Status\Core\Performer;

/**
 * Class ParseLinkRoute
 * @package Status\Core\Performer
 */
final class ParseLinkRoute
{
    /**
     * @var string
     */
    private $rURL;
    /**
     * @var array
     */
    private $result = [];

    /**
     * ParseLink constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->rURL = $url;
        $this->startParse();
    }

    /**
     *
     */
    private function startParse()
    {
        $this->result = $this->searchElementsURL();
    }

    /**
     * @param string $str
     * @return array
     */
    private function searchElementsURL(): array
    {
        $elemsURI = [];

        preg_match_all(
            "/\/(?'path'[^\/\\$\{\}]+)|\/\{\\$?(?'args'[^\/\\$\{\}]+)\}|\//i",
            $this->rURL,
            $elemsURI,
            PREG_UNMATCHED_AS_NULL
        );

        return $elemsURI;
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return array_unique($this->result, SORT_REGULAR);
    }
}