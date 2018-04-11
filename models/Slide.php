<?php

namespace tomaivanovtomov\slider\models;

use Yii;
use omgdef\multilingual\MultilingualQuery;
use omgdef\multilingual\MultilingualBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
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
class Slide extends ActiveRecord
{
    /**
     * Slider images folder
     */
    const FOLDER_SLIDER = "slides";

    /**
     * The image folder path
     *
     * @var
     */
    private $image_path;

    public $image;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->image_path = isset($_SERVER['HTTPS']) ? 'https' : 'http' . "://" . $_SERVER['HTTP_HOST'] . '\frontend\web';
        /*$this->image_path = 'C:\xampp\htdocs\test\frontend\web';*/
    }

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
            [['image'], 'file', 'extensions' => ['jpg', 'png', 'gif', 'jpeg']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $title = "title_" . Yii::$app->language;
        $description = "description_" . Yii::$app->language;

        return [
            'id' => Yii::t('app', 'ID'),
            'sort' => Yii::t('app', 'Sort'),
            'filename' => Yii::t('app', 'Filename'),
            'title' => Yii::t('app', 'Title'),
            $title => Yii::t('app', 'Title En'),
            $description => Yii::t('app', 'Description'),
        ];
    }

    public function upload($index)
    {
        if ($this->validate(['image'])) {

            $this->image = UploadedFile::getInstance($this, "image[$index]");

            if(!empty($this->image)){
                $brand_dir = $this->image_path . "/".Slide::FOLDER_SLIDER."/";
                if(!file_exists($brand_dir)){
                    mkdir( $brand_dir, 0777, true );
                }

                $this->image->saveAs($this->image_path . "/".Slide::FOLDER_SLIDER."/" . $this->id . "_" . $this->image->name);

                $this->filename = "{$this->id}_{$this->image->name}";

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

    /**
     * Iterate over the array of fileds and adds language suffix if the language is not default
     * @param $fields
     * @return array
     */
    protected function multilingualFields($fields)
    {
        $output = [];

        foreach ($fields as $field) {
            foreach (Yii::$app->params['languages'] as $language) {
                if (Yii::$app->params['languageDefault'] != $language) {
                    $output[] = "{$field}_{$language}";
                }
            }
        }

        return $output;
    }

    public function getImage($height = null)
    {
        $image = $this->image_path . "/" . Slide::FOLDER_SLIDER . "/" . $this->filename;

        return Html::img( $image , [
            'style' => "height:{$height}px",
            'alt' => $this->title,
            "title" => $this->title
        ]);

        //Function doesn't return correct bool
        /*if(file_exists($image)){
            return Html::img( $image , ['alt' => $this->title, "title" => $this->title]);
        }*/
    }

    public static function getSliderImages()
    {
        return \tomaivanovtomov\slider\models\Slide::find()
            ->joinWith('translation')
            ->select(['slide.id', 'slideLang.title', 'slide.filename'])
            ->where('slideLang.language=:lang', [':lang' => Yii::$app->language])
            ->all();
    }
}
