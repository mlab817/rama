<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleInventory extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = ['region_id', 'consortium', 'operator', 'plate_no', 'fleet_no', 'type', 'route_code', 'remarks'];

    public static $vehicleTypes = ['Provincial Bus', 'City Bus', 'MPUJ', 'TOURIST VAN', 'TUVE', 'PUB', 'MUVE', 'MB', 'TPUJ', 'Net - PUB', 'Net - TUVE', 'Net - TPUJ', 'Net - MPUJ', 'TOURIST BUS', 'RESCUE-PUB'];
    public static $consortiumTypes = ['ESP', 'MMC', 'N/A'];
    public static $remarkTypes = ['Regional', 'Intra Regional', 'Inter Regional', 'N/A'];

    public static $statusColors = [
        'Yes'        => 'green',
        'No'         => 'red',
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.vehicle_inventory_table'));

        parent::__construct($attributes);
    }

    /**
     * A Vehicle Inventory has region.
     *
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(config('admin.database.regions_model'));
    }

    /**
     * A Vehicle Inventory has route.
     *
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(config('admin.database.routes_model'),'route_code','code');
    }
}
