<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;
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

    public function test_set_insurance_is_added_to_payload()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', 0);
        $w = DHL24::wizard()->setShipmentValue(200.99);

        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(200.99, $w->getPayload()['service']['insuranceValue']);
    }

    public function test_set_insurance_with_cost_saver_enabled_with_value_below_limit()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 500);
        $w = DHL24::wizard()->setShipmentValue(200.99);

        $this->assertArrayNotHasKey('insurance', $w->getPayload()['service'], 'Insurance shouldn`t be set.');
        $this->assertArrayNotHasKey('insuranceValue', $w->getPayload()['service']);
    }

    public function test_set_insurance_with_cost_saver_enabled_with_value_higher_then_limit()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 500);
        $w = DHL24::wizard()->setShipmentValue(500.99);

        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(500.99, $w->getPayload()['service']['insuranceValue']);
    }

    public function test_set_insurance_with_enabled_rounding()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', 50);
        $w = DHL24::wizard()->setShipmentValue(500.99);

        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(550, $w->getPayload()['service']['insuranceValue']);
    }

    public function test_set_higher_insurance_after_cod_should_not_overwrite_insurance_value()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(200)
            ->setShipmentValue(400);

        $this->assertEquals(400, $w->getPayload()['service']['insuranceValue']);
        $this->assertEquals(200, $w->getPayload()['service']['collectOnDeliveryValue']);
    }

    public function test_cod_with_reference_and_insurance_is_added_to_payload()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', false);
        $w = DHL24::wizard()->setCollectOnDelivery(200.01, 'INV/188/2024');

        $this->assertTrue($w->getPayload()['service']['collectOnDelivery']);
        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(200.01, $w->getPayload()['service']['collectOnDeliveryValue']);
        $this->assertEquals(200.01, $w->getPayload()['service']['insuranceValue']);
        $this->assertEquals('INV/188/2024', $w->getPayload()['service']['collectOnDeliveryReference']);
    }

    public function test_cod_with_reference_and_insurance_is_added_to_payload_with_insurance_rounding()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', 50);
        $w = DHL24::wizard()->setCollectOnDelivery(230.01, 'INV/188/2024');

        $this->assertTrue($w->getPayload()['service']['collectOnDelivery']);
        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(230.01, $w->getPayload()['service']['collectOnDeliveryValue']);
        $this->assertEquals(250, $w->getPayload()['service']['insuranceValue']);
        $this->assertEquals('INV/188/2024', $w->getPayload()['service']['collectOnDeliveryReference']);
    }

    public function test_cod_with_reference_and_insurance_is_added_to_payload_with_insurance_rounding_and_cost_saver_enabled()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', 100);
        $w = DHL24::wizard()->setCollectOnDelivery(230.01, 'INV/1881/2024');

        $this->assertTrue($w->getPayload()['service']['collectOnDelivery']);
        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(230.01, $w->getPayload()['service']['collectOnDeliveryValue']);
        $this->assertEquals(300, $w->getPayload()['service']['insuranceValue']);
        $this->assertEquals('INV/1881/2024', $w->getPayload()['service']['collectOnDeliveryReference']);
    }

    public function test_cod_with_higher_value_then_insurance_bumps_insurance_value()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(300)
            ->setShipmentValue(200);

        $this->assertEquals(300, $w->getPayload()['service']['collectOnDeliveryValue']);
        $this->assertEquals(300, $w->getPayload()['service']['insuranceValue']);

        $w = DHL24::wizard()
            ->setShipmentValue(200)
            ->setCollectOnDelivery(300);

        $this->assertEquals(300, $w->getPayload()['service']['collectOnDeliveryValue']);
        $this->assertEquals(300, $w->getPayload()['service']['insuranceValue']);
    }

    public function test_cod_adds_insurance_when_cost_saver_enabled_and_below_minimum_value()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 1000);
        $w = DHL24::wizard()->setCollectOnDelivery(400);

        $this->assertTrue($w->getPayload()['service']['collectOnDelivery']);
        $this->assertTrue($w->getPayload()['service']['insurance']);
        $this->assertEquals(400, $w->getPayload()['service']['collectOnDeliveryValue']);
        $this->assertEquals(400, $w->getPayload()['service']['insuranceValue']);
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

    public function test_store_shipment_shipper_data_in_database()
    {
        $wizard = self::createTestWizard();
        $payload = $wizard->getPayload();
        $wizard->store();

        // SHIPPER
        $this->assertEquals('ACME Corp Ltd.', $payload['shipper']['name']);
        $this->assertEquals('02777', $payload['shipper']['postalCode']);
        $this->assertEquals('Otwock', $payload['shipper']['city']);
        $this->assertEquals('Warszawska', $payload['shipper']['street']);
        $this->assertEquals('102/20', $payload['shipper']['houseNumber']);
        $this->assertEquals('John Rambo', $payload['shipper']['contactPerson']);
        $this->assertEquals('john.rambo@example.com', $payload['shipper']['contactEmail']);
        $this->assertEquals('504094400', $payload['shipper']['contactPhone']);

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

    public function test_store_receiver_data_in_database()
    {
        $wizard = self::createTestWizard();
        $payload = $wizard->getPayload();
        $shipment = $wizard->store();

        // RECEIVER
        $this->assertEquals('C', $payload['receiver']['addressType']);
        $this->assertEquals('DE', $payload['receiver']['country']);
        $this->assertEquals('Microsoft Corp Ltd.', $payload['receiver']['name']);
        $this->assertEquals('03888', $payload['receiver']['postalCode']);
        $this->assertEquals('Lomza', $payload['receiver']['city']);
        $this->assertEquals('Gdańska', $payload['receiver']['street']);
        $this->assertEquals('101/1', $payload['receiver']['houseNumber']);
        $this->assertEquals('Johnny Travolta', $payload['receiver']['contactPerson']);
        $this->assertEquals('j.t@example.com', $payload['receiver']['contactEmail']);
        $this->assertEquals('677987787', $payload['receiver']['contactPhone']);

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
        $shipment->delete();
    }

    public function test_store_items_data_in_database()
    {
        $wizard = self::createTestWizard();
        $payload = $wizard->getPayload();
        $wizard->store();

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

    public function test_set_shipment_reference()
    {
        $w = DHL24::wizard()->setReference('Order 200/2024');
        $this->assertEquals('Order 200/2024', $w->getPayload()['reference']);
    }

    public function test_fill_shipment_reference_from_cod_reference()
    {
        $w = DHL24::wizard()->setCollectOnDelivery(100, 'Order 200/2024');
        $this->assertEquals('Order 200/2024', $w->getPayload()['service']['collectOnDeliveryReference']);
        $this->assertEquals('Order 200/2024', $w->getPayload()['reference']);
    }

    public function test_fill_cod_reference_from_shipment_reference()
    {
        $w = DHL24::wizard()
            ->setReference('Order 200/2024')
            ->setCollectOnDelivery(100);

        $this->assertEquals('Order 200/2024', $w->getPayload()['service']['collectOnDeliveryReference']);
        $this->assertEquals('Order 200/2024', $w->getPayload()['reference']);
    }

    public function test_cannot_overwrite_shipment_reference_from_cod_reference()
    {
        $w = DHL24::wizard()
            ->setCollectOnDelivery(100, 'Order 200/2024')
            ->setReference('INVOICE');

        $this->assertEquals('Order 200/2024', $w->getPayload()['service']['collectOnDeliveryReference']);
        $this->assertEquals('INVOICE', $w->getPayload()['reference']);
    }

    public function test_cannot_overwrite_cod_reference_from_shipment_reference()
    {
        $w = DHL24::wizard()
            ->setReference('INVOICE')
            ->setCollectOnDelivery(100, 'Order 200/2024');

        $this->assertEquals('Order 200/2024', $w->getPayload()['service']['collectOnDeliveryReference']);
        $this->assertEquals('INVOICE', $w->getPayload()['reference']);
    }

    public function test_store_services_in_database()
    {
        $wizard = self::createTestWizard();
        $payload = $wizard->getPayload();
        $wizard->store();
        $cc = DHLCostCenter::where('name', 'TestCC')->first();

        $this->assertEquals(DHLDomesticShipmentType::PREMIUM->value, $payload['service']['product']);
        $this->assertEquals(500, $payload['service']['insuranceValue']);
        $this->assertEquals(400, $payload['service']['collectOnDeliveryValue']);
        $this->assertEquals(now()->format('Y-m-d'), $payload['shipmentDate']);
        $this->assertEquals('INVOICE', $payload['service']['collectOnDeliveryReference']);
        $this->assertEquals('Elektronika', $payload['content']);
        $this->assertEquals('TestCC', $payload['payment']['costsCenter']);
        $this->assertTrue($payload['service']['deliveryOnSaturday']);
        $this->assertTrue($payload['service']['returnOnDelivery']);
        $this->assertEquals('RETURN-INVOICE', $payload['service']['returnOnDeliveryReference']);
        $this->assertTrue($payload['service']['pickupOnSaturday']);
        $this->assertTrue($payload['service']['proofOfDelivery']);
        $this->assertTrue($payload['service']['selfCollect']);
        $this->assertTrue($payload['service']['predeliveryInformation']);
        $this->assertTrue($payload['service']['preaviso']);
        $this->assertTrue($payload['service']['deliveryEvening']);
        $this->assertEquals('Call first', $payload['comment']);
        $this->assertEquals('Order 11111', $payload['reference']);

        $this->assertDatabaseHas('dhl_shipments', [
            'product' => DHLDomesticShipmentType::PREMIUM->value,
            'insurance' => 500,
            'collect_on_delivery' => 400,
            'shipment_date' => now()->format('Y-m-d'),
            'collect_on_delivery_reference' => 'INVOICE',
            'content' => 'Elektronika',
            'cost_center_id' => $cc->id,
            'delivery_evening' => true,
            'delivery_on_saturday' => true,
            'pickup_on_saturday' => true,
            'return_on_delivery' => true,
            'return_on_delivery_reference' => 'RETURN-INVOICE',
            'proof_of_delivery' => true,
            'self_collect' => true,
            'predelivery_information' => true,
            'preaviso' => true,
            'payer_type' => 'SHIPPER',
            'comment' => 'Call first',
            'reference' => 'Order 11111',
        ]);

    }


}
