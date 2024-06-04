<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Models\DHLStatus;
use xGrz\Dhl24\Services\DHLTrackingStatusService;

class DHLTrackingStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_predefined_states_listing()
    {
        $states = DHLTrackingStatusService::getStates();
        $this->assertTrue($states->count() > 10);
    }

    public function test_get_state()
    {
        $state = DHLTrackingStatusService::getState('DOR');

        $this->assertEquals('DOR', $state->symbol);
        $this->assertNotNull($state->description);
        $this->assertNull($state->custom_description);
        $this->assertEquals(DHLStatusType::DELIVERED, $state->type);
    }

    public function test_update_description()
    {
        $status = new DHLTrackingStatusService('DOR');
        $status->updateDescription('Doręczona');

        $this->assertDatabaseHas(DHLStatus::class, [
            'symbol' => 'DOR',
            'description' => 'Doręczona',
        ]);
    }

    public function test_update_type()
    {
        $status = new DHLTrackingStatusService('DOR');
        $status->updateType(DHLStatusType::ERROR);

        $this->assertDatabaseHas(DHLStatus::class, [
            'symbol' => 'DOR',
            'type' => DHLStatusType::ERROR,
        ]);
    }

    public function test_find_for_tracking_returns_existing_model()
    {
        $model = DHLTrackingStatusService::findForTracking('DOR', 'ABC');
        $this->assertEquals('DOR', $model->symbol);
    }

    public function test_find_for_tracking_updates_api_description()
    {
        $model = DHLTrackingStatusService::findForTracking('DOR', 'XXX');
        $this->assertEquals('DOR', $model->symbol);
        $this->assertDatabaseHas(DHLStatus::class, [
            'symbol' => 'DOR',
            'description' => 'XXX',
        ]);
    }

    public function test_find_for_tracking_creates_new_state_when_not_found()
    {
        $rows = DHLStatus::count();
        $model = DHLTrackingStatusService::findForTracking('DOR1', 'XXXYYY');

        $this->assertEquals('DOR1', $model->symbol);
        $this->assertDatabaseHas(DHLStatus::class, [
            'symbol' => 'DOR1',
            'description' => 'XXXYYY',
            'type' => null,
        ]);
        $this->assertEquals($rows + 1, DHLStatus::count());
    }

    public function test_get_type_options()
    {
        $options = DHLStatusType::getOptions();

        $this->assertNotEmpty($options);
        $this->assertTrue(in_array(DHLStatusType::NOT_FOUND->getLabel(), $options));
        $this->assertTrue(in_array(DHLStatusType::CREATED->getLabel(), $options));
        $this->assertTrue(in_array(DHLStatusType::SENT->getLabel(), $options));
        $this->assertTrue(in_array(DHLStatusType::DELIVERED->getLabel(), $options));
        $this->assertTrue(in_array(DHLStatusType::PICKED_UP->getLabel(), $options));
        $this->assertTrue(in_array(DHLStatusType::ERROR->getLabel(), $options));

    }
}
