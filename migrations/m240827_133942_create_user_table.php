<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m240827_133942_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(45)->notNull(),
            'email' => $this->string(128)->unique()->notNull(),
            'auth_key' => $this->string(64)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'verification_token'=> $this->string()->defaultValue(null),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at'=> $this->bigInteger(),
            'updated_at'=> $this->bigInteger(),
            'created' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->insert('user', [
            'id' => 1,
            'username'=>'superuser',
            'email' => 'superuser@localhost.com',
            'password_hash'=>\Yii::$app->getSecurity()->generatePasswordHash('admin@@'),
            'password_reset_token'=>\Yii::$app->getSecurity()->generatePasswordHash('admin@@'),
            'status'=>10,
            'auth_key'=>\Yii::$app->security->generateRandomString(),
        ]);

    
        $this->insert('user', [
            'id' => 2,
            'username'=>'admin',
            'email' => 'admin@localhost.com',
            'password_hash'=>\Yii::$app->getSecurity()->generatePasswordHash('admin@@'),
            'password_reset_token'=>\Yii::$app->getSecurity()->generatePasswordHash('admin@@'),
            'status'=>10,
            'auth_key'=>\Yii::$app->security->generateRandomString(),
        ]);

        
        $this->insert('user', [
            'id' => 3,
            'username'=>'ketua',
             'email' => 'ketua@localhost.com',
            'password_hash'=>\Yii::$app->getSecurity()->generatePasswordHash('ketua@@'),
            'password_reset_token'=>\Yii::$app->getSecurity()->generatePasswordHash('ketua@@'),
            'status'=>10,
            'auth_key'=>\Yii::$app->security->generateRandomString(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
