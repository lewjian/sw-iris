<?php
namespace iris\contract;

interface Closer
{
    /**
     * 关闭
     *
     * @return mixed
     */
    public function close();

    /**
     * 是否已过期
     *
     * @param int $expiredIn
     * @return mixed
     */
    public function isExpired(int $expiredIn): bool ;
}
