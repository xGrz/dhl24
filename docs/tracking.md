# TRACKING SHIPMENTS
Tracking is locally stored for each shipment. Once tracking info is fetched, package store it locally. 


## TRACK ALL (queue)
If you want to update tracking information please run:

```php
\xGrz\Dhl24\Facades\DHL24::trackAllShipments(bool $shouldDispatchJob = true));
```
When no parameter is provided method will dispatch jobs (recommended). If you pass `false` tracking will be executed immediately without queue.

> You have to configure queue or this method will be risky to run (timeout of PHP script may occur when you have a lot of shipments in transport).
Queue approach is recommended.

___

## TRACK SINGLE SHIPMENT

```php
\xGrz\Dhl24\Facades\DHL24::trackShipment($shipment, $shouldDispatchJob = true);
```
As a `shipment` you can provide `DHLSHipment` model, shipment number or `DHLShipment->id`.
`shouldDispatchJob` (default: true) by default is putting job into queue. If you want to process job without touching queue set to `false`.

Shipment should exist in your system. You can't track shipments created outside of this package.
___

## Automatic shipment tracking

### Laravel <11

In your `app/Console/Kerner.php` file add following lines in `schedule` method:
```php
protected function schedule(Schedule $schedule): void
    {
        $schedule
            ->call(fn() => DHL24::trackAllShipments())
            ->name('DHL24 | Shipment tracking')
            ->everyMinute();
    }
```

Fill free to change schedule configuration (name, interval).
