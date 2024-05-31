# REPORT (shipping confirmation list)
```php
xGrz\Dhl24\Facades\DHL24::report(Carbon $date);
```
This method fetches shipment list. `date` parameter is optional - if not provided method will assume current data. File is stored automatically in configured disk/path from `dhl24.php` config file.
If your config has disk set to false - file store is unavailable.
> If you chain `->download()` at the end you will receive http response with report download for return direct from controller for example.

> If you chain `->getResponse()` you will get report file details.
> __WARNING!__ Content data is base64_encoded, so you have to use base64_decode($content) to get PDF file contents.
___
