<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_post".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $tags
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $author_id
 */
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 1;
    const STATUS_PUBLISHED = 2;

    const EVENT_STATUS_CHANGE = 'statusChange';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['title', 'content'],
                'filter',
                'filter' => function ($value) {
                    return \Yii::$app->formatter->asHtml($value);
                }
            ],
            [['title', 'content', 'status'], 'required'],
            [['content', 'tags'], 'string'],
            [['status'], 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_PUBLISHED]],
            ['status', 'filter', 'filter' => function ( $value ) {
                // приведение типа необходимо для сравнения $changedAttributes
                // чтобы определить были изменения или нет
                // поскольку из формы приходит не int, а string
                return (int) $value;
            }],
            [['title'], 'string', 'max' => 128]
        ];
    }

//    public function extraFields()
//    {
//        return [
//            'comments',
//        ];
//    }

    public function fields()
    {
        return [
            'id',
            'title',
            'content',
            'tags',
            'status',
            'author',
            'created_at' => function () {
                return date('d-m-y H:i', $this->created_at);
            },
            'updated_at' => function () {
                return date('d-m-y H:i', $this->updated_at);
            },
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'tags' => 'Tags',
            'status' => 'Status',
            'created_at' => 'created',
            'updated_at' => 'updated',
            'author_id' => 'Author ID',
        ];
    }

    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                if (empty($this->author_id)) {
                    $this->author_id = \Yii::$app->user->identity->getId();
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
        if (!$insert && array_key_exists('status', $changedAttributes)) {
            $this->trigger(self::EVENT_STATUS_CHANGE);
        }
    }
}
