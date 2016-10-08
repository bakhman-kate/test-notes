<?php

use yii\db\Migration;

/**
 * Handles adding token to table `user`.
 */
class m161007_192817_add_token_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'token', $this->string(1024));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'token');
    }
}
