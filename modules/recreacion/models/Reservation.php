<?php

namespace app\modules\recreacion\models;

use Yii;

/**
 * This is the model class for table "reservas".
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $city_id
 * @property int $room_id
 * @property string $documento_cliente
 * @property double $valor_cargo
 * @property string $numero_confirmacion
 * @property string $identificador_proceso
 * @property string $identificador_session
 * @property string $canal
 * @property string $status
 * @property string $fecha_solicitud
 * @property string $fecha_confirmacion
 */
class Reservation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reservation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hotel_id', 'city_id', 'room_id', 'documento_cliente', 'valor_cargo', 'canal', 'fecha_solicitud'], 'required'],
            [['hotel_id', 'city_id', 'room_id'], 'integer'],
            [['valor_cargo'], 'number'],
            [['fecha_solicitud', 'fecha_confirmacion'], 'safe'],
            [['documento_cliente'], 'string', 'max' => 30],
            [['numero_confirmacion', 'identificador_session', 'canal', 'status'], 'string', 'max' => 100],
            [['identificador_proceso'], 'string', 'max' => 36],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hotel_id' => 'Hotel ID',
            'city_id' => 'City ID',
            'room_id' => 'Room ID',
            'documento_cliente' => 'Documento Cliente',
            'valor_cargo' => 'Valor Cargo',
            'numero_confirmacion' => 'Numero Confirmacion',
            'identificador_proceso' => 'Identificador Proceso',
            'identificador_session' => 'Identificador Session',
            'canal' => 'Canal',
            'status' => 'Status',
            'fecha_solicitud' => 'Fecha Solicitud',
            'fecha_confirmacion' => 'Fecha Confirmacion',
        ];
    }
}
