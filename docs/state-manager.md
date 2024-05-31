# SHIPMENT STATE MANAGER

Shipment state manager handles typical states of shipment as predefined states.
We store them in `dhl_statuses` table. 

If DHL provide new status code out package will store it in local db with description.

## GET LIST OF STATUSES

```php
xGrz\Dhl24\Services\DHLTrackingStatusService::getStates();
```
You will get full list of statuses (as eloquent collection).
___

## UPDATE STATUS DESCRIPTION
If default description provided by DHL API is not good enough fill free to add your custom description.

```php
$state = new xGrz\Dhl24\Services\DHLTrackingStatusService($status);
$state->updateDescription('Your description message');
```
As status parameter you have to provide `DHLStatus` model or symbol of this status (for ex. 'DOR').
___

## UPDATE STATUS TYPE

Status type indicate state of delivery (SENT, InDelivery, Delivered, etc.). Each status should have defined type. 
We use those types for dispatching events like ShipmentSent or ShipmentDelivered.

For update type you simply use:

```php
$state = new xGrz\Dhl24\Services\DHLTrackingStatusService($status);
$state->updateType(xGrz\Dhl24\Enums\DHLStatusType::CREATED);
```

Status Types are hardcoded as enum. If you want to get full list of status types (for ex. as option list) use this method:
```php
xGrz\Dhl24\Services\DHLTrackingStatusService::getStatusTypes();
```
This method will return key/value array. Key is numeric value of type, value is label.
Label is customizable with lang file (key dhl::shipment.statusType.***).
