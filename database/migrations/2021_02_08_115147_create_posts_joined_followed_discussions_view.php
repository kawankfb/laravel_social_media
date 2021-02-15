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
}
