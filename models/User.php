<?php

namespace app\models;

use Yii;
use Firebase\JWT\JWT;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string|null $verification_token
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string $created
 * @property string $updated
 */
class User  extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE=13;
    
    public $current_pass,$new_pass,$retype_pass;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['username','email'], 'required'],
            [['username','email'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['current_pass', 'new_pass', 'retype_pass'], 'required','on'=>'change','message'=>''],
            ['current_pass','checkOldPassword','on'=>'change','message'=>''],
            ['retype_pass', 'compare','compareAttribute'=>'new_pass'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'verification_token' => 'Verification Token',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

     /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            
            'timestamp'=>[
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => ['created','created_at'],
                'updatedAtAttribute' => ['updated','updated_at'],
                'value' => new Expression('NOW()'),
            ],
            
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        //return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
        
        $secret = base64_encode(Yii::$app->params['JWT']['secret_key']);
        try{
            // decode token
            
            //$private_key = Yii::$app->params['OPENSSL']['private_key'];
            $jwt = JWT::decode($token,$secret, [Yii::$app->params['JWT']['algorithm']]);
            
            //$jwt = JWT::decode($token,$private_key, 'RS256');
            
            //$public_key = Yii::$app->params['OPENSSL']['public_key'];
            //$jwt = JWT::decode($token,$public_key, 'RS256');
            
            // temukan user berdasrkan jti / id user
            
            return static::findOne($jwt->jti);
            
        }catch(\Exception $e){
            
            // throw UnauthorizedHttpException jika token tidak valid
            throw new UnauthorizedHttpException(Yii::t('yii','Invalid or Expired Token'));
            
        }
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }
    
    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }
    
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function checkOldPassword($attribute,$params)
    {
        $user = Yii::$app->user->identity;
        if (!$user || !$user->validatePassword($this->current_pass)) {
            $this->addError('current_pass', 'Password lama salah.');
        }
    }
    
   
}
