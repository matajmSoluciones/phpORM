<?php
namespace phpORM\Fields;

class StringField extends BaseField{
    protected $column_type = "VARCHAR";
    protected $column_size = 180;
}