<?php

namespace app\index\controller;

use iris\Config;
use iris\Controller;
use iris\datasource\Db;
use iris\datasource\Mysql;
use iris\datasource\Tx;
use iris\Pool;

class User extends Controller
{
    public function getUserInfo()
    {
        $user = null;
        Db::startTrans(function (Tx $tx) {
            $user = $tx->query("select * from user where id = ?", 2);
            println(json_encode($user));
            $tx->insert("insert into user(username, create_at) values(?,?)", "thomas", date("Y-m-d H:i:s") );
            $user = $tx->query("select * from user");
            println(json_encode($user));
            return true;
        });
        return $this->json($user);
    }

    public function updateUser()
    {
       try {
           $id = 1;
           $oldUserInfo = Db::query("select * from user", $id);
           $rows = Db::delete("delete from user where id = ?", $id);
           $newUserInfo  = Db::query("select * from user", $id);

           return $this->json([
               'rows' => $rows,
               'data' => [
                   'old' => $oldUserInfo,
                   'new' => $newUserInfo
               ]
           ]);
       } catch (\Exception $exception) {
           return $exception->getMessage();
       }
    }
}
