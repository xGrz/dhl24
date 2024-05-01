<?php

namespace xGrz\Dhl24\Observers;

use xGrz\Dhl24\Models\DHLContentSuggestion;

class DHLContentSuggestionObserver
{
    public function updating(DHLContentSuggestion $content): void
    {
        if ($content->isDirty('is_default') && $content->is_default) {
            DHLContentSuggestion::where('is_default', true)->update(['is_default' => false]);
        }
    }

}
