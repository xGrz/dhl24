<?php

namespace xGrz\Dhl24\Facades;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Facade;
use xGrz\Dhl24\Api\Actions\GetMyShipments;
use xGrz\Dhl24\Api\Actions\GetPostalCodeServices;
use xGrz\Dhl24\Api\Actions\GetPrice;
use xGrz\Dhl24\Api\Actions\GetShippingConfirmationList;
use xGrz\Dhl24\Api\Actions\GetVersion;
use xGrz\Dhl24\Enums\ShipmentType;
use xGrz\Dhl24\Enums\ShippingConfirmationType;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Wizard\ShipmentWizard;

class DHL24 extends Facade
{

    public static function getApiVersion(): string
    {
        try {
            return GetVersion::make()->call()->getVersion();
        } catch (DHL24Exception $e) {
            return '';
        }
    }

    /**
     * @throws DHL24Exception
     */
    public static function getMyShipments(Carbon $from, Carbon $to, int $pageNo = null): array
    {
        return GetMyShipments::make($from, $to, $pageNo)->call()->getItems();
    }

    /**
     * @throws DHL24Exception
     */
    public static function storeShippingConfirmationList(Carbon $date = null, ShippingConfirmationType $type = ShippingConfirmationType::ALL)
    {
        return GetShippingConfirmationList::make($date, $type)->call()->store()->getFilename();
    }

    /**
     * @throws DHL24Exception
     */
    public static function downloadShippingConfirmationListAsPDF(Carbon $date = null, ShippingConfirmationType $type = ShippingConfirmationType::ALL)
    {
        return GetShippingConfirmationList::make($date, $type)->call()->store();
    }

    /**
     * @throws DHL24Exception
     */
    public static function getPickupServices(string $postCode, Carbon $pickupDate = null, bool $toArray = false)
    {
        return GetPostalCodeServices::make($postCode, $pickupDate)->call()->pickup($toArray);
    }

    public static function getDeliveryServices(string $postCode, Carbon $deliveryDate = null, bool $toArray = false)
    {
        return GetPostalCodeServices::make($postCode, $deliveryDate)->call()->delivery($toArray);
    }

    public static function getPrice(ShipmentWizard $shipment)
    {
        try {
            return (new GetPrice($shipment))->call()->getPrice();
        } catch (DHL24Exception $e) {
            dump($e->getMessage());
        }
    }

    public static function getPriceOptions(ShipmentWizard $shipment): array
    {
        $options = [
            'PACKAGE' => false,
            'PACKAGE09' => false,
            'PACKAGE12' => false,
            'PACKAGE_EVENING' => false,
        ];

        try {
            $shipment->services()->setShipmentType(ShipmentType::DOMESTIC);
            $options['PACKAGE'] = (new GetPrice($shipment))->call()->getPrice();
        } catch (DHL24Exception $e) {
        }

        try {
            $shipment->services()->setShipmentType(ShipmentType::DOMESTIC09);
            $options['PACKAGE09'] = (new GetPrice($shipment))->call()->getPrice();
        } catch (DHL24Exception $e) {
        }

        try {
            $shipment->services()->setShipmentType(ShipmentType::DOMESTIC12);
            $options['PACKAGE12'] = (new GetPrice($shipment))->call()->getPrice();
        } catch (DHL24Exception $e) {
        }

        try {
            $shipment->services()->setShipmentType(ShipmentType::EVENING_DELIVERY);
            $options['PACKAGE_EVENING'] = (new GetPrice($shipment))->call()->getPrice();
        } catch (DHL24Exception $e) {
        }

        return $options;
    }

    public static function getOptions(ShipmentWizard $shipment)
    {
        return self::getDeliveryServices(
            $shipment->getDestinationPostCode(),
            Carbon::parse($shipment->getShipmentDate()),

        );
    }
}
