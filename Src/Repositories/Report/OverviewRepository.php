<?php

namespace App\Repositories\Report;

use MDCR\Models\User;
use MDCR\Models\Course;
use MDCR\core\Model;


class OverviewRepository
{
    public function run()
    {

        $user = new User();
        $user = $user->getCurrentUser();
        $model = new Course();
        $hasMoreThanOne = $model->exec("select count(p1.id) as total, YEAR(created_at) as year, MONTH(created_at) as month, DAY(created_at) as day, p1.created_at from playedcard p1 where user_id = ? group by YEAR(created_at), MONTH(created_at), DAY(created_at)", [$user['id']]);
        // $hasOnlyOne = $model->exec("select * from (select count(id) as total, YEAR(nextshow) as year, MONTH(nextshow) as month, DAY(nextshow) as day, playedcard.* from playedcard where user_id = ? group by card_id) as t where t.total = 1", [$user['id']]);
        // $resultPlayed = array_merge($hasMoreThanOne, $hasOnlyOne);

        $toPlay = $model->exec("select count(p1.id)as total, YEAR(nextshow) as year, MONTH(nextshow) as month, DAY(nextshow) as day, p1.* from playedcard p1 where user_id = ? and id = (select MAX(p2.id) from playedcard p2 where p1.card_id = p2.card_id limit 1) group by card_id, YEAR(nextshow), MONTH(nextshow), DAY(nextshow)", [$user['id']]);

        return ['played' => $hasMoreThanOne, 'to_play' => $toPlay];
    }
}
