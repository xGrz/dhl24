# COST CENTERS

>This is optional, so you don't have to use it.

Package can manage costs centers for accountants. You can have billing information divided into sections on your invoices.

> Package provides cost center manager. You can divide your shipments by unlimited number of cost centers.
> DHL invoice attachment divides shipments by those cost centers.
___
## LISTING

For all listing methods:
> `withPagination` (optional) parameter accepts true/false/integer. By default, `withPagination` is set to `false`. When `true` is provided a default (Laravel) per page will be used.
You can provide an integer value for custom perPage value.

> `paginationName` (optional) defines URI parameter that holds page number. Laravel default is `page`. 



### Active listing
```php
xGrz\Dhl24\Facades\DHL24::costsCenter($withPagination, $paginationName)
```
`withPagination` and `paginationName` read first paragraph in *Cost center listings* section

### Soft deleted listing
```php
xGrz\Dhl24\Facades\DHL24::deletedCostsCenter($withPagination, $paginationName)
```
`withPagination` and `paginationName` read first paragraph in *Cost center listings* section

### Active and soft deleted listing
```php
xGrz\Dhl24\Facades\DHL24::allCostCenters($withPagination, $paginationName)
```
`withPagination` and `paginationName` read first paragraph in *Cost center listings* section
___

## ADD

```php
xGrz\Dhl24\Facades\DHL24::addContentSuggestion($name);
```
Cost center `name` must be unique across all database rows (including soft deleted). If name exists DHL24Exception will be thrown.

___

## RENAME
```php
xGrz\Dhl24\Facades\DHL24::renameCostCenter($center, $name)
```
`center` - you can provide DHLCostCenter model or id

`name` - please provide name to update cost center

Cost center `name` must be unique across all database rows (including soft deleted). If name exists DHL24Exception will be thrown.

## DELETE

```php
xGrz\Dhl24\Facades\DHL24::deleteCostCenter($center)
```
`center` - you can provide DHLCostCenter model or id

If your cost center is used by shipments (assigned to shipment) it will be soft deleted to hold your history.
When cost center is not assigned to any shipment permanent delete will be executed.
___

## RESTORE
```php
xGrz\Dhl24\Facades\DHL24::restoreCostCenter($center)
```
`center` - you can provide DHLCostCenter model or id



