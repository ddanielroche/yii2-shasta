<?php

namespace ddroche\shasta\resources;

/**
 * Class File
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/#definition-File
 *
 * @property string $file
 * @property string $md5
 * @property string $crc32
 * @property string $filename
 * @property integer $filetype
 * @property boolean $public
 * @property string $url
 *
 * TODO Invalid memory address or nil pointer dereference
 */
class File extends ShastaResource
{
    /** @var string */
    public $file;
    /** @var string */
    public $md5;
    /** @var string */
    public $crc32;
    /** @var string */
    public $filename;
    /** @var integer */
    public $filetype;
    /** @var boolean */
    public $public;
    /** @var string */
    public $url;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['file'], 'required', 'on' => static::SCENARIO_CREATE],
            [['file'], 'string', 'on' => static::SCENARIO_CREATE],
            [['md5', 'crc32', 'filename', 'url'], 'string', 'on' => static::SCENARIO_LOAD],
            [['filetype'], 'integer', 'on' => static::SCENARIO_LOAD],
            [['public'], 'boolean'],
        ]);
    }

    public function getResource()
    {
        return '/files';
    }
}