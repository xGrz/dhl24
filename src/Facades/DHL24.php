<?php

namespace xGrz\Dhl24\Facades;

use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;
use xGrz\Dhl24\Api\Actions\GetMyShipments;
use xGrz\Dhl24\Api\Actions\GetPostalCodeServices;
use xGrz\Dhl24\Api\Actions\GetShippingConfirmationList;
use xGrz\Dhl24\Api\Actions\GetVersion;
use xGrz\Dhl24\Enums\ShippingConfirmationType;
use xGrz\Dhl24\Exceptions\DHL24Exception;

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
}
