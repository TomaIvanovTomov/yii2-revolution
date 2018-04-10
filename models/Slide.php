<?php

namespace tomaivanovtomov\revolution\models;

use backend\models\CActiveRecord;
use Yii;
use omgdef\multilingual\MultilingualQuery;
use omgdef\multilingual\MultilingualBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "slide".
 *
 * @property int $id
 * @property int $enable
 * @property int $sort
 * @property string $filename
 *
 * @property Slidelang[] $slidelangs
 */
class Slide extends CActiveRecord
{
    public $image;

    public static function find()
    {
        return new MultilingualQuery(get_called_class());
    }

    public function behaviors()
    {
        $allLanguages = [];
        foreach (Yii::$app->params['languages'] as $title => $language) {
            $allLanguages[$title] = $language;
        }

        return [
            'ml' => [
                'class' => MultilingualBehavior::className(),
                'languages' => $allLanguages,
                //'languageField' => 'language',
                //'localizedPrefix' => '',
                //'requireTranslations' => false',
                //'dynamicLangClass' => true',
                //'langClassName' => PostLang::className(), // or namespace/for/a/class/PostLang
                'defaultLanguage' => Yii::$app->params['languageDefault'],
                'langForeignKey' => 'slide_id',
                'tableName' => "{{%slidelang}}",
                'attributes' => [
                    'title',
                    'description',
                ]
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slide';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $string_255 = $this->multilingualFields(['title']);
        $string = $this->multilingualFields(['description']);

        return [
            [['sort'], 'integer'],
            [['filename'], 'string', 'max' => 255],
            [$string_255, 'string', 'max' => 255],
            ['title', 'string', 'max' => 255],
            [$string, 'string'],
            ['description', 'string'],
            [['image'], 'file', 'extensions' => Yii::$app->params['availableImageExtensions']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'sort' => Yii::t('app', 'Sort'),
            'filename' => Yii::t('app', 'Filename'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public function upload($index)
    {
        if ($this->validate(['image'])) {

            $this->image = UploadedFile::getInstance($this, "image[$index]");

            if(!empty($this->image)){
                $brand_dir = \Yii::getAlias("@images-dev") . "/backend_images/".Slide::FOLDER_SLIDER."/";
                if(!file_exists($brand_dir)){
                    mkdir( $brand_dir, 0777, true );
                }

                $this->image->saveAs(\Yii::getAlias("@images-dev") . "/backend_images/".Slide::FOLDER_SLIDER."/" . $this->id . "_" . $this->image->name);

                $this->filename = "{$this->id}_{$this->image->name}";

                $this->uploadThumb(Slide::FOLDER_SLIDER, $this);

                $this->image = null;
            }else{
                return false;
            }

        } else {
            return false;
        }
    }

    public function loadModels($index)
    {
        $props = ['title', 'description'];

        foreach (Yii::$app->params['languages'] as $language) {
            if (Yii::$app->params['languageDefault'] != $language) {
                foreach ($props as $property) {
                    $prop_lang = "{$property}_{$language}";
                    $this->$prop_lang = Yii::$app->request->post('Slide')["{$property}_{$language}"][$index];
                }
            }
        }

        $this->title = Yii::$app->request->post('Slide')["title"][$index];
        $this->description = Yii::$app->request->post('Slide')["description"][$index];

        $this->upload($index);

    }

}
