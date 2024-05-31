# Shipment wizard
<!-- TOC -->
* [Shipment wizard](#shipment-wizard)
  * [Add Shipper data](#add-shipper-data)
  * [Add Receiver data](#add-receiver-data)
  * [Add items to shipment](#add-items-to-shipment)
  * [Shipment services (required)](#shipment-services-required)
    * [Shipment content](#shipment-content)
  * [Shipment services (optional)](#shipment-services-optional)
    * [SHIPMENT TYPE](#shipment-type)
    * [SHIPMENT DATE](#shipment-date)
    * [SHIPMENT VALUE](#shipment-value)
    * [COLLECT ON DELIVERY (COD)](#collect-on-delivery-cod)
    * [SHIPMENT REFERENCE](#shipment-reference)
    * [COST CENTER (MPK)](#cost-center-mpk)
    * [COMMENT](#comment)
    * [SPECIAL METHODS](#special-methods)
      * [SELF COLLECT](#self-collect)
      * [RETURN ON DELIVERY (ROD)](#return-on-delivery-rod)
      * [PROOF OF DELIVERY](#proof-of-delivery)
      * [PRE DELIVERY INFORMATION (paid phone service)](#pre-delivery-information-paid-phone-service)
      * [PRE AVISO (email service)](#pre-aviso-email-service)
      * [SATURDAY PICKUP (if available)](#saturday-pickup-if-available)
      * [DELIVERY ON SATURDAY (if available)](#delivery-on-saturday-if-available)
* [SEND SHIPMENT TO DHL SERVERS](#send-shipment-to-dhl-servers)
<!-- TOC -->

We provide shipment wizard. This is the best way to create shipment.

First, we have to init wizard by facade method:

```php
$wizard = \xGrz\Dhl24\Facades\DHL24::wizard();
```

> All wizard methods are chainable.

___

## Add Shipper data

You can automate shipper data fill by creating middle class that fill all shipper data. By design, you should fill
shipper data manually for each shipment.

```php
$wizard
    ->shipperName('ACME Corp Ltd.')
    ->shipperPostalCode('02-777')
    ->shipperCity('Otwock')
    ->shipperStreet('Warszawska')
    ->shipperHouseNumber('102/20')
    ->shipperContactPerson('John Rambo')
    ->shipperContactEmail('john.rambo@example.com')
    ->shipperContactPhone('504094400');
```

> setting `shipperContact*` is optional.

___ 

## Add Receiver data

```php
$wizard
    ->receiverName('Microsoft Corp Ltd.')
    ->receiverPostalCode('03888', 'DE')
    ->receiverCity('Lomza')
    ->receiverStreet('Gdańska')
    ->receiverHouseNumber('101/1')
    ->receiverContactPerson('Johnny Travolta')
    ->receiverContactEmail('j.t@example.com')
    ->receiverContactPhone('677987787');
```

> When setting postal code you can optionally pass second parameter with receiver country code ('DE' in example). This
> parameter is optional for shipments delivered in Poland. Package will set default 'PL' as receiver country.
___

## Add items to shipment

There are predefined shipments type like `ENVELOPE`, `PACKAGE` or `PALLET`.
We are storing shipment item type in `xGrz\Dhl24\Enums\DHLShipmentItemType::class`.

* `ENVELOPE` item type requires only `quantity` parameter, however:
* `PACKAGE` and `PALETTE` item type requires more details about item like `weight`, diamentions or `nonStandard`.

```php
$wizard
    ->addItem(\xGrz\Dhl24\Enums\DHLShipmentItemType::ENVELOPE, $quantity)
    ->addItem(\xGrz\Dhl24\Enums\DHLShipmentItemType::PACKAGE, $quantity, $weight, $width, $height, $length, $nonStandard)
```

First parameter is always item type as enum mentioned above.

* `quantity` - (integer) quantity of items
* `weight` - (integer|float) weight of item
* `width`, `height`, `length` - (integer) diamentions in centimeters
* `nonStandard` - (bool) optional (default: false)

___

## Shipment services (required)

### Shipment content

DHL requires shipment content description. No default content is set.
Read about our [content manager (helper) here](content-suggestions.md).

```php
$wizard->content('Electronics');
```

___

## Shipment services (optional)

### SHIPMENT TYPE

Shipment type defines which DHL product you would like to use.
For now, you can set one of:

* DOMESTIC (standard DHL shipment),
* DOMESTIC09 (delivery to 9:00am next business day),
* DOMESTIC12 (delivery to 12:00am NBD)
* PREMIUM (DHL guarantee NBD delivery)
* EVENING_DELIVERY (delivery in evening)

> We do not provide foreign countries delivery right now. This will be added in the future.

```php
$wizard->shipmentType(xGrz\Dhl24\Enums\DHLDomesticShipmentType::DOMESTIC);
```

By default, wizard will assume DOMESTIC (standard shipment).
___

### SHIPMENT DATE

Set shipping date.

```php
$wizard->shipmentDate(Carbon $date)
```

As a `date` parameter please provide carbon object. We took only date from this object, so you don't have to set any
hours.

### SHIPMENT VALUE

If you want to provide shipment value (for insurance purposes) just add value to wizard:

```php
$wizard->shipmentValue(int|float 2000);
```

By the law regulations, all shipments (without collect on delivery) are insured up to 500PLN without charging you.
In that case we provided intelligent cost saver. It can be configured in `dhl24.php` file.
If you set `intelligent_cost_saver` to `false` insurance charge will be taken even if shipment value is below 500PLN.
Maximum cost saver value is configurable too.

> Intelligent cost saver is not applied when you set collect on delivery (COD). Insurance value will be set to collect
> on delivery value automatically.
> If you pass higher shipment value (in compare with COD) higher value will be set as insurance.

### COLLECT ON DELIVERY (COD)

```php
$wizard->collectOnDelivery(2500, 'INV F/102/2010');
```

* `amount` (required) should be integer or float.
* `reference` (optional string) you can pass COD reference for ex. invoice number.

If you pass reference to your COD it will be copied to shipment reference (if not set earlier).

If COD amount is higher than shipment value, shipment value will be overwritten by COD value. 

### SHIPMENT REFERENCE

You can pass reference to shipment. It will be shown on label.
This reference is not COD reference equal. You can pass here for example order number.

```php
$wizard->reference('ORDER 111');
```
When you passed COD without reference shipment reference text will be applied as COD reference too.


### COST CENTER (MPK)
Please read [cost center docs](cost-center.md) witch describes this feature.
```php
$wizard->costCenter(DHLCostCenter);
```
Please pass `DHLCostCenter` model as a parameter.  

### COMMENT
You can add comment (visible on label) to shipment

```php
$wizard->comment('Please call customer before delivery');
```

### SPECIAL METHODS

#### SELF COLLECT
Your package will be waiting in terminal for pickup by customer
```php
$wizard->selfCollect();
```
#### RETURN ON DELIVERY (ROD)
```php
$wizard->returnOnDelivery(string $rod_reference);
```

#### PROOF OF DELIVERY
```php
$wizard->proofOfDelviery();
```

#### PRE DELIVERY INFORMATION (paid phone service)
```php
$wizard->preDeliveryInformation();
```

#### PRE AVISO (email service)
```php
$wizard->preAviso();
```

#### SATURDAY PICKUP (if available)
```php
$wizard->saturdayPickup();
```

#### DELIVERY ON SATURDAY (if available)
```php
$wizard->saturdayDelivery();
```
___

# SEND SHIPMENT TO DHL SERVERS
At this point shipment is not stored in local database.

It is automatically stored when you send shipment to API without errors (DHL24Excetion);

```php
$shipmentNumber = $wizard->create();
```

When shipment is accepted by API DHLShipment will be stored in database with shipment number assigned by API. Event ShipmentCreatedEvent is dispatched.
One of listeners of ShipmentCreatedEvent is GetShipmentLabelListener. It will download label in the background.

> As a result of `create` method shipment number will be returned.
