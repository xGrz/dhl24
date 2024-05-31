# SHIPMENT LIST (by API)

```php
xGrz\Dhl24\Facades\DHL24::dhlShipments($from, $to, $page): Collection
```
Fetches all shipments from DHL system.
`from` and `to` parameters are optional. You should pass Carbon object if you want to set date limits. Default values are set to today so you will get list of today created shipments.
As API limiting results to 100, you can use `page` parameter to fetch more data (default set to 1)
___
