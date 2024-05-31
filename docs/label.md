# Shipment label

DHL API allows label in different format (LP, BLP, LBLP, ZBLP, ZBLP300).
Those formats are defined in enum `xGrz\Dhl24\Enums\DHLLabelType:class`.

* LP: waybill (PDF, A4 format with shipment confirmation for sender)
* BLP: label (PDF, 10x15cm)
* LBLP: label (PDF A5 format on A4 page)
* ZBLP: label for Zebra printers (ZLP format)
* ZBLP300: label for Zebra printers (ZLP format) 300dpi

You can set default label type in you app config directory `config\dhl24.php` in `labels` section `defaultType`

When shipment is created (sent to API) our package dispatches `xGrz\Dhl24\Events\ShipmentCreatedEvent`.
By default, we provided listener `GetShipmentLabelListener` with is downloading label.

> Labels _will not be downloaded_ when you set `disk` to false in config file in `label->disk` section. 
In that case labels can be accessed only on-the-fly from DHL servers.

You can customize your storage `disk` and `directory` in config file.

If label is downloaded and stored you have unlimited time to this file.
___
# How to download label?

## Direct ask for label

```php
use xGrz\Dhl24\Enums\DHLLabelType;
use xGrz\Dhl24\Facades\DHL24;

DHL24::label($shipment, DHLLabelType::BLP);
```
`shipment` argument can be `DHLShipment` model, `DHLShipment->number` or `DHLShipment->id`.
`type` is optional parameter. By default, it will fetch type described in your config file, however you can pass enum `xGrz\Dhl24\Enums\DHLLabelType` to get different label type.

When label is stored locally you will get local copy.

If you want return pdf label download response you should add `->download()` method like so:
```php
use xGrz\Dhl24\Enums\DHLLabelType;
use xGrz\Dhl24\Facades\DHL24;

return DHL24::label($shipment, DHLLabelType::BLP)->download();
```



