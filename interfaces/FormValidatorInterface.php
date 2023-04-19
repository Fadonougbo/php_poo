<?php

namespace Interfaces;

interface FormValidatorInterface
{
    public function required(string ...$name):self;

    public function lengthMin(array $arr):self;

    public function dateFormat(array $arr):self;

    public function slug(string ...$names):self;

    public function validate():bool|array;
}


?>