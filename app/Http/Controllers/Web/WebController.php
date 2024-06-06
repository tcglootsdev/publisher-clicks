<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Utils;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WebController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private mixed $model = null;
    private mixed $validator = null;

    public function __construct($model, $validator = null, $options = [])
    {
        $this->model = $model;
        $this->validator = $validator;
        $fileOptions = [
            'modelName' => strtolower(class_basename($model)),
            'files' => !empty($options['files']) ? $options['files'] : []
        ];
        $this->middleware('fileupload:' . serialize($fileOptions))->only('post');
    }

    public function get(Request $request): JsonResponse
    {
        $user = Auth::user();

        /**
         * =================================
         * 1. Get Available Fields
         * =================================
         */
        $avFields = $this->model::getAvailableFields('web', $user->role ?? 'guest', 'r');

        /**
         * =================================
         * 2. Request Validation
         * =================================
         */
        // Relationships
        $relationshipsForStructure = $request->query('with');

        // Search Option
        $searchKey = $request->query('searchKey');
        $searchValue = $request->query('searchValue');

        // Sort Option
        $sortKey = $request->query('sortKey');
        $sortArrow = $request->query('sortArrow');

        // Pagination Option
        $pageSize = intval($request->query('pageSize'));
        $pageIndex = intval($request->query('pageIndex'));

        $conditions = $this->model::getConditionsForAvailableRows('web', $user);
        $conditions = array_merge($conditions, $this->getAdditionalConditions($request));

        /**
         * =================================
         * 3. Get
         * =================================
         */
        list($total, $data) = $this->getData(
            model: $this->model,
            avFields: $avFields,
            conditions: $conditions,
            searchKey: $searchKey,
            searchValue: $searchValue,
            sortKey: $sortKey,
            sortArrow: $sortArrow,
            pageSize: $pageSize,
            pageIndex: $pageIndex,
            relationshipsForStructure: $relationshipsForStructure,
        );

        $error = $this->afterGet($request, $data);
        if ($error) return Utils::responseJsonError($error);

        return Utils::responseJsonData(is_null($total) ? $data : [
            'data' => $data,
            'total' => $total
        ]);
    }

    public function getById(Request $request, $id): JsonResponse
    {
        /**
         * =================================
         * 1. Request Validation
         * =================================
         */
        $error = $this->validateId($id);
        if ($error) {
            return Utils::responseJsonError($error);
        }

        /**
         * =================================
         * 2. Get Available Fields
         * =================================
         */
        $avFields = $this->model::getAvailableFields('web', Auth::user()->role ?? 'guest', 'r');

        $relationshipsForStructure = $request->query('with');

        $error = $this->beforeGetById($id);
        if ($error) {
            return Utils::responseJsonError($error);
        }

        /**
         * =================================
         * 3. Get User
         * =================================
         */
        $data = $this->model::select($avFields)
            ->with($this->getRelationshipsForData($this->getRelationshipsStructure($relationshipsForStructure)))
            ->where(is_array($id) ? $id : ['id' => $id])
            ->first();
        if (!$data) {
            return Utils::responseJsonError(trans('invalid_' . strtolower(class_basename($this->model)) . '_id'));
        }

        $error = $this->afterGetById($data);
        if ($error) {
            return Utils::responseJsonError($error);
        }

        return Utils::responseJsonData($data);
    }

    public function getRelatedDataById(Request $request, $id, $model, $foreignKey, $hasOne = false): JsonResponse
    {
        /**
         * =================================
         * 1. Request Validation
         * =================================
         */
        $error = $this->validateId($id);
        if ($error) {
            return Utils::responseJsonError($error);
        }

        if (!$this->checkIfAvailableRowById($id)) {
            return Utils::responseJsonError(trans('access_denied'));
        }

        /**
         * =================================
         * 2. Get Available Fields
         * =================================
         */
        $avFields = $model::getAvailableFields('web', Auth::user()->role ?? 'guest', 'r');

        if (!$hasOne) {
            // Search Option
            $searchKey = $request->query('searchKey');
            $searchValue = $request->query('searchValue');

            // Sort Option
            $sortKey = $request->query('sortKey');
            $sortArrow = $request->query('sortArrow');

            // Pagination Option
            $pageSize = intval($request->query('pageSize'));
            $pageIndex = intval($request->query('pageIndex'));
        }

        $conditions = $model::getConditionsForAvailableRows('web', Auth::user());
        $conditions = $this->getAdditionalConditionsForRelatedData($model, $conditions);
        $conditions[] = [$foreignKey, $id];

        /**
         * =================================
         * 3. Get
         * =================================
         */
        list($total, $data) = $this->getData(
            model: $model,
            avFields: $avFields,
            conditions: $conditions,
            searchKey: $searchKey ?? null,
            searchValue: $searchValue ?? null,
            sortKey: $sortKey ?? null,
            sortArrow: $sortArrow ?? null,
            pageSize: $pageSize ?? null,
            pageIndex: $pageIndex ?? null,
        );
        if ($hasOne) {
            $data = $data->first();
        }

        $error = $this->afterGetRelatedDataById($model, $data);
        if ($error) return Utils::responseJsonError($error);

        return Utils::responseJsonData((is_null($total) || $hasOne) ? $data : [
            'data' => $data,
            'total' => $total
        ]);
    }

    public function post(Request $request): JsonResponse
    {
        $body = $request->all();

        if (!isset($body['id'])) {
            // Create
            /**
             * =================================
             * 1. Get Available Fields
             * =================================
             */
            $avFields = $this->model::getAvailableFields('web', Auth::user()->role ?? 'guest', 'c');

            /**
             * =================================
             * 2. Request Validation
             * =================================
             */
            if (isset($this->validator)) {
                $error = (new ($this->validator)())->validate($body, $avFields, 'c');
                if ($error) return Utils::responseJsonError($error);
            }

            /**
             * =================================
             * 3. Create
             * =================================
             */
            $data = Utils::onlyKeysInArray($body, $avFields);
            $this->fillDataForCreate($data);

            DB::beginTransaction();

            $row = $this->model::create($data);
            if (!$row) {
                DB::rollBack();
                return Utils::responseJsonError(trans('internal_error'));
            }

            if (!$this->checkIfRowCanBeCreated($row)) {
                DB::rollBack();
                return Utils::responseJsonError(trans('access_denied'));
            }

            $error = $this->doAdditionalActionsAfterCreate($row);
            if ($error) {
                DB::rollBack();
                return Utils::responseJsonError($error);
            }

            DB::commit();

            return Utils::responseJsonData(['id' => $row->id]);
        } else {
            // Update
            /**
             * =================================
             * 1. Get Available Fields
             * =================================
             */
            $avFields = $this->model::getAvailableFields('web', Auth::user()->role ?? 'guest', 'u');

            /**
             * =================================
             * 2. Request Validation
             * =================================
             */
            if (isset($this->validator)) {
                $error = (new ($this->validator)())->validate($body, $avFields, 'u');
                if ($error) return Utils::responseJsonError($error);
            }

            $id = $body['id'];

            if (!$this->checkIfRowCanBeUpdatedById($id)) {
                return Utils::responseJsonError(trans('access_denied'));
            }

            /**
             * =================================
             * 3. Update
             * =================================
             */
            $data = Utils::onlyKeysInArray($body, $avFields);
            $this->fillDataForUpdate($data);

            DB::beginTransaction();

            $oldRow = $this->model::find($id);

            $result = $this->model::find($id)->update($data);
            if (!$result) {
                DB::rollBack();
                return Utils::responseJsonError(trans('internal_error'));
            }

            $newRow = $this->model::find($id);

            if (!$this->checkIfRowCanBeCreated($newRow)) {
                DB::rollBack();
                return Utils::responseJsonError(trans('access_denied'));
            }

            $error = $this->doAdditionalActionsAfterUpdate($id, $oldRow, $newRow);
            if ($error) {
                DB::rollBack();
                return Utils::responseJsonError($error);
            }

            DB::commit();

            return Utils::responseJsonData();
        }
    }

    public function delete(Request $request): JsonResponse
    {
        /**
         * =================================
         * 1. Request Validation
         * =================================
         */
        $id = $request->all()['id'] ?? null;

        $error = $this->validateId($id);
        if ($error) {
            return Utils::responseJsonError($error);
        }

        $error = $this->beforeDelete($id);
        if ($error) return Utils::responseJsonError($error);

        /**
         * =================================
         * 2. Delete row with id
         * =================================
         */
        $deleted = $this->model::destroy($id);
        if ($deleted) {
            return Utils::responseJsonData();
        } else {
            return Utils::responseJsonError(trans('invalid_' . strtolower(class_basename($this->model)) . '_id'));
        }
    }

    protected function validateId($id): ?string
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer',
        ], [
            'id.required' => trans('empty_' . strtolower(class_basename($this->model)) . '_id'),
            'id.integer' => trans('invalid_' . strtolower(class_basename($this->model)) . '_id'),
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        if (!$this->model::find($id)) {
            return Utils::responseJsonError(trans('invalid_' . strtolower(class_basename($this->model)) . '_id'));
        }
        return null;
    }

    private function validateSearchOptions($searchKey, $avFields): bool
    {
        $searchKeyType = gettype($searchKey);
        if ($searchKeyType !== 'string' && $searchKeyType !== 'array') {
            return false;
        }
        if ($searchKeyType === 'string' && !in_array($searchKey, $avFields)) {
            return false;
        }
        if ($searchKeyType === 'array') {
            for ($i = 0; $i < count($searchKey); $i++) {
                if (!in_array($searchKey[$i], $avFields)) {
                    array_splice($searchKey, $i, 1);
                    $i--;
                }
            }
            if (count($searchKey) === 0) return false;
        }
        return true;
    }

    private function validateSortOptions($sortKey, $sortArrow, $avFields): bool
    {
        if (gettype($sortKey) !== 'string' || !in_array($sortKey, $avFields)) return false;
        if ($sortArrow !== 'desc' && $sortArrow !== 'asc') return false;
        return true;
    }

    private function validatePaginationOptions($pageSize, $pageIndex): bool
    {
        if ($pageSize < 5 || $pageSize > 100) return false;
        if ($pageIndex < 1) return false;
        return true;
    }

    protected final function getRelationshipsStructure($relationshipsForStructure): ?array
    {
        if (!is_array($relationshipsForStructure)) {
            return [];
        }
        $relationshipsStructure = [];
        for ($i = 0; $i < count($relationshipsForStructure); $i++) {
            if (!is_string($relationshipsForStructure[$i])) {
                continue;
            }
            $relationships = explode('.', $relationshipsForStructure[$i]);
            for ($j = 0; $j < count($relationships); $j++) {
                $currentDeep = &$relationshipsStructure;
                for ($k = 0; $k < $j; $k++) {
                    $currentDeep = &$currentDeep[$relationships[$k]];
                }
                if (!isset($currentDeep[$relationships[$j]])) {
                    $currentDeep[$relationships[$j]] = [];
                }
            }
        }
        return $relationshipsStructure;
    }

    protected final function getRelationshipsForData($relationshipsStructure, $model = null): array {
        $relationshipsForData = [];
        $relationships = array_keys($relationshipsStructure);
        foreach ($relationships as $relationship) {
            if (method_exists($model ?? $this->model, $relationship)) {
                $relatedModel = (new ($model ?? $this->model)())->{$relationship}()->getRelated();
                $relationshipsForData[$relationship] = function ($subquery) use ($relationshipsStructure, $relationship, $relatedModel) {
                    $subquery->select($relatedModel::getAvailableFields('web', Auth::user()->role ?? 'guest', 'r'))
                        ->with($this->getRelationshipsForData($relationshipsStructure[$relationship]))
                        ->where(function ($subquery) use ($relatedModel) {
                            Utils::setConditions2Query($subquery, $relatedModel::getConditionsForAvailableRows('web', Auth::user()));
                        });
                };
            }
        }
        return $relationshipsForData;
    }

    private function getData($model, $avFields, $conditions, $searchKey, $searchValue, $sortKey, $sortArrow, $pageSize, $pageIndex, $relationshipsForStructure = []): array
    {
        $query = $model::select($avFields)
            ->with($this->getRelationshipsForData($this->getRelationshipsStructure($relationshipsForStructure)))
            ->where(function ($subquery) use ($conditions) {
                if ($conditions) {
                    Utils::setConditions2Query($subquery, $conditions);
                }
            })
            ->where(function ($subquery) use ($avFields, $searchValue, $searchKey) {
                if ($this->validateSearchOptions($searchKey, $avFields)) {
                    Utils::setConditions2Query($subquery, [[$searchKey, 'like', '%' . $searchValue . '%']]);
                }
            });
        if ($this->validateSortOptions($sortKey, $sortArrow, $avFields)) {
            $query->orderBy($sortKey, $sortArrow);
        }
        $total = null;
        if ($this->validatePaginationOptions($pageSize, $pageIndex)) {
            $total = $query->count();
            $query->skip($pageSize * ($pageIndex - 1))->take($pageSize);
        }
        $data = $query->get();

        return [$total, $data];
    }

    private function checkIfAvailableRowById($id): bool
    {
        $conditions = $this->model::getConditionsForAvailableRows('web', Auth::user());
        $conditions[] = is_array($id) ? $id : ['id', $id];
        $row = $this->model::where(function ($subquery) use ($conditions) {
            Utils::setConditions2Query($subquery, $conditions);
        })->first();
        return !!$row;
    }

    protected function checkIfRowCanBeCreated($row): bool
    {
        return $this->checkIfAvailableRowById($row->id);
    }

    protected function checkIfRowCanBeUpdatedById($id): bool
    {
        return $this->checkIfAvailableRowById($id);
    }

    protected function getAdditionalConditions(Request $request): array { return []; }

    protected function afterGet(Request $request, &$data){ return null; }

    protected function beforeGetById($id) { return null; }

    protected function afterGetById(&$data){ return null; }

    protected function fillDataForCreate(&$data): void {}

    protected function fillDataForUpdate(&$data): void {}

    protected function doAdditionalActionsAfterCreate($row){ return null; }

    protected function doAdditionalActionsAfterUpdate($id, $oldRow, $newRow): mixed { return null; }

    protected function beforeDelete($id) { return null; }

    protected function getAdditionalConditionsForRelatedData($model, $conditions): array { return $conditions; }

    protected function afterGetRelatedDataById($model, &$data) { return null; }
}
