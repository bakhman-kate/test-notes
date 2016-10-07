<?php

use yii\db\Migration;

/**
 * Handles the creation for table `note`.
 * Has foreign keys to the tables:
 *
 * - `category`
 */
class m161005_184030_create_note_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('note', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'text' => $this->string(1024)->notNull(),
            'category_id' => $this->integer(),
        ]);

        // creates index for column `category_id`
        $this->createIndex(
            'idx-note-category_id',
            'note',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-note-category_id',
            'note',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-note-category_id',
            'note'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            'idx-note-category_id',
            'note'
        );

        $this->dropTable('note');
    }
}
