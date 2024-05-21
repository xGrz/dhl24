# Laravel package for DHL24.PL integration
DHL24.PL Laravel integration


## Shipment tracking
If you want to update tracking information please run
```php
xGrz\Dhl24\Facades\DHL24::updateShipmentTracking();
```
This method dispatches jobs for updating tracking for packages in transport.
As output, you will get integer with count of tracked shipments.

This method dispatches jobs so you have to run your queue.
