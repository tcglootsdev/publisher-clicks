<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class Utils {
    //  TODO: In principle, status must be received as a parameter. But it is unnecessary because 200 response are sent for every request
    //  public static function responseJsonData($status, $data = null) {
    //      ... ... ...
    //      return response()->status($status)->json($jsonData);
    //  }
    public static function responseJsonData($data = null): JsonResponse
    {
        $jsonData = [
            'status' => 1,
        ];
        if (isset($data)) {
            $jsonData['data'] = $data;
        }
        return response()->json($jsonData);
    }

    public static function responseJsonError($error = null): JsonResponse
    {
        $jsonData = [
            'status' => 0,
        ];
        if (isset($error)) {
            $jsonData['error'] = $error;
        }
        return response()->json($jsonData);
    }

    public static function onlyKeysInArray($object, $keys): array
    {
        $clearedObject = [];
        foreach ($object as $key => $value) {
            if (in_array($key, $keys)) {
                $clearedObject[$key] = $value;
            }
        }
        return $clearedObject;
    }

    public static function getAvailableFields($mapRolesToFields, $client, $role, $op): array
    {
        $avFields = [];
        if (
            !is_array($mapRolesToFields) ||
            !isset($mapRolesToFields[$client]) ||
            !is_array($mapRolesToFields[$client])
        ) {
            return $avFields;
        }
        foreach($mapRolesToFields[$client] as $field => $mapRolesToOps) {
            if (
                is_array($mapRolesToOps) &&
                isset($mapRolesToOps[$op]) &&
                is_array($mapRolesToOps[$op]) &&
                (
                    in_array('global', $mapRolesToOps[$op]) ||
                    in_array($role, $mapRolesToOps[$op])
                )
            ) {
                $avFields[] = $field;
            }
        }
        return $avFields;
    }

    public static function setConditions2Query(&$query, $conditions, $combineMode = 'and'): void
    {
//        if (is_bool($conditions)) {
//            $query->where($conditions);
//            return;
//        }
        if (is_array($conditions)) {
//            if (array_values($conditions) !== $conditions) {
//                $query->where($conditions);
//                return;
//            }
            $combineFunc = $combineMode === 'or' ? 'orWhere' : 'where';
            foreach ($conditions as $condition) {
                if (is_bool($condition)) {
                    $query->{$combineFunc}(function ($query) use ($condition) {
                        $query->whereRaw($condition ? 'true' : 'false');
                    });
                }
                if (is_array($condition)) {
                    if (array_values($conditions) !== $conditions) {
                        $query->{$combineFunc}(function ($query) use ($conditions, $condition) {
                            $query->where($conditions);
                        });
                    } else if (count($condition) > 3) {
//                        array_pop($condition);
                        $query->{$combineFunc}(function ($query) use ($condition) {
                            self::setConditions2Query($query, $condition, end($condition));
                        });
                    } else {
                        $field = null;
                        $operator = null;
                        $value = null;

                        $field = $condition[0];
                        if (count($condition) === 3) {
                            $operator = $condition[1];
                            $value = $condition[2];
                        } else {
                            $value = $condition[1];
                        }
                        if (is_array($field)) {
                            // For example, [['username', 'fullname'], 'jonatps']. This is typically used when searching.
                            $query->{$combineFunc}(function ($query) use ($value, $field, $operator) {
                                $conditions = self::makeConditions($field, $operator, $value);
                                // TODO: When $field is an array, I think it is very rare for the fields in it to be combined with "and".
                                // So, I assumed that they were always combined with "or" and implemented it that way.
                                self::setConditions2Query($query, $conditions, 'or');
                            });
                        }
                        if (is_array($value)) {
                            // If type of $value is array, $operator is considered as null
                            $query->{$combineFunc}(function ($query) use ($value, $field) {
                                $query->whereIn($field, $value);
                            });
                        } else if (!is_null($operator)) {
                            // TODO: Need to test when $operator is null, I remember $operator seems to be considered as $value when that is null
                            $query->{$combineFunc}($field, $operator, $value);
                        } else {
                            $query->{$combineFunc}($field, $value);
                        }
                    }
                }
            }
        }
    }

    // This functions is used to make conditions with an array as field, for example, [[name, email], 'jonatps']
    public static function makeConditions($field, $operator, $value): array
    {
        $conditions = [];

        if (!is_array($field)) return $conditions;

        foreach ($field as $item) {
            $conditions[] = [$item, $operator, $value];
        }

        return $conditions;
    }
}
