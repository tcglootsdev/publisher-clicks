<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Utils;

class User extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = ['username', 'password', 'role'];

    public static function getAvailableFields($client, $role, $op)
    {
        $mapRolesToFields = [
            'web' => [
                'id' => [
                    'r' => ['admin'],
                ],
                'username' => [
                    'r' => ['admin'],
                ],
                'role' => [
                    'r' => ['admin'],
                ],
                'created_at' => [
                    'r' => ['admin'],
                ],
            ]
        ];
        return Utils::getAvailableFields($mapRolesToFields, $client, $role, $op);
    }

    public static function getConditionsForAvailableRows($client, $user): array
    {
        if ($client === 'web') {
            if ($user->role === 'admin') {
                return [['role', '!=', 'admin']];
            }
        }
        return [false];
    }
}
