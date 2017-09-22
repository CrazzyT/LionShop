<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $cat_id
 * @property string $cat_name
 * @property integer $sort
 * @property integer $is_show
 * @property integer $parent_id
 *
 * @property Goods[] $goods
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'is_show', 'parent_id'], 'integer'],
            [['cat_name'], 'string', 'max' => 45],
            [['cat_name'], 'unique'],
            ['parent_id','default','value'=>0]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => 'Cat ID',
            'cat_name' => '栏目名称',
            'sort' => '排序',
            'is_show' => '前台是否显示',
            'parent_id' => '父级栏目',
        ];
    }

    public function loadDefaultValues($skipIfSet = true)
    {
        $this->is_show = 1;
        $this->sort = 10;
        return $this;
//        return parent::loadDefaultValues($skipIfSet); // TODO: Change the autogenerated stub
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasMany(Goods::className(), ['cat_id' => 'cat_id']);
    }

    /**
     * 添加下拉框遍历数据
     */
    public function dropDownList($categories=[])
    {
        if(empty($categories))
        {
            $categories = self::level(self::find()->asArray()->all());
        }

        $res = [];
        if(is_array($categories))
        {
            foreach ($categories as $v)
            {
                $res[$v['cat_id']] = str_repeat('|----',$v['level']).$v['cat_name'];
            }
        }
        return $res;
    }

    /**
     * 处理无限极分类
     */
    static public function level($categories=[],$except='',$parentId=0,$level=0)
    {
        static $res = [];
        if(is_array($categories))
        {
            foreach ($categories as $k => $v)
            {
                if($v['parent_id'] == $parentId && $v['cat_id'] != $except)
                {
                    $v['level'] = $level;
                    $res[] = $v;
                    self::level($categories,$except,$v['cat_id'],$level+1);
                }
            }
        }
        return $res;
    }

    static public function levels($categories=[],$except='',$parentId=0,$level=0)
    {
        static $res = [];
        if(is_array($categories))
        {
            foreach ($categories as $k => $v)
            {
                if($v['parent_id'] == $parentId && $v['cat_id'] != $except)
                {
                    $v['level'] = $level;
                    $res[] = $v;
                    self::levels($categories,$except,$v['cat_id'],$level+1);
                }
            }
        }
        return $res;
    }

}
