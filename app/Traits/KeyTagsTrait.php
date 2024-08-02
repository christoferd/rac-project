<?php

namespace App\Traits;

trait KeyTagsTrait
{
    /**
     * Should be set to something like 'Client.'
     *
     * @var string
     */
    protected string $keyTagsPrefix = '';

    protected string $keyTagsOpen = '{';
    protected string $keyTagsClose = '}';

    /**
     * This should return $this->wrapOpenCloseTags(...)
     *
     * @return array
     */
    abstract function getKeyTags(): array;

    /**
     * This should return an array of Key Tags and the data.
     * e.g. ['{key-tag}' => 'replace the key tag with this data', ...]
     *
     * @return array
     */
    abstract function getKeyTagsData(): array;

    /**
     * Wrap each string value in the array with open and close strings, that could be { }.
     *
     * @param array $arr
     * @return array
     */
    function wrapOpenCloseTagsArray(array $arr)
    {
        foreach($arr as $key => $val) {
            if(\is_string($val)) {
                $arr[$key] = $this->keyTagsOpen.$val.$this->keyTagsClose;
            }
            else {
                $arr[$key] = $val;
            }
        }
        return $arr;
    }

    function wrapOpenCloseTagsString(string $str)
    {
        return $this->keyTagsOpen.$str.$this->keyTagsClose;
    }

    /**
     * Replace key tags in $content as found in $keyTagsData.
     *
     * @param string $content
     * @param array  $keyTagsData [ "{prefix.key-tag}" => "Text to replace with", ... ]
     * @return string
     */
    function replaceKeyTags(string $content, array $keyTagsData)
    {
        return \str_replace(array_keys($keyTagsData), \array_values($keyTagsData), $content);
    }
}
