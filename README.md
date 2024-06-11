# Laravel package for DHL24.PL integration
DHL24.PL Laravel integration

* Direct API Calls
  * [Version / Test connection](docs/test-api.md)
  * [Get service points](docs/service-points.md)
  * [Shipments listing (from DHL server)](docs/api-shipment-listing.md)
  * [Get shipment list report (daily)](docs/report.md)
* [Shipment tracking](docs/tracking.md)
  * [Track single shipment](docs/tracking.md#track-single-shipment) 
  * [Updating shipments tracking info (in background)](docs/tracking.md#track-all-queue)
* HELPERS
  * [Shipment content manager](docs/content-suggestions.md)
  * [Shipment cost center manager / billings](docs/cost-center.md)
  * [Shipment state manager (based on tracking status)](docs/state-manager.md)
* [Download shipment label](docs/label.md)
* [Shipment wizard](docs/wizard.md)
* [Shipment cost](docs/cost.md)
___

## Installation

```
composer require xgrz/dhl24
```

## Credentials configuration

In your .env file:
```
DHL24_WSDL=https://dhl24.com.pl/webapi2.html
DHL24_USERNAME=
DHL24_PASSWORD=
DHLPARCELSHOP_LOGIN=
DHLPARCELSHOP_PASSWORD=
DHL24_SAP=
```

## Package settings


