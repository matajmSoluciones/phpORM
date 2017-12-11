<?php
namespace phpORM\Utils;

abstract class Timezone {
    public static function now(){
        return \date("Y-m-d H:i:s");
    }
    public static function format($time, $format = "Y-m-d H:i:s") {
        $date = \DateTime::createFromFormat($format, $time);
        return $date;
    }
}