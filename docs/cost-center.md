# COST CENTERS

>This is optional, so you don't have to use it.

Package can manage costs centers for accountants. You can have billing information divided into sections on your invoices.

> Package provides cost center manager. You can divide your shipments by unlimited number of cost centers.
> DHL invoice attachment divides shipments by those cost centers.
___
## LISTING (query)

`query` method return DHLCostsCenter query builder. Typically, you will sort your results by `name` or by `is_default`.
You can use predefined scopes `sortedByNames` on query or `defaultFirst`. If you want to combine them use `defaultFirst` before `sortedByNames` to get default cost center as first on list.

### Active listing
```php
\xGrz\Dhl24\Facades\DHL24::costsCenter()
    ->query()
    ->defaultFirst()
    ->sortedByNames()
    ->get();
```
___

### Soft deleted listing
```php
\xGrz\Dhl24\Facades\DHL24::costsCenter()
    ->query()
    ->sortedByNames()
    ->onlyTrashed()
    ->get();
```
___

### Active and soft deleted listing
```php
\xGrz\Dhl24\Facades\DHL24::costsCenter()
    ->query()
    ->sortedByNames()
    ->withTrashed()
    ->paginate();
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



