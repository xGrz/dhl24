# Laravel package for DHL24.PL integration
DHL24.PL Laravel integration


## Test API call
Test call for API Version. Credentials are not required.
```php
xGrz\Dhl24\Facades\DHL24::apiVersion(): string
```

## Get nearest service points
Get list of nearest service points.

```php
xGrz\Dhl24\Facades\DHL24::servicePoints($postalCode, $radius, $country, $type): Collection
```
Method required only `postalCode`. You can optionally set `radius` (default is 5), `country` (default is 'PL').
Last parameter `type` is enum `xGrz\Dhl24\Enums\ServicePointType`. Use it if you want to filter results by type of service point. Default is null so you will get all types.
Method returns `Collection` so you can use all laravel Collection methods by chaining them. Typical scenario is use `->take(20)` for result limiting.


## Get shipments list

```php
xGrz\Dhl24\Facades\DHL24::myShipments($from, $to, $page): Collection
```
Fetches all shipments from DHL system.
`from` and `to` parameters are optional. You should pass Carbon object if you want to set date limits. Default values are set to today so you will get list of today created shipments.
As API limiting results to 100, you can use `page` parameter to fetch more data (default set to 1)


## Report (shipping confirmation list)
```php
xGrz\Dhl24\Facades\DHL24::report(Carbon $date);
```
This method fetches shipment list. `date` parameter is optional - if not provided method will assume current data. File is stored automatically in configured disk/path from `dhl24.php` config file.
If you config has disk set to false - file store is unavailable.
> If you chain `->download()` at the end you will receive http response with report download for return direct from controller for example.

> If you chain `->getResponse()` you will get report file details. 
> __WARNING!__ Content data is base64_encoded so you have to use base64_decode($content) to get PDF file contents.


## Shipment content
DHL requires to provide shipment contents. 

This package has helper for content suggestions. See [content suggestion helper](docs/content-suggestions.md) docs.





## Shipment tracking
If you want to update tracking information please run
```php
xGrz\Dhl24\Facades\DHL24::updateShipmentTracking();
```
This method dispatches jobs for updating tracking for packages in transport.
As output, you will get integer with count of tracked shipments.

This method dispatches jobs so you have to run your queue.
