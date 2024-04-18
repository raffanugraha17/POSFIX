<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
Log::channel('activitylog')->info('Data telah diubah oleh User 1');

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'activity', 'module', 'table'];
    
    public static function logCrudOperation($activity, $model)
    {
        // Pengecekan autentikasi sebelum mendapatkan user_id
        $userId = Auth::check() ? Auth::id() : null;
        $modelName = get_class($model);
        $tableName = $model->getTable();

        // Cek apakah model yang dioperasikan bukan ActivityLog
        if ($modelName !== self::class) {
            self::create([
                'user_id' => $userId,
                'activity' => $activity,
                'module' => $modelName,
                'table' => $tableName,
            ]);

            // Gunakan channel activitylog untuk mencatat ke log.txt
            Log::channel('activitylog')->info("CRUD operation: $activity, Model: $modelName, Table: $tableName, User ID: $userId");
        }
    }

    public static function logLoginLogout($activity, $module)
    {
        // Pengecekan autentikasi sebelum mendapatkan user_id
        $userId = Auth::check() ? Auth::id() : null;

        self::create([
            'user_id' => $userId,
            'activity' => $activity,
            'module' => $module,
            'table' => null,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            Log::channel('activitylog')->info('Record baru dibuat', ['model' => self::class, 'id' => $model->id]);
        });

        static::updated(function ($model) {
            Log::channel('activitylog')->info('Record diupdate', ['model' => self::class, 'id' => $model->id]);
        });

        static::deleted(function ($model) {
            Log::channel('activitylog')->info('Record dihapus', ['model' => self::class, 'id' => $model->id]);
        });
    }
}