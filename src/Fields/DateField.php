<?php
namespace phpORM\Fields;
use \phpORM\Utils\Timezone;

class DateField extends DateTimeField{
    protected $column_type = "DATE";
    protected $format = "Y-m-d";
    private $sql_format = "Y-m-d";
}