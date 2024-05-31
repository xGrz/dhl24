# SERVICE POINTS
Get list of nearest service points.

```php
xGrz\Dhl24\Facades\DHL24::servicePoints($postalCode, $radius, $country, $type): Collection
```
Method required only `postalCode`. You can optionally set `radius` (default is 5), `country` (default is 'PL').
Last parameter `type` is enum `xGrz\Dhl24\Enums\ServicePointType`. Use it if you want to filter results by type of service point. Default is null so you will get all types.
Method returns `Collection` so you can use all laravel Collection methods by chaining them. Typical scenario is use `->take(20)` for result limiting.
___
