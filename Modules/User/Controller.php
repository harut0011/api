<?php

namespace Modules\User;

use Modules\User\Models\Model;
use Modules\User\Models\Sessions;
use System\BaseController;
use System\Exceptions\ExcAuth;
use System\Exceptions\ExcValidation;

class Controller extends BaseController
{
    protected Model $model;
    protected Sessions $sessions;

    public function __construct()
    {
        parent::__construct();
        $this->model = Model::getInstance();
        $this->sessions = Sessions::getInstance();
    }

    public function signUp(): int
    {
        if (!$this->checkAccess()) {
            http_response_code(409);
            echo json_encode(['error' => 'User already authorized']);
            exit();
        }
        
        $fields = json_decode(file_get_contents('php://input'), true);
        $fields['password'] = password_hash($fields['password'], PASSWORD_BCRYPT);
        try {
            return $this->model->add($fields);
        } catch (ExcValidation $e) {
            http_response_code(422);
            echo json_encode(['errors' => $e->getErrorsList()]);
            exit();
        }
    }

    public function signIn(): void
    {
        if ($this->checkAccess()) {
            http_response_code(409);
            echo json_encode(['error' => 'User already authorized']);
            exit();
        }
        
        $errors = [];
        
        $fields = json_decode(file_get_contents('php://input'), true);
        try {
            $user = $this->model->getByLogin($fields['login']);

            if (!password_verify($fields['password'], $user['password'])) {
                $errors[] = 'Incorrect password';
                http_response_code(401);
                echo json_encode(['errors' => $errors]);
                exit();
            }

            $token = mb_substr(bin2hex(random_bytes(128)), 0, 128);
            $this->sessions->add(['id_user' => $user['id_user'], 'token' => $token]);
            $_SESSION['token'] = $token;
            $this->user = $user;
            http_response_code(200);
            echo json_encode($user + ['token' => $token]);
            exit();
        } catch (ExcValidation $e) {
            http_response_code(422);
            echo json_encode(['errors' => $e->getErrorsList()]);
            exit();
        }
    }

    public static function getUser(): ?array
    {
        $token = $_SESSION['token'] ?? null;

        if ($token === null) return null;

        $session = Sessions::getInstance()->getByToken($token);
        
        return Model::getInstance()->one($session['id_user']);
    }
}