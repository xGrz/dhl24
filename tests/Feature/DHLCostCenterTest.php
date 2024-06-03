<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Models\DHLCostCenter;
use xGrz\Dhl24\Models\DHLShipment;

class DHLCostCenterTest extends TestCase
{
    use RefreshDatabase;

    private function create(int $count = 10): void
    {
        for ($i = 0; $i < $count; $i++) {
            DHL24::costsCenter()->add('TestCostCenterName' . $i);
        }
    }

    public function test_add_cost_center()
    {
        self::create(1);
        $this->assertDatabaseHas('dhl_cost_centers', ['name' => 'TestCostCenterName0']);
    }

    public function test_throws_error_when_name_exists()
    {
        self::create(5);

        $this->expectException(DHL24Exception::class);
        DHL24::costsCenter()->add('TestCostCenterName2');
    }

    public function test_rename_cost_center()
    {
        self::create(5);

        $cc = DHLCostCenter::inRandomOrder()->first();
        $ccName = $cc->name;
        DHL24::costsCenter($cc)->rename('Renamed');

        $this->assertDatabaseMissing('dhl_cost_centers', ['name' => $ccName]);
        $this->assertDatabaseHas('dhl_cost_centers', ['name' => 'Renamed']);
    }

    public function test_permanent_delete_cost_center_when_no_related_shipments()
    {
        self::create(5);
        $cc = DHLCostCenter::inRandomOrder()->first();
        DHL24::costsCenter($cc)->delete();
        $cc = $cc->toArray();

        $this->assertDatabaseMissing('dhl_cost_centers', ['id' => $cc['id'], 'name' => $cc['name']]);
    }

    public function test_soft_delete_when_center_has_related_shipments()
    {
        self::create(1);
        $cc = DHLCostCenter::first();
        DHLShipment::create(['cost_center_id' => $cc->id]);
        DHL24::costsCenter($cc)->delete();

        $this->assertDatabaseHas('dhl_cost_centers', ['id' => $cc['id'], 'name' => $cc['name']]);
        $this->assertSoftDeleted($cc);
    }

    public function test_restore_after_soft_delete()
    {
        self::create(1);
        $cc = DHLCostCenter::first();
        $cc->deleted_at = now();

        DHL24::costsCenter($cc)->restore();
        $this->assertNotSoftDeleted($cc);
    }

    public function test_set_and_change_default_cost_center()
    {
        self::create(10);
        $cc1 = DHLCostCenter::inRandomOrder()->first();
        $cc2 = DHLCostCenter::inRandomOrder()->where('id', '<>', $cc1->id)->first();

        $this->assertDatabaseMissing('dhl_cost_centers', ['is_default' => 1]);

        DHL24::costsCenter($cc1)->setDefault();
        $this->assertEquals(1, DHLCostCenter::where(['is_default' => 1])->count());
        $this->assertDatabaseHas('dhl_cost_centers', ['name' => $cc1->name, 'is_default' => 1]);

        DHL24::costsCenter($cc2)->setDefault($cc2);
        $this->assertEquals(1, DHLCostCenter::where(['is_default' => 1])->count());
        $this->assertDatabaseHas('dhl_cost_centers', ['name' => $cc2->name, 'is_default' => 1]);
        $this->assertDatabaseHas('dhl_cost_centers', ['name' => $cc1->name, 'is_default' => 0]);
    }

    public function test_active_cost_center_listing()
    {
        self::create();
        DHLCostCenter::inRandomOrder()
            ->take(2)
            ->get()
            ->each(function (DHLCostCenter $center) {
                $center->update(['deleted_at' => now()]);
            });

        $this->assertEquals(8, DHL24::costsCenter()->query()->get()->count());
    }

    public function test_default_center_is_first_on_listing()
    {
        self::create();
        $cc = DHLCostCenter::inRandomOrder()->first();
        DHL24::costsCenter($cc)->setDefault();
        $listing = DHL24::costsCenter()->query()->get();

        $this->assertTrue($listing->first()->is_default);
    }

    public function test_deleted_cost_center_listing()
    {
        self::create();
        DHLCostCenter::inRandomOrder()
            ->take(4)
            ->get()
            ->each(function (DHLCostCenter $center) {
                $center->update(['deleted_at' => now()]);
            });

        $this->assertEquals(4, DHL24::costsCenter()->query()->onlyTrashed()->count());
    }

    public function test_get_complete_list_of_cost_centers()
    {
        self::create();
        DHLCostCenter::inRandomOrder()
            ->take(4)
            ->get()
            ->each(function (DHLCostCenter $center) {
                $center->update(['deleted_at' => now()]);
            });

        $this->assertEquals(10, DHL24::costsCenter()->query()->withTrashed()->count());
    }

    public function test_assigned_shipments_listing()
    {
        self::create(2);
        $cc = DHLCostCenter::first();
        for($i=0;$i<9;$i++) {
            DHLShipment::create(['cost_center_id' => $cc->id]);
        }

        $this->assertCount(9, DHL24::costsCenter($cc)->shipments()->get());
        $this->assertInstanceOf(DHLShipment::class, DHL24::costsCenter($cc)->shipments()->first());
    }


}
