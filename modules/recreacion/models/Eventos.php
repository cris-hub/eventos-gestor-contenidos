<?php

namespace app\modules\recreacion\models;

use Yii;

/**
 * This is the model class for table "eventos".
 *
 * @property int $even_id
 * @property string $even_name
 * @property string $even_fecha
 * @property string $even_descripcion
 */
class Eventos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['even_fecha'], 'safe'],
            [['even_name', 'even_descripcion'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'even_id' => Yii::t('app', 'Even ID'),
            'even_name' => Yii::t('app', 'Even Name'),
            'even_fecha' => Yii::t('app', 'Even Fecha'),
            'even_descripcion' => Yii::t('app', 'Even Descripcion'),
        ];
    }
}
