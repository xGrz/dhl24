<?php

namespace xGrz\Dhl24\Livewire\Forms;

use Livewire\Form;
use xGrz\Dhl24\Models\DHLContentSuggestion;
use xGrz\Dhl24\Models\DHLCostCenter;

class ShippingServices extends Form
{
    public $contentSuggestions = [];
    public $costsCenter = [];

    public string $deliveryService = 'AH';
    public string $costCenterName = '';
    public string $content = '';
    public string $value = '';
    public string $cod = '';
    public string $reference = '';
    public bool $pdi = false;
    public bool $rod = false;
    public bool $owl = false;

    public function getCostCenters(): void
    {
        $this->costsCenter = DHLCostCenter::query()
            ->select('name')
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get()
            ->map(fn($costName) => $costName->name)
            ->toArray();
        $this->costCenterName = $this->costsCenter[0] ?? '';
    }

    public function getContentSuggestions(): void
    {
        $suggestions = DHLContentSuggestion::orderBy('name')->get();


        $this->content = $suggestions
            ->filter(fn($suggestion) => $suggestion->is_default)
            ->first()
            ?->name ?? '';

        $this->contentSuggestions = $suggestions
            ->map(fn($contentSuggestion) => $contentSuggestion->name)
            ->toArray();
    }

    public function getCod()
    {
        if (!empty($this->cod)) {
            return 0;
        }
        return 0;
    }

    public function getValue()
    {
        return 0;
    }

}
