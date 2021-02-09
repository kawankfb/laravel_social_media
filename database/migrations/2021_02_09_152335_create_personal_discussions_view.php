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
        DB::statement($this->createView());
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
    }
   
    private function createView(): string
    {
        return <<<SQL
    CREATE VIEW `personal_discussions` AS
    (SELECT A.user_id AS user_id , A.id AS id , A.title AS title , A.url AS url , B.text AS text ,B.file AS file , B.created_at AS created_at ,B.updated_at AS updated_at FROM (SELECT user_id,id,title,url FROM discussions) AS A INNER JOIN ( SELECT discussion_id, text, file, created_at, updated_at FROM posts WHERE id IN ( SELECT MAX(id) FROM posts GROUP BY discussion_id )) AS B ON A.id=B.discussion_id) ORDER BY created_at DESC;
    SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `personal_discussions`;
SQL;
    }
}
