<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Wizard\DHLShipmentWizard;

class DHLShipmentWizardTest extends TestCase
{
    use RefreshDatabase;


    private static function createTestWizard(): DHLShipmentWizard
    {
        $cc = DHL24::addCostCenter('TestCC');

        return DHL24::wizard()
            ->setShipperName('ACME Corp Ltd.')
            ->setShipperPostalCode('02-777')
            ->setShipperCity('Otwock')
            ->setShipperStreet('Warszawska')
            ->setShipperHouseNumber('102/20')
            ->setShipperContactPerson('John Rambo')
            ->setShipperContactEmail('john.rambo@example.com')
            ->setShipperContactPhone('504094400')
            ->setReceiverName('Microsoft Corp Ltd.')
            ->setReceiverPostalCode('03-888', 'DE')
            ->setReceiverCity('Lomza')
            ->setReceiverStreet('Gdańska')
            ->setReceiverHouseNumber('101/1')
            ->setReceiverContactPerson('Johnny Travolta')
            ->setReceiverContactEmail('j.t@example.com')
            ->setReceiverContactPhone('677987787')
            ->addItem(DHLShipmentItemType::ENVELOPE, 1)
            ->addItem(DHLShipmentItemType::PACKAGE, 2, 20, 25, 15, 10, false)
            ->addItem(DHLShipmentItemType::PALLET, 3, 120, 80, 60, 30, true)
            ->setShipmentType(DHLDomesticShipmentType::PREMIUM)
            ->setContent('Elektronika')
            ->setCostCenter($cc)
            ->setCollectOnDelivery(400, 'INVOICE')
            ->setShipmentValue(500)
            ->setSaturdayDelivery()
            ->setReturnOnDelivery('RETURN-INVOICE')
            ->setSaturdayPickup()
            ->setProofOfDelivery()
            ->setSelfCollect()
            ->setPredeliveryInformation()
            ->setPreaviso()
            ->setComment('Call first')
            ->setEveningDelivery()
            ->setReference('Order 11111');
    }

    private function getPayload(): array
    {
        $wizard = self::createTestWizard();
        $payload = $wizard->getPayload();
        $wizard->store();
        return $payload;
    }

    public function test_access_shipment_wizard_with_facade()
    {
        $this->assertInstanceOf(DHLShipmentWizard::class, DHL24::wizard());
    }

    public function test_set_shipper_data()
    {
        $w = DHL24::wizard()
            ->setShipperName('ACME Corp Ltd.')
            ->setShipperPostalCode('02-777')
            ->setShipperCity('Otwock')
            ->setShipperStreet('Warszawska')
            ->setShipperHouseNumber('102/20')
            ->setShipperContactPerson('John Rambo')
            ->setShipperContactEmail('john.rambo@example.com')
            ->setShipperContactPhone('504094400');
        $payloadShipper = $w->getPayload()['shipper'];
        $w->store();


        $this->assertEquals('ACME Corp Ltd.', $payloadShipper['name']);
        $this->assertEquals('02777', $payloadShipper['postalCode']);
        $this->assertEquals('Otwock', $payloadShipper['city']);
        $this->assertEquals('Warszawska', $payloadShipper['street']);
        $this->assertEquals('102/20', $payloadShipper['houseNumber']);
        $this->assertEquals('John Rambo', $payloadShipper['contactPerson']);
        $this->assertEquals('john.rambo@example.com', $payloadShipper['contactEmail']);
        $this->assertEquals('504094400', $payloadShipper['contactPhone']);

        $this->assertDatabaseHas('dhl_shipments', [
            'shipper_name' => 'ACME Corp Ltd.',
            'shipper_postal_code' => '02777',
            'shipper_city' => 'Otwock',
            'shipper_street' => 'Warszawska',
            'shipper_house_number' => '102/20',
            'shipper_contact_person' => 'John Rambo',
            'shipper_contact_email' => 'john.rambo@example.com',
            'shipper_contact_phone' => '504094400',
        ]);
    }

    public function test_set_receiver_data()
    {
        $w = DHL24::wizard()
            ->setReceiverName('Microsoft Corp Ltd.')
            ->setReceiverPostalCode('03888', 'DE')
            ->setReceiverCity('Lomza')
            ->setReceiverStreet('Gdańska')
            ->setReceiverHouseNumber('101/1')
            ->setReceiverContactPerson('Johnny Travolta')
            ->setReceiverContactEmail('j.t@example.com')
            ->setReceiverContactPhone('677987787');

        $payloadReceiver = $w->getPayload()['receiver'];
        $w->store();

        $this->assertEquals('DE', $payloadReceiver['country']);
        $this->assertEquals('Microsoft Corp Ltd.', $payloadReceiver['name']);
        $this->assertEquals('03888', $payloadReceiver['postalCode']);
        $this->assertEquals('Lomza', $payloadReceiver['city']);
        $this->assertEquals('Gdańska', $payloadReceiver['street']);
        $this->assertEquals('101/1', $payloadReceiver['houseNumber']);
        $this->assertEquals('Johnny Travolta', $payloadReceiver['contactPerson']);
        $this->assertEquals('j.t@example.com', $payloadReceiver['contactEmail']);
        $this->assertEquals('677987787', $payloadReceiver['contactPhone']);
        $this->assertEquals(DHLAddressType::CONSUMER->value, $payloadReceiver['addressType']);

        $this->assertDatabaseHas('dhl_shipments', [
            'receiver_type' => DHLAddressType::CONSUMER,
            'receiver_country' => 'DE',
            'receiver_name' => 'Microsoft Corp Ltd.',
            'receiver_postal_code' => '03888',
            'receiver_city' => 'Lomza',
            'receiver_street' => 'Gdańska',
            'receiver_house_number' => '101/1',
            'receiver_contact_person' => 'Johnny Travolta',
            'receiver_contact_email' => 'j.t@example.com',
            'receiver_contact_phone' => '677987787',
        ]);

        $w->setReceiverType(DHLAddressType::BUSINESS);
        $w->store();

        $this->assertDatabaseHas('dhl_shipments', [
            'receiver_type' => DHLAddressType::BUSINESS,
            'receiver_country' => 'DE',
            'receiver_name' => 'Microsoft Corp Ltd.',
            'receiver_postal_code' => '03888',
            'receiver_city' => 'Lomza',
            'receiver_street' => 'Gdańska',
            'receiver_house_number' => '101/1',
            'receiver_contact_person' => 'Johnny Travolta',
            'receiver_contact_email' => 'j.t@example.com',
            'receiver_contact_phone' => '677987787',
        ]);


//        $w->setReceiverType(DHLAddressType::BUSINESS);
//        $this->assertEquals(DHLAddressType::BUSINESS->value, $w->getPayload()['receiver']['addressType']);
    }

    public function test_add_items_to_shipment()
    {
        $w = DHL24::wizard()
            ->addItem(DHLShipmentItemType::ENVELOPE, 1)
            ->addItem(DHLShipmentItemType::PACKAGE, 2, 20, 25, 15, 10, false)
            ->addItem(DHLShipmentItemType::PALLET, 3, 120, 80, 60, 30, true);
        $payload = $w->getPayload();
        $w->store();

        $this->assertCount(3, $payload['pieceList']);
        $this->assertEquals(DHLShipmentItemType::ENVELOPE->value, $payload['pieceList'][0]['type']);
        $this->assertEquals(1, $payload['pieceList'][0]['quantity']);
        $this->assertEquals(DHLShipmentItemType::PACKAGE->value, $payload['pieceList'][1]['type']);
        $this->assertEquals(2, $payload['pieceList'][1]['quantity']);
        $this->assertEquals(20, $payload['pieceList'][1]['weight']);
        $this->assertEquals(10, $payload['pieceList'][1]['length']);
        $this->assertEquals(25, $payload['pieceList'][1]['width']);
        $this->assertEquals(15, $payload['pieceList'][1]['height']);
        $this->assertEquals(DHLShipmentItemType::PALLET->value, $payload['pieceList'][2]['type']);
        $this->assertEquals(3, $payload['pieceList'][2]['quantity']);

        $this->assertDatabaseHas('dhl_shipment_items', [
            'type' => DHLShipmentItemType::ENVELOPE->value,
            'quantity' => 1,
            'weight' => null,
            'width' => null,
            'height' => null,
            'length' => null,
            'non_standard' => false
        ]);

        $this->assertDatabaseHas('dhl_shipment_items', [
            'type' => DHLShipmentItemType::PACKAGE->value,
            'quantity' => 2,
            'weight' => 20,
            'width' => 25,
            'height' => 15,
            'length' => 10,
            'non_standard' => false
        ]);

        $this->assertDatabaseHas('dhl_shipment_items', [
            'type' => DHLShipmentItemType::PALLET->value,
            'quantity' => 3,
            'weight' => 120,
            'width' => 80,
            'height' => 60,
            'length' => 30,
            'non_standard' => true
        ]);
    }

    public function test_service_product_type_domestic_()
    {
        $w = DHL24::wizard()
            ->setShipmentType(DHLDomesticShipmentType::DOMESTIC);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertEquals('AH', $service['product']);
        $this->assertArrayNotHasKey('deliveryEvening', $service);
        $this->assertArrayNotHasKey('deliveryOnSaturday', $service);
        $this->assertArrayNotHasKey('pickupOnSaturday', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'product' => 'AH',
            'delivery_evening' => false,
            'delivery_on_saturday' => false,
            'pickup_on_saturday' => false,
        ]);
    }

    public function test_service_product_type_domestic_09()
    {
        $w = DHL24::wizard()
            ->setShipmentType(DHLDomesticShipmentType::DOMESTIC09);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertEquals('09', $service['product']);
        $this->assertArrayNotHasKey('deliveryEvening', $service);
        $this->assertArrayNotHasKey('deliveryOnSaturday', $service);
        $this->assertArrayNotHasKey('pickupOnSaturday', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'product' => '09',
            'delivery_evening' => false,
            'delivery_on_saturday' => false,
            'pickup_on_saturday' => false,
        ]);
    }

    public function test_service_product_type_domestic_12()
    {
        $w = DHL24::wizard()
            ->setShipmentType(DHLDomesticShipmentType::DOMESTIC12);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertEquals('12', $service['product']);
        $this->assertArrayNotHasKey('deliveryEvening', $service);
        $this->assertArrayNotHasKey('deliveryOnSaturday', $service);
        $this->assertArrayNotHasKey('pickupOnSaturday', $service);


        $this->assertDatabaseHas('dhl_shipments', [
            'product' => '12',
            'delivery_evening' => false,
            'delivery_on_saturday' => false,
            'pickup_on_saturday' => false,
        ]);
    }

    public function test_service_product_type_premium()
    {
        $w = DHL24::wizard()
            ->setShipmentType(DHLDomesticShipmentType::PREMIUM);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertEquals('PR', $service['product']);
        $this->assertArrayNotHasKey('deliveryEvening', $service);
        $this->assertArrayNotHasKey('deliveryOnSaturday', $service);
        $this->assertArrayNotHasKey('pickupOnSaturday', $service);


        $this->assertDatabaseHas('dhl_shipments', [
            'product' => 'PR',
            'delivery_evening' => false,
            'delivery_on_saturday' => false,
            'pickup_on_saturday' => false,
        ]);
    }

    public function test_service_product_type_delivery_evening()
    {
        $w = DHL24::wizard()
            ->setShipmentType(DHLDomesticShipmentType::EVENING_DELIVERY);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertEquals('DW', $service['product']);
        $this->assertTrue($service['deliveryEvening']);
        $this->assertArrayNotHasKey('deliveryOnSaturday', $service);
        $this->assertArrayNotHasKey('pickupOnSaturday', $service);


        $this->assertDatabaseHas('dhl_shipments', [
            'product' => 'DW',
            'delivery_evening' => true,
            'delivery_on_saturday' => false,
            'pickup_on_saturday' => false,
        ]);
    }

    public function test_service_delivery_on_saturday()
    {
        $w = DHL24::wizard()
            ->setSaturdayDelivery();
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['deliveryOnSaturday']);
        $this->assertArrayNotHasKey('pickupOnSaturday', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'delivery_on_saturday' => true,
            'pickup_on_saturday' => false,
            'delivery_evening' => false,
        ]);

    }

    public function test_service_pickup_on_saturday()
    {
        $w = DHL24::wizard()
            ->setSaturdayPickup();
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['pickupOnSaturday']);
        $this->assertArrayNotHasKey('deliveryEvening', $service);
        $this->assertArrayNotHasKey('deliveryOnSaturday', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'pickup_on_saturday' => true,
            'delivery_evening' => false,
            'delivery_on_saturday' => false,

        ]);
    }

    public function test_service_collect_on_delivery_with_reference()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(250, 'INV 5/2024');
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['collectOnDelivery']);
        $this->assertEquals(250, $service['collectOnDeliveryValue']);
        $this->assertEquals('BANK_TRANSFER', $service['collectOnDeliveryForm']);
        $this->assertEquals('INV 5/2024', $service['collectOnDeliveryReference']);

        $this->assertDatabaseHas('dhl_shipments', [
            'collect_on_delivery' => 250,
            'collect_on_delivery_reference' => 'INV 5/2024',
            'insurance' => 250,
        ]);
    }

    public function test_service_pure_insurance_without_helpers()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);
        $w = DHL24::wizard()
            ->setShipmentValue(290);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertEquals(290, $service['insurance']);
        $this->assertArrayNotHasKey('collect_on_delivery', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'insurance' => 290,
        ]);
    }

    public function test_service_insurance_with_rounding()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', 50);
        $w = DHL24::wizard()
            ->setShipmentValue(290);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertEquals(300, $service['insurance']);
        $this->assertArrayNotHasKey('collect_on_delivery', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'insurance' => 300,
        ]);
    }

    public function test_service_insurance_with_cost_saver_below_max_value()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 298);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', 100);
        $w = DHL24::wizard()
            ->setShipmentValue(290);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertArrayNotHasKey('insurance', $service);
        $this->assertArrayNotHasKey('collect_on_delivery', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'insurance' => null,
        ]);
    }

    public function test_service_insurance_with_cost_saver_over_max_value()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 288);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);
        $w = DHL24::wizard()
            ->setShipmentValue(290);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['insurance']);
        $this->assertEquals(290, $service['insuranceValue']);
        $this->assertArrayNotHasKey('collect_on_delivery', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'insurance' => 290,
        ]);
    }

    public function test_service_insurance_with_value_rounding()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', 200);
        $w = DHL24::wizard()
            ->setShipmentValue(290);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['insurance']);
        $this->assertEquals(400, $service['insuranceValue']);
        $this->assertArrayNotHasKey('collect_on_delivery', $service);

        $this->assertDatabaseHas('dhl_shipments', [
            'insurance' => 400,
        ]);
    }


    public function test_collect_on_delivery_automatically_sets_insurance()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);

        $w = DHL24::wizard()
            ->setCollectOnDelivery(300);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['insurance']);
        $this->assertEquals(300, $service['insuranceValue']);

        $this->assertDatabaseHas('dhl_shipments', [
            'insurance' => 300,
        ]);
    }

    public function test_collect_on_delivery_automatically_set_insurance_cannot_be_overwritten_by_lower_insurance()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);

        $w = DHL24::wizard()
            ->setCollectOnDelivery(300)
            ->setShipmentValue(100);

        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['insurance']);
        $this->assertEquals(300, $service['insuranceValue']);

        $this->assertDatabaseHas('dhl_shipments', [
            'insurance' => 300,
        ]);
    }

    public function test_collect_on_delivery_automatically_set_insurance_can_be_overwritten_by_higher_insurance()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);

        $w = DHL24::wizard()
            ->setCollectOnDelivery(300)
            ->setShipmentValue(500);

        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['insurance']);
        $this->assertEquals(500, $service['insuranceValue']);

        $this->assertDatabaseHas('dhl_shipments', [
            'collect_on_delivery' => 300,
            'insurance' => 500,
        ]);
    }

    public function test_collect_on_delivery_should_not_overwrite_higher_insurance_value()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);

        $w = DHL24::wizard()
            ->setShipmentValue(500)
            ->setCollectOnDelivery(300);

        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['insurance']);
        $this->assertEquals(500, $service['insuranceValue']);

        $this->assertDatabaseHas('dhl_shipments', [
            'collect_on_delivery' => 300,
            'insurance' => 500,
        ]);
    }

    public function test_collect_on_delivery_higher_then_insurance_should_overwrite_insurance()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);

        $w = DHL24::wizard()
            ->setShipmentValue(300)
            ->setCollectOnDelivery(500);

        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['insurance']);
        $this->assertEquals(500, $service['insuranceValue']);
        $this->assertEquals(500, $service['collectOnDeliveryValue']);

        $this->assertDatabaseHas('dhl_shipments', [
            'collect_on_delivery' => 500,
            'insurance' => 500,
        ]);
    }


    public function test_collect_on_delivery_set_insurance_when_cost_saver_is_applied()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 500);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);

        $w = DHL24::wizard()
            ->setCollectOnDelivery(400);
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertEquals(400, $service['insurance']);
        $this->assertEquals(400, $service['collectOnDelivery']);

        $this->assertDatabaseHas('dhl_shipments', [
            'insurance' => 400,
            'collect_on_delivery' => 400,
        ]);
    }

    public function test_service_return_on_delivery()
    {
        $w = DHL24::wizard()
            ->setReturnOnDelivery('INV/199/2024');
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['returnOnDelivery']);
        $this->assertEquals('INV/199/2024', $service['returnOnDeliveryReference']);

        $this->assertDatabaseHas('dhl_shipments', [
            'return_on_delivery' => true,
            'return_on_delivery_reference' => 'INV/199/2024',
        ]);
    }

    public function test_service_proof_of_delivery()
    {
        $w = DHL24::wizard()
            ->setProofOfDelivery();
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['proofOfDelivery']);

        $this->assertDatabaseHas('dhl_shipments', [
            'proof_of_delivery' => true,
        ]);
    }

    public function test_service_self_collect()
    {
        $w = DHL24::wizard()
            ->setSelfCollect();
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['selfCollect']);

        $this->assertDatabaseHas('dhl_shipments', [
            'self_collect' => true,
        ]);
    }

    public function test_service_predelivery_information()
    {
        $w = DHL24::wizard()
            ->setPredeliveryInformation();
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['predeliveryInformation']);

        $this->assertDatabaseHas('dhl_shipments', [
            'predelivery_information' => true,
        ]);
    }

    public function test_service_preaviso()
    {
        $w = DHL24::wizard()
            ->setPreaviso();
        $service = $w->getPayload()['service'];
        $w->store();

        $this->assertTrue($service['preaviso']);

        $this->assertDatabaseHas('dhl_shipments', [
            'preaviso' => true,
        ]);
    }


    public function test_payment_data_is_automatically_filled_by_wizard_with_shipper_payer_type()
    {
        Config::set('dhl24.auth.sap', 123123123);
        $w = DHL24::wizard();
        $payment = $w->getPayload()['payment'];
        $w->store();

        $this->assertEquals('BANK_TRANSFER', $payment['paymentMethod']);
        $this->assertEquals('SHIPPER', $payment['payerType']);
        $this->assertEquals('123123123', $payment['accountNumber']);

        $this->assertDatabaseHas('dhl_shipments', [
            'payer_type' => 'SHIPPER'
        ]);
    }

    public function test_assigning_cost_center()
    {
        $cc = DHL24::addCostCenter('TestCostCenter');
        $w = DHL24::wizard()
            ->setCostCenter($cc);
        $payment = $w->getPayload()['payment'];
        $w->store();

        $this->assertEquals($cc->name, $payment['costsCenter']);

        $this->assertDatabaseHas('dhl_shipments', [
            'cost_center_id' => $cc->id
        ]);
    }

    public function test_fill_today_shipment_date_by_default()
    {
        $w = DHL24::wizard();
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals(now()->format('Y-m-d'), $shipment['shipmentDate']);
        $this->assertDatabaseHas('dhl_shipments', [
            'shipment_date' => now()->format('Y-m-d')
        ]);
    }

    public function test_manual_shipment_date_assign()
    {
        $w = DHL24::wizard()
            ->setShipmentDate(now()->addDays(2));
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals(now()->addDays(2)->format('Y-m-d'), $shipment['shipmentDate']);
        $this->assertDatabaseHas('dhl_shipments', [
            'shipment_date' => now()->addDays(2)->format('Y-m-d')
        ]);
    }

    public function test_skip_restriction_check_missing_in_payload_when_disabled()
    {
        Config::set('dhl24.restrictions-check', false);
        $w = DHL24::wizard();
        $shipment = $w->getPayload();

        $this->assertArrayNotHasKey('skipRestrictionCheck', $shipment);
    }

    public function test_skip_restriction_check_exists_in_payload_when_enabled()
    {
        Config::set('dhl24.restrictions-check', true);
        $w = DHL24::wizard();
        $shipment = $w->getPayload();

        $this->assertArrayHasKey('skipRestrictionCheck', $shipment);
        $this->assertTrue($shipment['skipRestrictionCheck']);
    }

    public function test_shipment_comment()
    {
        $w = DHL24::wizard()
            ->setComment('Call customer before delivery');
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('Call customer before delivery', $shipment['comment']);

        $this->assertDatabaseHas('dhl_shipments', [
            'comment' => 'Call customer before delivery'
        ]);
    }

    public function test_shipment_content()
    {
        $w = DHL24::wizard()
            ->setContent('Sex, drugs and r&r');
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('Sex, drugs and r&r', $shipment['content']);
        $this->assertDatabaseHas('dhl_shipments', [
            'content' => 'Sex, drugs and r&r'
        ]);
    }

    public function test_shipment_reference()
    {
        $w = DHL24::wizard()
            ->setReference('ORDER 111');
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('ORDER 111', $shipment['reference']);

        $this->assertDatabaseHas('dhl_shipments', [
            'reference' => 'ORDER 111'
        ]);
    }

    public function test_shipment_reference_fills_cod_ref_when_cod_set_without_ref()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(200)
            ->setReference('ORDER 111');
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('ORDER 111', $shipment['service']['collectOnDeliveryReference']);

        $this->assertDatabaseHas('dhl_shipments', [
            'reference' => 'ORDER 111',
            'collect_on_delivery_reference' => null
        ]);
    }

    public function test_shipment_reference_fills_cod_ref_when_cod_set_without_ref_reversed_assign()
    {
        $w = DHL24::wizard()
            ->setReference('ORDER 111')
            ->setCollectOnDelivery(200);
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('ORDER 111', $shipment['service']['collectOnDeliveryReference']);

        $this->assertDatabaseHas('dhl_shipments', [
            'reference' => 'ORDER 111',
            'collect_on_delivery_reference' => null
        ]);
    }

    public function test_shipment_reference_is_not_applied_to_cod_ref_when_cod_set_ref()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(200, 'ORDER 222')
            ->setReference('ORDER 111');
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('ORDER 222', $shipment['service']['collectOnDeliveryReference']);

        $this->assertDatabaseHas('dhl_shipments', [
            'reference' => 'ORDER 111',
            'collect_on_delivery_reference' => 'ORDER 222'
        ]);
    }

    public function test_shipment_reference_is_not_applied_to_cod_ref_when_cod_set_ref_reversed_assign()
    {
        $w = DHL24::wizard()
            ->setReference('ORDER 111')
            ->setCollectOnDelivery(200, 'ORDER 222');
                $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('ORDER 222', $shipment['service']['collectOnDeliveryReference']);

        $this->assertDatabaseHas('dhl_shipments', [
            'reference' => 'ORDER 111',
            'collect_on_delivery_reference' => 'ORDER 222'
        ]);
    }

    public function test_collect_on_delivery_reference_is_copied_into_shipment_reference_when_empty()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(200, 'ORDER 222');
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('ORDER 222', $shipment['reference']);
        $this->assertDatabaseHas('dhl_shipments', [
            'reference' => null,
            'collect_on_delivery_reference' => 'ORDER 222',
        ]);
    }

    public function test_collect_on_delivery_reference_is_not_copied_into_shipment_reference_when_reference_is_filled()
    {
        $w = DHL24::wizard()
            ->setReference('INV 2002')
            ->setCollectOnDelivery(200, 'ORDER 222');
        $shipment = $w->getPayload();
        $w->store();

        $this->assertEquals('INV 2002', $shipment['reference']);
        $this->assertEquals('ORDER 222', $shipment['service']['collectOnDeliveryReference']);
        $this->assertDatabaseHas('dhl_shipments', [
            'reference' => 'INV 2002',
            'collect_on_delivery_reference' => 'ORDER 222',
        ]);

    }


}
