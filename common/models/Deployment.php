<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_deployment".
 *
 * @property integer $id
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property integer $status
 */
class Deployment extends ActiveRecord
{
    const STATUS_TERMINATED = 0;
    const STATUS_FAILED = 2;
    const STATUS_STOPPED = 1;
    const STATUS_PAUSED = 3;

    const STATUS_ACTIVE = 10;

    const STATUS_CREATING = 20;
    const STATUS_STARTING = 21;
    const STATUS_STOPPING = 22;
    const STATUS_TERMINATING = 23;
    const STATUS_PAUSING = 24;
    const STATUS_RESTARTING = 25;

    const HEALTH_FAILED = 0;
    const HEALTH_ACTIVE = 1;

    const DP_STATUS_ACTIVE = 0;
    const DP_STATUS_COMPLED = 1;



    public static $statusTesting = [
        Deployment::STATUS_CREATING,
        Deployment::STATUS_STARTING,
        Deployment::STATUS_STOPPING,
        Deployment::STATUS_TERMINATING,
        Deployment::STATUS_PAUSING,
        Deployment::STATUS_RESTARTING,
    ];

    public static $statusCreating = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_FAILED => 'failed',
    ];

    public static $statusStarting = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_FAILED => 'failed',
    ];

    public static $statusStopping = [
        self::STATUS_STOPPED => 'stopped',
        self::STATUS_FAILED => 'failed',
    ];

    public static $statusTerminating = [
        self::STATUS_TERMINATED => 'terminated',
        self::STATUS_FAILED => 'failed',
    ];

    public static $statusPausing = [
        self::STATUS_PAUSED => 'paused',
        self::STATUS_FAILED => 'failed',
    ];

    public static $statusRestarting = [
        self::STATUS_ACTIVE => 'active',
        self::STATUS_FAILED => 'failed',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deployment}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => function () {
                    return date('Y-m-d 00:00:00');
                },
            ]
        ];

        return array_merge(
            parent::behaviors(),
            $behaviors
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idDeployment'], 'required'],
            [['statusDeployment'], 'required'],
            [['status'], 'default', 'value' => self::DP_STATUS_ACTIVE],
            [['health'], 'default', 'value' => self::HEALTH_FAILED],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'idDeployment' => 'Deployment ID',
            'statusDeployment' => 'Deployment Status',
            'health' => 'Deployment health',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'status',
            'createdAt',
            'idDeployment',
            'statusDeployment',
            'health',
        ];
    }
}
