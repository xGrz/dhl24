<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Models\DHLTrackingState;
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

        $this->assertEquals('DOR', $state->code);
        $this->assertNotNull($state->system_description);
        $this->assertNull($state->description);
        $this->assertEquals(DHLStatusType::DELIVERED, $state->type);
    }

    public function test_update_description()
    {
        $status = new DHLTrackingStatusService('DOR');
        $status->updateDescription('Doręczona');

        $this->assertDatabaseHas(DHLTrackingState::class, [
            'code' => 'DOR',
            'description' => 'Doręczona',
        ]);
    }

    public function test_update_type()
    {
        $status = new DHLTrackingStatusService('DOR');
        $status->updateType(DHLStatusType::ERROR);

        $this->assertDatabaseHas(DHLTrackingState::class, [
            'code' => 'DOR',
            'type' => DHLStatusType::ERROR,
        ]);
    }

    public function test_find_for_tracking_returns_existing_model()
    {
        $model = DHLTrackingStatusService::findForTracking('DOR', 'ABC');
        $this->assertEquals('DOR', $model->code);
    }

    public function test_find_for_tracking_updates_api_description()
    {
        $model = DHLTrackingStatusService::findForTracking('DOR', 'XXX');
        $this->assertEquals('DOR', $model->code);
        $this->assertDatabaseHas(DHLTrackingState::class, [
            'code' => 'DOR',
            'system_description' => 'XXX',
        ]);
    }

    public function test_find_for_tracking_creates_new_state_when_not_found()
    {
        $rows = DHLTrackingState::count();
        $model = DHLTrackingStatusService::findForTracking('DOR1', 'XXXYYY');

        $this->assertEquals('DOR1', $model->code);
        $this->assertDatabaseHas(DHLTrackingState::class, [
            'code' => 'DOR1',
            'system_description' => 'XXXYYY',
            'type' => null,
        ]);
        $this->assertEquals($rows + 1, DHLTrackingState::count());
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
