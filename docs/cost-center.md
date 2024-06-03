# COST CENTERS

>This is optional, so you don't have to use it.

Package can manage costs centers for accountants. You can have billing information divided into sections on your invoices.

> Package provides cost center manager. You can divide your shipments by unlimited number of cost centers.
> DHL invoice attachment divides shipments by those cost centers.
___
## LISTING (query)

`query` method applies sorted scope (sorting by name). If cost center is marked as default it will be always on first place in results.

### Active listing
```php
\xGrz\Dhl24\Facades\DHL24::costsCenter()->query()->get();
```
___

### Soft deleted listing
```php
\xGrz\Dhl24\Facades\DHL24::costsCenter()->query()->onlyTrashed()->get();
```
___

### Active and soft deleted listing
```php
\xGrz\Dhl24\Facades\DHL24::costsCenter()->query()->withTrashed()->paginate();
```
In this example we get paginated list except listing all models.
___

## ADD

```php
\xGrz\Dhl24\Facades\DHL24::costsCenter()->add($name);
```
Cost center `name` must be unique across all database rows (including soft deleted). If name exists DHL24Exception will be thrown.
___


## RENAME
```php
\xGrz\Dhl24\Facades\DHL24::costsCenter($center)->rename($name);
```
`center` - you can provide DHLCostCenter model or id

`name` - please provide name to update cost center

Cost center `name` must be unique across all database rows (including soft deleted). If name exists DHL24Exception will be thrown.
___

## DEFAULT
```php
\xGrz\Dhl24\Facades\DHL24::costsCenter($center)->setDefault();
```
`center` - you can provide DHLCostCenter model or id

Only one costs center can be default. 
___

## DELETE

```php
\xGrz\Dhl24\Facades\DHL24::costsCenter($center)->detele();
```
`center` - you can provide DHLCostCenter model or id

If your cost center is used by shipments (assigned to shipment) it will be soft deleted to hold your history.
When cost center is not assigned to any shipment permanent delete will be executed.
___

## RESTORE
```php
\xGrz\Dhl24\Facades\DHL24::costCenter($center)->restore();
```
`center` - you can provide DHLCostCenter model or id



