<?php

namespace iris\contract;

interface TaskHandler
{
    public static function handle(array $data, int $task_id, int $from_id);
}