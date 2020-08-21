<?php
namespace iris;

class File
{
    /**
     * @param string $filename
     * @param string $msg
     */
    public static function writeTo($filename, $msg)
    {
        $dirname = dirname($filename);
        if (!is_dir($dirname)) {
            mkdir($dirname, 0777, true);
        }
        if (Config::get("log.print_to_console")) {
            println($msg);
        }
        go(function () use($filename, $msg) {
            file_put_contents($filename, $msg . "\n", FILE_APPEND);
        });
    }
}