<?php

namespace app\common\task;

use iris\contract\TaskHandler;

class SendEmail implements TaskHandler
{

    public static function handle(array $data, int $task_id, int $from_id)
    {
        println("email send to", $data['to']);
        return 'email job finished';
    }
}
