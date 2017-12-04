<?php
namespace phpORM\Fields;

class UniqueKey extends ConstraintField{
    protected $key_type = "UNIQUE";
}