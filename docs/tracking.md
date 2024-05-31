# TRACKING SHIPMENTS
Tracking is locally stored for each shipment. Once tracking info is fetched, package store it locally. 


## TRACK ALL (queue)
If you want to update tracking information please run:
```php
xGrz\Dhl24\Facades\DHL24::updateShipmentTracking();
```
This method dispatches jobs for updating tracking for packages in transport.
As output, you will get integer with count of tracked shipments.

> You have to configure queue or this method will be risky to run (timeout of PHP script may occur when you have a lot of shipments in transport).
Queue approach is recommended.

> TIP: Add this method to laravel schedule. System will update shipment tracking in background.
___

## TRACK SINGLE SHIPMENT

```php
xGrz\Dhl24\Facades\DHL24::trackShipment($shipment, $shouldDispatchJob = true);
```
As a `shipment` you can provide `DHLSHipment` model, shipment number or `DHLShipment->id`.
`shouldDispatchJob` (default: true) by default is putting job into queue. If you want to process job without touching queue set to `false`.

Shipment should exist in your system. You can't track shipments created outside of this package.
___
