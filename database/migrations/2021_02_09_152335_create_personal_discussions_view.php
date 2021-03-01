<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePersonalDiscussionsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement($this->dropView());
        DB::statement($this->dropFinalView());
        DB::statement($this->dropTemporary2View());
        DB::statement($this->dropTemporary1View());
        DB::statement($this->dropTemporary0View());

        
        DB::statement($this->createTemporary0View());
        //DB::statement($this->createTemporary1View());
        DB::statement($this->createTemporary2View());
        DB::statement($this->createFinalView());
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {DB::statement($this->dropView());
        DB::statement($this->dropFinalView());
        DB::statement($this->dropTemporary2View());
        DB::statement($this->dropTemporary1View());
        DB::statement($this->dropTemporary0View());
    }
   
    private function createView(): string
    {
        return <<<SQL
    CREATE VIEW `personal_discussions` AS
    (SELECT A.user_id AS user_id , A.id AS id , A.title AS title , A.url AS url , B.text AS text ,B.file AS file , B.created_at AS created_at ,B.updated_at AS updated_at FROM (SELECT user_id,id,title,url FROM discussions) AS A LEFT JOIN ( SELECT discussion_id, text, file, created_at, updated_at FROM posts WHERE id IN ( SELECT MAX(id) FROM posts GROUP BY discussion_id )) AS B ON A.id=B.discussion_id) ORDER BY created_at DESC;
    SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `personal_discussions`;
SQL;
    }



    private function createTemporary0View(): string
    {
        return <<<SQL
    CREATE VIEW `temporary0` AS
    SELECT user_id,id,title,url FROM discussions
    SQL;
    }

    private function dropTemporary0View(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `temporary0`;
SQL;
    }



    private function createTemporary1View(): string
    {
        return <<<SQL
    CREATE VIEW `temporary1` AS
    SELECT MAX(id) FROM posts GROUP BY discussion_id
    SQL;
    }

    private function dropTemporary1View(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `temporary1`;
SQL;
    }


    private function createTemporary2View(): string
    {
        return <<<SQL
    CREATE VIEW `temporary2` AS
    SELECT discussion_id, text, file, created_at, updated_at FROM posts WHERE id IN (SELECT MAX(id) FROM posts GROUP BY discussion_id)
    SQL;
    }

    private function dropTemporary2View(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `temporary2`;
SQL;
    }

    private function createFinalView(): string
    {
        return <<<SQL
    CREATE VIEW `personal_discussions` AS
    (SELECT A.user_id AS user_id , A.id AS id , A.title AS title , A.url AS url , B.text AS text ,B.file AS file , B.created_at AS created_at ,B.updated_at AS updated_at FROM temporary0 AS A LEFT JOIN temporary2 AS B ON A.id=B.discussion_id) ORDER BY created_at DESC;
 SQL;
    }

    private function dropFinalView(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `personal_discussions`;
SQL;
    }

}
