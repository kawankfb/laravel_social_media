<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePostsJoinedFollowedDiscussionsView extends Migration
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
        DB::statement($this->dropTemp2View());
        DB::statement($this->dropTemp1View());
        DB::statement($this->dropTemp0View());

        
        DB::statement($this->createTemp0View());
        DB::statement($this->createTemp1View());
        DB::statement($this->createTemp2View());
        DB::statement($this->createFinalView());
    }
   
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement($this->dropView());
        DB::statement($this->dropFinalView());
        DB::statement($this->dropTemp2View());
        DB::statement($this->dropTemp1View());
        DB::statement($this->dropTemp0View());

    }
   
    private function createView(): string
    {
        return <<<SQL
CREATE VIEW `last_messages` AS
SELECT A.user_id AS user_id , A.discussion_id AS id , A.title AS title , A.url AS url , B.text AS text ,B.file AS file , B.created_at AS created_at FROM ( (SELECT user_id,discussion_id,title,url FROM followed_discussions AS C INNER JOIN (SELECT id, title, url FROM discussions) AS D ON C.discussion_id=D.id)) AS A INNER JOIN ( SELECT discussion_id, text, file, created_at FROM posts WHERE id IN ( SELECT MAX(id) FROM posts GROUP BY discussion_id )) AS B ON A.discussion_id=B.discussion_id LIMIT 0, 25
SQL;
    }

    private function dropView(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `last_messages`;
SQL;
    }


    


    private function createTemp0View(): string
    {
        return <<<SQL
CREATE VIEW `temp0` AS
SELECT id, title, url FROM discussions
SQL;
    }

    private function dropTemp0View(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `temp0`;
SQL;
    }

    private function createTemp1View(): string
    {
        return <<<SQL
CREATE VIEW `temp1` AS
SELECT user_id,discussion_id,title,url FROM followed_discussions AS C INNER JOIN temp0 AS D ON C.discussion_id=D.id
SQL;
    }

    private function dropTemp1View(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `temp1`;
SQL;
    }


    private function createTemp2View(): string
    {
        return <<<SQL
CREATE VIEW `temp2` AS
SELECT discussion_id, text, file, created_at FROM posts WHERE id IN ( SELECT MAX(id) FROM posts GROUP BY discussion_id )
SQL;
    }

    private function dropTemp2View(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `temp2`;
SQL;
    }




    private function createFinalView(): string
    {
        return <<<SQL
CREATE VIEW `last_messages` AS
SELECT A.user_id AS user_id , A.discussion_id AS id , A.title AS title , A.url AS url , B.text AS text ,B.file AS file , B.created_at AS created_at FROM temp1 AS A INNER JOIN temp2 AS B ON A.discussion_id=B.discussion_id LIMIT 0, 25
SQL;
    }

    private function dropFinalView(): string
    {
        return <<<SQL
DROP VIEW IF EXISTS `last_messages`;
SQL;
    }
}
