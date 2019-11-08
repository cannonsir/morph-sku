<?php

namespace Gtd\Product\Exceptions;

class CategoryNotFoundException extends \Exception
{
    public static function make($category_id)
    {
        return new static("Category with id $category_id was not found");
    }
}