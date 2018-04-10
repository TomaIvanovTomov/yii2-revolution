<?php

namespace tomaivanovtomov\revolution;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    /**
     * The database connection to use for models in this module.
     *
     * @var string
     */
    public $dbConnection = 'db';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * @return string
     */
    public function getDb()
    {
        return \Yii::$app->get($this->dbConnection);
    }
}
