<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Wizard\DHLShipmentWizard;

class DHLShipmentTest extends TestCase
{

    use RefreshDatabase;

    private static function create(int $count = 1)
    {

    }

    public function test_access_shipment_wizard_with_facade()
    {
        $this->assertInstanceOf(DHLShipmentWizard::class, DHL24::wizard());
    }

    public function test_set_shipper_data()
    {
        $wizard = DHL24::wizard()
            ->setShipperName('ACME Corp Ltd.')
            ->setShipperPostalCode('02-777')
            ->setShipperCity('Otwock')
            ->setShipperStreet('Warszawska')
            ->setShipperHouseNumber('102/20')
            ->setShipperContactPerson('John Rambo')
            ->setShipperContactEmail('john.rambo@example.com')
            ->setShipperContactPhone('504094400');

        $shipper = $wizard->getPayload()['shipper'];
        $this->assertEquals('ACME Corp Ltd.', $shipper['name']);
        $this->assertEquals('02777', $shipper['postalCode']);
        $this->assertEquals('Otwock', $shipper['city']);
        $this->assertEquals('Warszawska', $shipper['street']);
        $this->assertEquals('102/20', $shipper['houseNumber']);
        $this->assertEquals('John Rambo', $shipper['contactPerson']);
        $this->assertEquals('john.rambo@example.com', $shipper['contactEmail']);
        $this->assertEquals('504094400', $shipper['contactPhone']);
    }

    public function test_set_receiver_data()
    {
        $wizard = DHL24::wizard()
            ->setReceiverName('ACME Corp Ltd.')
            ->setReceiverPostalCode('02-777', 'DE')
            ->setReceiverCity('Otwock')
            ->setReceiverStreet('Warszawska')
            ->setReceiverHouseNumber('102/20')
            ->setReceiverContactPerson('John Rambo')
            ->setReceiverContactEmail('john.rambo@example.com')
            ->setReceiverContactPhone('504094400');


        $receiver = $wizard->getPayload()['receiver'];
        $this->assertEquals('DE', $receiver['country']);
        $this->assertEquals('ACME Corp Ltd.', $receiver['name']);
        $this->assertEquals('02777', $receiver['postalCode']);
        $this->assertEquals('Otwock', $receiver['city']);
        $this->assertEquals('Warszawska', $receiver['street']);
        $this->assertEquals('102/20', $receiver['houseNumber']);
        $this->assertEquals('John Rambo', $receiver['contactPerson']);
        $this->assertEquals('john.rambo@example.com', $receiver['contactEmail']);
        $this->assertEquals('504094400', $receiver['contactPhone']);
        $this->assertEquals(DHLAddressType::CONSUMER->value, $receiver['addressType']);

        $wizard->setReceiverType(DHLAddressType::BUSINESS);
        $this->assertEquals(DHLAddressType::BUSINESS->value, $wizard->getPayload()['receiver']['addressType']);
    }

    public function test_add_items()
    {
        $wizard = DHL24::wizard()
            ->addItem(DHLShipmentItemType::ENVELOPE, 1)
            ->addItem(DHLShipmentItemType::PACKAGE, 1, 20, 20, 15, 10, false)
            ->addItem(DHLShipmentItemType::PALLET, 3, 120, 80, 60, 30, true);

        $items = $wizard->getPayload()['pieceList'];
        $this->assertCount(3, $items);

        $this->assertCount(2, $items[0]);
        $this->assertCount(6, $items[1]);
        $this->assertCount(7, $items[2]);

        $this->assertEquals(DHLShipmentItemType::PALLET->value, $items[2]['type']);
        $this->assertEquals(3, $items[2]['quantity']);
        $this->assertEquals(120, $items[2]['weight']);
        $this->assertEquals(80, $items[2]['width']);
        $this->assertEquals(60, $items[2]['height']);
        $this->assertEquals(30, $items[2]['length']);
        $this->assertTrue($items[2]['non_standard']);

    }

    public function test_shipment_services_product()
    {
        $w = DHL24::wizard()->setShipmentType(DHLDomesticShipmentType::PREMIUM);
        $this->assertEquals('PR', $w->getPayload()['service']['product']);
        $this->assertArrayNotHasKey('deliveryEvening', $w->getPayload()['service']);

        $w = DHL24::wizard()->setShipmentType(DHLDomesticShipmentType::DOMESTIC09);
        $this->assertEquals('09', $w->getPayload()['service']['product']);
        $this->assertArrayNotHasKey('deliveryEvening', $w->getPayload()['service']);

        $w = DHL24::wizard()->setShipmentType(DHLDomesticShipmentType::DOMESTIC12);
        $this->assertEquals('12', $w->getPayload()['service']['product']);
        $this->assertArrayNotHasKey('deliveryEvening', $w->getPayload()['service']);

        $w = DHL24::wizard()->setShipmentType(DHLDomesticShipmentType::DOMESTIC);
        $this->assertEquals('AH', $w->getPayload()['service']['product']);
        $this->assertArrayNotHasKey('deliveryEvening', $w->getPayload()['service']);
    }

    public function test_shipment_services_delivery_evening()
    {
        $w = DHL24::wizard()->setShipmentType(DHLDomesticShipmentType::EVENING_DELIVERY);
        $this->assertEquals('DW', $w->getPayload()['service']['product']);
        $this->assertTrue($w->getPayload()['service']['deliveryEvening']);
    }

    public function test_shipment_services_delivery_on_saturday()
    {
        $w = DHL24::wizard()->setSaturdayDelivery();
        $this->assertTrue($w->getPayload()['service']['deliveryOnSaturday']);
    }

    public function test_shipment_services_pickup_on_saturday()
    {
        $w = DHL24::wizard()->setSaturdayPickup();
        $this->assertTrue($w->getPayload()['service']['pickupOnSaturday']);
    }

    public function test_shipment_services_collect_on_delivery()
    {
        $w = DHL24::wizard()->setCollectOnDelivery(200.99, 'INV/188/2024');

        $this->assertTrue($w->getPayload()['service']['collectOnDelivery']);
        $this->assertEquals('INV/188/2024', $w->getPayload()['service']['collectOnDeliveryReference']);
        $this->assertEquals(200.99, $w->getPayload()['service']['collectOnDeliveryValue']);

        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(200.99, $w->getPayload()['service']['insuranceValue']);
    }

    public function test_shipment_services_insurance()
    {
        $w = DHL24::wizard()->setShipmentValue(1200.99);

        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(1200.99, $w->getPayload()['service']['insuranceValue']);
    }

    public function test_set_insurance_lower_then_cod_should_return_cod_value()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(2020)
            ->setShipmentValue(1200.99);

        $this->assertTrue($w->getPayload()['service']['collectOnDelivery']);
        $this->assertTrue($w->getPayload()['service']['insurance']);

        $this->assertEquals(2020, $w->getPayload()['service']['collectOnDeliveryValue']);
        $this->assertEquals(2020, $w->getPayload()['service']['insuranceValue']);
    }

    public function test_set_cod_lower_then_insurance_should_return_original_insurance_value()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(202)
            ->setShipmentValue(1200.99);

        $this->assertTrue($w->getPayload()['service']['collectOnDelivery']);
        $this->assertTrue($w->getPayload()['service']['insurance']);

        $this->assertEquals(202,$w->getPayload()['service']['collectOnDeliveryValue']);
        $this->assertEquals(1200.99, $w->getPayload()['service']['insuranceValue']);
    }

    public function test_shipment_services_return_on_delivery()
    {
        $w = DHL24::wizard()->setReturnOnDelivery('INV/199/2024');

        $this->assertTrue($w->getPayload()['service']['returnOnDelivery']);
        $this->assertEquals('INV/199/2024', $w->getPayload()['service']['returnOnDeliveryReference']);
    }

    public function test_shipment_services_proof_of_delivery()
    {
        $w = DHL24::wizard()->setProofOfDelivery();
        $this->assertTrue($w->getPayload()['service']['proofOfDelivery']);
    }

    public function test_shipment_services_self_collect()
    {
        $w = DHL24::wizard()->setSelfCollect();
        $this->assertTrue($w->getPayload()['service']['selfCollect']);
    }


    public function test_shipment_services_predelivery_information()
    {
        $w = DHL24::wizard()->setPredeliveryInformation();
        $this->assertTrue($w->getPayload()['service']['predeliveryInformation']);
    }

    public function test_shipment_services_preaviso()
    {
        $w = DHL24::wizard()->setPreaviso();
        $this->assertTrue($w->getPayload()['service']['preaviso']);
    }

    public function test_shipment_services_payment()
    {
        Config::set('dhl24.auth.sap', 123123123);
        $payment = DHL24::wizard()->getPayload()['payment'];

        $this->assertEquals('BANK_TRANSFER', $payment['paymentMethod']);
        $this->assertEquals('SHIPPER', $payment['payerType']);
        $this->assertEquals('123123123', $payment['accountNumber']);
    }

    public function test_cost_center_exists_in_payment_structure()
    {
        $cc = DHL24::addCostCenter('TestCostCenter');
        $wizard = DHL24::wizard()->setCostCenter($cc);

        $this->assertEquals($cc->name, $wizard->getPayload()['payment']['costsCenter']);
    }

    public function test_shipment_content()
    {
        $wizard = DHL24::wizard();
        $wizard->setContent('Sex, drugs and r&r');

        $this->assertEquals('Sex, drugs and r&r', $wizard->getPayload()['content']);
    }

    public function test_shipment_comment()
    {
        $wizard = DHL24::wizard();
        $this->assertArrayNotHasKey('comment', $wizard->getPayload());

        $wizard->setComment('Call customer before delivery');
        $this->assertEquals('Call customer before delivery', $wizard->getPayload()['comment']);
    }

    public function test_shipment_date()
    {
        $wizard = DHL24::wizard();

        $this->assertEquals(now()->format('Y-m-d'), $wizard->getPayload()['shipmentDate']);
    }

}
