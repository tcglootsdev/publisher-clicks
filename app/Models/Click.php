<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Utils;

class Click extends Model
{
    use SoftDeletes;

    protected $table = 'clicks';

    protected $fillable = ['user_id'];

    public function publisher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getAvailableFields($client, $role, $op)
    {
        $mapRolesToFields = [
            'web' => [
                'id' => [
                    'r' => ['admin'],
                ],
                'user_id' => [
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
                return [true];
            }
        }
        return [false];
    }
}
