<?php

namespace Modules\Todo;

use System\BaseModel;

class Model extends BaseModel
{
    protected string $table = 'todos';
    protected string $primaryKey = 'id_todo';
    protected array $necessaryFields = ['name', 'description'];
}