# Shipment cost

You can check shipment cost event before shipment is created.

Please use [ShipmentWizard](wizard.md) and set all of required options.
Let's assume you have prepared wizard handled by `$wizard` variable.

## FROM WIZARD

```php
$cost = $wizard->getCost();
```

`$cost` will return total cost with fuel surcharge (nett).

## FROM STORED SHIPMENT

```php
use xGrz\Dhl24\Facades\DHL24;

$cost = DHL24::wizard(DHLShipment)->getCost();
```

`$cost` will return total cost with fuel surcharge (nett).

## DETAILED COST

You can get cost details by a little harder way.

PREPARE WIZARD as mention in [ShipmentWizard](wizard.md) docs.

```php
use xGrz\Dhl24\Facades\DHL24;
DHL24::wizard()
    // -> ...
```

or open existing shipment in Wizard:

```php
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLShipment;

DHL24::wizard(DHLShipment::first());
```

Call action:

```php
$cost = (new \xGrz\Dhl24\Actions\Cost());
```

Now you can check some additional cost information on `$cost` object:

* `$cost->basePrice()` (float) cost without fuel surcharge (nett)
* `$cost->price()` (float) total cost (nett) with fuel surcharge
* `$cost->fuelSurcharge()` (float) fuel surcharge (amount)
* `$cost->fuelSurchargePercent()` (float) fuel surcharge (percent)



