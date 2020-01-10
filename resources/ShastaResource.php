<?php

namespace ddroche\shasta\resources;

use ddroche\shasta\Shasta;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Class Shasta
 * @package ddroche\shasta\resources
 * @see https://doc.payments.shasta.me/
 *
 * @property string $id
 * @property string $created_at
 * @property array $meta
 * @property string $resource
 */
abstract class ShastaResource extends Model
{
    const SCENARIO_LOAD = 'load';
    const SCENARIO_CREATE = 'create';

    /** @var string */
    public $id;

    /** @var string */
    public $created_at;

    /** @var string */
    public $project_id;

    /** @var array */
    public $meta;

    public abstract function getResource();

    public function rules()
    {
        return [
            [['id', 'created_at', 'project_id'], 'string', 'on' => static::SCENARIO_LOAD],
            ['meta', 'safe', 'on' => static::SCENARIO_LOAD],
        ];
    }

    /**
     * @return Shasta
     * @throws InvalidConfigException
     */
    public static function getShasta()
    {
        /** @var Shasta $shasta */
        $shasta = Yii::$app->get('shasta');
        return $shasta;
    }
}
