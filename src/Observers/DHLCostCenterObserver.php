<?php

namespace xGrz\Dhl24\Observers;

use xGrz\Dhl24\Models\DHLCostCenter;

class DHLCostCenterObserver
{
    public function updating(DHLCostCenter $costCenter): void
    {
        if ($costCenter->isDirty('is_default') && $costCenter->is_default) {
            DHLCostCenter::where('is_default', true)->update(['is_default' => false]);
        }
    }
}
