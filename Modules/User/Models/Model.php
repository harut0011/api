<?php

namespace Modules\User\Models;

use System\BaseModel;

class Model extends BaseModel
{
    protected string $table = 'users';
    protected string $primaryKey = 'id_user';

    public function getByLogin(string $login): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE login = :login";
        $user = $this->db->query($sql, ['login' => $login])->fetch();
        return $user ?? null;
    }
}