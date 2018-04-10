<?php

use yii\db\Migration;

/**
 * Handles the creation of table `slide`.
 */
class m180410_054406_create_slide_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('slide', [
            'id' => $this->primaryKey(),
            'sort' => $this->integer(11)->defaultValue(999),
            'filename' => $this->string(255)->defaultValue(null)
        ]);

        $this->createTable('slideLang', [
            'id' => $this->primaryKey(),
            'slide_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->defaultValue(null),
            'description' => $this->text()->defaultValue(null),
            'language' => $this->string(5)->notNull()
        ]);

        $this->createIndex(
            'idx-slide_id',
            'slideLang',
            'slide_id'
        );

        $this->addForeignKey(
            'fk_slide_lang_to_slide',
            'slideLang',
            'slide_id',
            'slide',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_slide_lang_to_slide',
            'slideLang'
        );

        $this->dropIndex(
            'idx-slide_id',
            'slideLang'
        );

        $this->dropTable('slideLang');

        $this->dropTable('slide');
    }
}
