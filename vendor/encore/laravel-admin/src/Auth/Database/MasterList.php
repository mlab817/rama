<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterList extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = ['region_id', 'consortium', 'operator', 'plate_no', 'fleet_no', 'type', 'route_code', 'remarks'];

    public static $vehicleTypes = ['Provincial Bus', 'City Bus', 'MPUJ', 'TOURIST VAN', 'TUVE', 'PUB', 'MUVE', 'MB', 'TPUJ', 'Net - TUVE', 'Net - TPUJ', 'Net - MPUJ', 'TOURIST BUS'];
    public static $consortiumTypes = ['ESP', 'MMC', 'N/A'];
    public static $remarkTypes = ['Regional', 'Intra Regional', 'Inter Regional', 'N/A'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.puv_details_table'));

        parent::__construct($attributes);
    }

    /**
     * A Masterlist has station.
     *
     * @return BelongsTo
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(config('admin.database.stations_model'));
    }

    /**
     * A Masterlist has vehicle.
     *
     * @return BelongsTo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(config('admin.database.vehicle_inventory_model'),'plate_no','plate_no');
    }

    /**
     * A Masterlist has user.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('admin.database.users_model'));
    }
}
