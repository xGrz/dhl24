# SHIPMENT STATE MANAGER

As shipment state messages provided by API ale not well formatted you can change message displayed in your app.

Shipment state manager handles typical states of shipment as predefined states.
We store them in `dhl_statuses` table. 

If DHL provide new status code out package will store it in local db with description.

## GET LIST OF STATUSES

```php
\xGrz\Dhl24\Facades\DHL24::states()
    ->query()
    ->orderByTypes()
    ->get();
```
You will get full list of statuses (as eloquent collection). `orderByTypes` is scope sorting results by `types` and `names`.
___

## UPDATE STATUS DESCRIPTION
If default description provided by DHL API is not good enough fill free to add your custom description.

```php
\xGrz\Dhl24\Facades\DHL24::states('DOR')->rename('Your description message');
```
As `states` parameter you have to provide `DHLTrackingState` model or symbol of this status (for ex. 'DOR').
___

## UPDATE STATUS TYPE

Status type indicate state of delivery (SENT, InDelivery, Delivered, etc.). Each status should have defined type. 
We use those types for dispatching events like ShipmentSent or ShipmentDelivered.

For update type you simply use:

```php
use xGrz\Dhl24\Enums\DHLStatusType;

\xGrz\Dhl24\Facades\DHL24::states('DOR')->setType(DHLStatusType::CREATED);
```
Status Types are hardcoded as enums. 

If you want to get full list of status types (for ex. as option list) use this method:
```php
\xGrz\Dhl24\Facades\DHL24::states()->getTypeOptions();
```
This method will return key/value array. Key is numeric value of type, value is label.
Label is customizable with lang file (key dhl::shipment.statusType.***).
