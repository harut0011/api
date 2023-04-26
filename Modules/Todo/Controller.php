<?php

namespace Modules\Todo;

use System\BaseController;
use System\BaseModel;
use System\Exceptions\Exc404;
use System\Exceptions\ExcValidation;

class Controller extends BaseController
{
    protected BaseModel $model;

    public function __construct()
    {
        $this->model = Model::getInstance();
    }
    
    public function index(): ?array
    {
        return $this->model->all();
    }

    public function one(): array
    {
        try {
            return $this->model->one((int)$this->params['id']);
        } catch (Exc404 $e) {
            http_response_code(404);
            echo json_encode(['message' => $e->getMessage()]);
            exit();
        }
    }

    public function add(): int
    {
        $fields = json_decode(file_get_contents('php://input'), true);
        try {
            return $this->model->add($fields);
        } catch (ExcValidation $e) {
            http_response_code(422);
            echo json_encode(['errors' => $e->getErrorsList()]);
            exit();
        }
    }
}