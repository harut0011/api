<?php

namespace Modules\User\Models;

use System\BaseModel;
use System\Exceptions\ExcAuth;

class Sessions extends BaseModel
{
    protected string $table = 'sessions';
    protected string $primaryKey = 'id_session';
    public static $instance;

    public function getByToken(string $token): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE token = :token";
        $session = $this->db->query($sql, ['token' => $token])->fetch();
        if (!$session) {
            throw new ExcAuth('Session token not found');
        }
        return $session;
    }
}