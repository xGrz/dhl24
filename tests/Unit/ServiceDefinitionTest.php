<?php

use Tests\TestCase;
use xGrz\Dhl24\Enums\ShipmentType;
use xGrz\Dhl24\Wizard\Components\ServiceDefinition;

class ServiceDefinitionTest extends TestCase
{

    private ServiceDefinition $serviceDefinition;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', false);
        Config::set('dhl24.shipment-insurance.insurance_value_round_up', 0);

        $this->serviceDefinition = new ServiceDefinition(ShipmentType::DOMESTIC12);
    }

    public function test_is_insurance_not_set_by_default(): void
    {
        $this->assertFalse($this->serviceDefinition->insurance);
    }

    public function test_is_insurance_is_successfully_set(): void
    {
        $this->serviceDefinition->setInsurance(200.90);

        $this->assertTrue($this->serviceDefinition->insurance);
        $this->assertEquals(201, $this->serviceDefinition->insuranceValue);
    }

    public function test_is_insurance_is_removed_with_intelligent_cost_saver_enabled_when_value_below_500(): void
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 500);
        $this->serviceDefinition->setInsurance(499.99);

        $this->assertFalse($this->serviceDefinition->insurance);
        $this->assertNull($this->serviceDefinition->insuranceValue);

    }

    public function test_is_insurance_is_set_with_intelligent_cost_saver_enabled_when_value_above_500(): void
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 500);

        $this->serviceDefinition->setInsurance(500.01);

        $this->assertTrue($this->serviceDefinition->insurance);
        $this->assertEquals(501, $this->serviceDefinition->insuranceValue);
    }

    public function test_is_collect_on_delivery_is_successfully_set(): void
    {
        $this->serviceDefinition->setCollectOnDelivery(200);
        $this->assertTrue($this->serviceDefinition->collectOnDelivery);
        $this->assertEquals(200, $this->serviceDefinition->collectOnDeliveryValue);
    }

    public function test_is_collect_on_delivery_setting_insurance(): void
    {
        $this->serviceDefinition->setCollectOnDelivery(200);

        $this->assertTrue($this->serviceDefinition->insurance);
        $this->assertEquals(200, $this->serviceDefinition->insuranceValue);
    }

    public function test_is_collect_on_delivery_setting_insurance_with_cost_saver_enabled(): void
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 500);
        $this->serviceDefinition->setCollectOnDelivery(200);

        $this->assertTrue($this->serviceDefinition->insurance);
        $this->assertEquals(200, $this->serviceDefinition->insuranceValue);
    }

    public function test_lower_insurance_is_overwritten_by_collect_on_delivery_value()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 500);
        $this->serviceDefinition->setInsurance(300);
        $this->serviceDefinition->setCollectOnDelivery(400);

        $this->assertTrue($this->serviceDefinition->insurance);
        $this->assertEquals(400, $this->serviceDefinition->insuranceValue);
    }

    public function test_higher_insurance_is_not_overwritten_by_collect_on_delivery_value()
    {
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver', true);
        Config::set('dhl24.shipment-insurance.intelligent_cost_saver_max_value', 500);
        $this->serviceDefinition->setInsurance(800);
        $this->serviceDefinition->setCollectOnDelivery(400);

        $this->assertTrue($this->serviceDefinition->insurance);
        $this->assertEquals(800, $this->serviceDefinition->insuranceValue);
    }

    public function test_evening_delivery_is_successfully_set(): void
    {
        $this->assertFalse($this->serviceDefinition->deliveryEvening);
        $this->serviceDefinition->setEveningDelivery();

        $this->assertTrue($this->serviceDefinition->deliveryEvening);
    }

    public function test_saturday_delivery_overwrites_evening_delivery(): void
    {
        $this->serviceDefinition->setEveningDelivery();
        $this->assertTrue($this->serviceDefinition->deliveryEvening);

        $this->serviceDefinition->setDeliveryOnSaturday();

        $this->assertFalse($this->serviceDefinition->deliveryEvening);
        $this->assertTrue($this->serviceDefinition->deliveryOnSaturday);
    }

    public function test_evening_delivery_not_overwriting_saturday_delivery(): void
    {
        $this->serviceDefinition->setDeliveryOnSaturday();
        $this->serviceDefinition->setEveningDelivery();

        $this->assertTrue($this->serviceDefinition->deliveryOnSaturday);
        $this->assertFalse($this->serviceDefinition->deliveryEvening);
    }

    public function test_set_pickup_on_saturday()
    {
        $this->assertFalse($this->serviceDefinition->pickupOnSaturday);
        $this->serviceDefinition->setPickupOnSaturday();
        $this->assertTrue($this->serviceDefinition->pickupOnSaturday);
    }

    public function test_return_on_delivery()
    {
        $this->assertFalse($this->serviceDefinition->returnOnDelivery);
        $this->assertNull($this->serviceDefinition->returnOnDeliveryReference);
        $this->serviceDefinition->setReturnOnDelivery();

        $this->assertTrue($this->serviceDefinition->returnOnDelivery);
        $this->assertNull($this->serviceDefinition->returnOnDeliveryReference);
    }

    public function test_return_on_delivery_with_document_name()
    {
        $this->assertFalse($this->serviceDefinition->returnOnDelivery);
        $this->assertNull($this->serviceDefinition->returnOnDeliveryReference);
        $this->serviceDefinition->setReturnOnDelivery('nameOfDocument');

        $this->assertTrue($this->serviceDefinition->returnOnDelivery);
        $this->assertEquals('nameOfDocument', $this->serviceDefinition->returnOnDeliveryReference);
    }

    public function test_set_false_return_on_delivery_removes_document_name()
    {
        $this->assertFalse($this->serviceDefinition->returnOnDelivery);
        $this->assertNull($this->serviceDefinition->returnOnDeliveryReference);
        $this->serviceDefinition->setReturnOnDelivery('nameOfDocument');
        $this->serviceDefinition->setReturnOnDelivery(rod: false);

        $this->assertFalse($this->serviceDefinition->returnOnDelivery);
        $this->assertNull($this->serviceDefinition->returnOnDeliveryReference);
    }

}
