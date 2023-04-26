<?php

namespace Modules\Todo;

use Modules\_base\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {
        $model = Model::getInstance();
        return $model->all();
    }
}