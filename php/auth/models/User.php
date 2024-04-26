<?php

namespace app\models;

use Yii;
/**
 * This is the model class for table "base_user".
 *
 * @property int $id
 * @property string $password
 * @property string|null $last_login
 * @property int $is_superuser
 * @property string|null $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property int|null $is_staff
 * @property int|null $is_active
 * @property string|null $date_joined
 * @property string|null $name
 * @property string|null $email
 *
 * @property BaseUserGroups[] $baseUserGroups
 * @property BaseUserUserPermissions[] $baseUserUserPermissions
 * @property DjangoAdminLog[] $djangoAdminLogs
 * @property AuthGroup[] $groups
 * @property AuthPermission[] $permissions
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'base_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password'], 'required'],
            [['last_login', 'date_joined'], 'safe'],
            [['is_superuser', 'is_staff', 'is_active'], 'integer'],
            [['password'], 'string', 'max' => 128],
            [['username', 'first_name', 'last_name'], 'string', 'max' => 150],
            [['name'], 'string', 'max' => 120],
            [['email'], 'string', 'max' => 254],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Password',
            'last_login' => 'Last Login',
            'is_superuser' => 'Is Superuser',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'is_staff' => 'Is Staff',
            'is_active' => 'Is Active',
            'date_joined' => 'Date Joined',
            'name' => 'Name',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[BaseUserGroups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBaseUserGroups()
    {
        return $this->hasMany(BaseUserGroups::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[BaseUserUserPermissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBaseUserUserPermissions()
    {
        return $this->hasMany(BaseUserUserPermissions::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[DjangoAdminLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDjangoAdminLogs()
    {
        return $this->hasMany(DjangoAdminLog::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(AuthGroup::class, ['id' => 'group_id'])->viaTable('base_user_groups', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Permissions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(AuthPermission::class, ['id' => 'permission_id'])->viaTable('base_user_user_permissions', ['user_id' => 'id']);
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implement this method if you're using access tokens for authentication
        // Example: return static::findOne(['access_token' => $token]);
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // Implement this method if you're using authentication keys for user authentication
        return $this->id;
    }

    public function validateAuthKey($authKey)
    {
        // Implement this method to validate authentication key
        return $this->id === $authKey;
    }
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
