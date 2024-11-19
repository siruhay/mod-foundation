<?php

namespace Module\Foundation\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class FoundationRegency extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'platform';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reference_regencies';

    /**
     * The roles variable
     *
     * @var array
     */
    protected $roles = ['foundation-regency'];

    /**
     * The model store method
     *
     * @param Request $request
     * @return void
     */
    public static function storeRecord(Request $request)
    {
        //
    }

    /**
     * The model update method
     *
     * @param Request $request
     * @param [type] $model
     * @return void
     */
    public static function updateRecord(Request $request, $model)
    {
        //
    }

    /**
     * The model delete method
     *
     * @param [type] $model
     * @return void
     */
    public static function deleteRecord($model)
    {
        //
    }

    /**
     * The model restore method
     *
     * @param [type] $model
     * @return void
     */
    public static function restoreRecord($model)
    {
        //
    }

    /**
     * The model destroy method
     *
     * @param [type] $model
     * @return void
     */
    public static function destroyRecord($model)
    {
        //
    }
}
