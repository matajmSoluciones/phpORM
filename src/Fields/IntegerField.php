<?php
namespace phpORM\Fields;

class IntegerField extends BaseField{
    protected $column_type = "INT";
    protected $column_size = 8;
}