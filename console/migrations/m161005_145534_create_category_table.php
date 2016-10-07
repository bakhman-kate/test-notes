<?php

use yii\db\Migration;

/**
 * Handles the creation for table `category`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m161005_145534_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'description' => $this->string(200)->defaultValue(null),
            'user_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-category-user_id',
            'category',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-category-user_id',
            'category',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        
        $this->createIndex(
            'idx-category_name-user_id',
            'category',
            ['name', 'user_id'], 
            true
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-category-user_id',
            'category'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-category-user_id',
            'category'
        );
        
        $this->dropIndex(
            'idx-category_name-user_id',
            'category'
        );

        $this->dropTable('category');
    }
}
