<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log; // Import Log class

class CustomerList extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'customer_id';
    protected $fillable = ['customer_code', 'customer_name', 'customer_telephone', 'customer_age', 'customer_occupation', 'customer_address', 'customer_gender', 'flag'];

    protected $casts = [
        'flag' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($customerList) {
            $customerList->flag = true;
            $customerList->save();
        });

        static::created(function ($customerList) {
            Log::channel('activitylog')->info('Customer List created', ['id' => $customerList->id]);
        });

        static::updated(function ($customerList) {
            Log::channel('activitylog')->info('Customer List updated', ['id' => $customerList->id]);
        });

        static::deleted(function ($customerList) {
            Log::channel('activitylog')->info('Customer List deleted', ['id' => $customerList->id]);
        });
    }

    public static function logCrudOperation($activity, $model)
    {
        $userId = auth()->id();
        $modelName = get_class($model);
        $tableName = $model->getTable();

        // Implement logic to log CRUD operations if needed
    }
}
