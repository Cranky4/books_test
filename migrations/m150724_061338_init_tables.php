<?php

use yii\db\Schema;
use yii\db\Migration;

class m150724_061338_init_tables extends Migration
{
    public function up()
    {
        $this->createTable('books', [
          'id' => Schema::TYPE_PK,
          'name' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Название"',
          'date_create' => Schema::TYPE_INTEGER , 'NOT NULL COMMENT "Дата создания"',
          'date_update' => Schema::TYPE_INTEGER , 'DEFAULT NULL COMMENT "Дата редактирования"',
          'date' => Schema::TYPE_INTEGER , 'NOT NULL COMMENT "Дата публикации"',
          'preview_image' => Schema::TYPE_TEXT. ' COMMENT "Картинка"',
          'author_id' => Schema::TYPE_INTEGER . 'DEFAULT NULL COMMENT "Ид автора"'
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');

        $this->createIndex('author_id', 'books', 'author_id');

        $this->createTable('authors', [
          'id' => Schema::TYPE_PK,
          'first_name' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Имя"',
          'last_name' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Фамилия"',
        ], 'ENGINE=InnoDB  DEFAULT CHARSET=utf8');


        $this->insert('authors',[
          'first_name' => 'Джорж',
          'last_name' => 'Мартин'
        ]);

        $this->insert('books',[
            'name' => 'Игра престолов',
            'date' => 838193663,
            'date_create' => time(),
            'author_id' => 1,
            ''
        ]);
    }

    public function down()
    {
        $this->dropTable('books');
        $this->dropTable('authors');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
