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
        foreach (Yii::$app->params['language-information'] as $language) {
            $allLanguages[$language['extension']] = $language['extension'];
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
                'tableName' => "{{%slideLang}}",
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
            [['image'], 'file', 'extensions' => ['jpg', 'png', 'jpeg']],
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

        foreach (Yii::$app->params['language-information'] as $language) {
            if (Yii::$app->params['languageDefault'] != $language['extension']) {
                foreach ($props as $property) {
                    $prop_lang = "{$property}_{$language['extension']}";
                    $this->$prop_lang = Yii::$app->request->post('Slide')["{$property}_{$language['extension']}"][$index];
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
            foreach (Yii::$app->params['language-information'] as $language) {
                if (Yii::$app->params['languageDefault'] != $language['extension']) {
                    $output[] = "{$field}_{$language['extension']}";
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
        return Slide::find()
            ->select(['slide.id', 'slideLang.title', 'slide.filename'])
            ->leftJoin('slideLang', 'slide.id=slideLang.slide_id')
            ->where('slideLang.language=:lang', [':lang' => Yii::$app->language])
            ->orderBy('sort ASC')
            ->all();
    }

    public static function deleteRemovedImages($images)
    {
        $searchModel = new SlideSearch();
        $slide_models = $searchModel->search(Yii::$app->request->queryParams)->getModels();

        //Get images on update keys
        $images_keys = array_keys($images);

        //Get images from database keys
        $slide_models_keys = array_keys($slide_models);

        $diffs = array_diff($slide_models_keys, $images_keys);

        foreach ($diffs as $diff){
            $file = isset($_SERVER['HTTPS']) ? 'https' : 'http' . "://" . $_SERVER['HTTP_HOST'] . '\frontend\web' . "/" . Slide::FOLDER_SLIDER . "/" . $slide_models[$diff]->filename;

            //Delete image from slides folder
            if(file_exists($file)){
                unlink($file);
            }

            //Delete record from database
            $slide_models[$diff]->delete();
        }

    }

    public function loadSortable()
    {
        $slider = Slide::find()->select(['id', 'sort', 'filename'])->orderBy('sort ASC')->asArray()->all();

        $output = [];

        foreach ($slider as $slide) {
            $output[] = [
                'content' =>
                    "<div class=\"grid-item text-danger\" style='width: 150px; height: 150px;'><input type='hidden' name='Slide[{$slide['id']}]' value='{$slide['id']}'>
                        ".Html::img($this->getImagePath() . "/" . Slide::FOLDER_SLIDER . "/" . $slide['filename'],
                        [
                            'style' => 'max-width: 100%; height: 100%; pointer-events: none;'
                        ])."
                    </div>"
            ];
        }

        return $output;
    }

    public function reorderSlide($id, $sort)
    {
        $model = Slide::findOne((int)$id);
        $model->sort = $sort;

        if($model->update() === false){
            return false;
        }
    }

    /**
     * Get images absolute path
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->image_path;
    }
}
