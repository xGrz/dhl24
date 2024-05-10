<?php

namespace xGrz\Dhl24\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use xGrz\Dhl24\Facades\DHL24;
use xGrz\Dhl24\Helpers\Money;
use xGrz\Dhl24\Models\DHLContentSuggestion;
use xGrz\Dhl24\Models\DHLCostCenter;

class ShipmentServices extends Component
{

    private string $postalCode = '';
    public array $services = [];
    public array $costsCenter = [];
    public array $contentSuggestions = [];
    public string $deliveryService;
    public string $costCenterName = '';
    public string $content = '';
    public string $comment = '';
    public bool $pdi = false;
    public bool $rod = false;
    public bool $owl = false;
    public string $value = '';
    public string $cod = '';

    public function mount(string|null $postalCode): void
    {
        self::postalCodeUpdated($postalCode);
        self::getCostCenters();
        self::getContentSuggestions();
    }

    #[On('postalCode-updated')]
    public function postalCodeUpdated($postalCode): void
    {
        $this->postalCode = $postalCode;
        if (strlen($this->postalCode) > 4) {
            self::checkServices();
        } else {
            $this->services = [];
        }
    }

    private function checkServices(): void
    {
        try {
            $options = DHL24::getDeliveryServices(str_replace('-', '', $this->postalCode));
            $this->services = json_decode(json_encode($options), true);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            $this->services = [];
        }
    }

    public function updatedCod($codAmount)
    {
        $shipmentValue = Money::isValid($this->value) ? Money::from($this->value)->toNumber() : 0;
        $codValue = Money::isValid($codAmount) ? Money::from($codAmount)->toNumber() : 0;
        if (($codValue > $shipmentValue) && $codValue) {
            $this->value = Money::from($codValue)->format(',', ' ');
        }
    }

    public function render(): View
    {
        return view('dhl::shipments.livewire.shipment-services');
    }

    private function getCostCenters(): void
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

    private function getContentSuggestions(): void
    {
        $suggestions = DHLContentSuggestion::orderBy('name')->get();

        $this->contentSuggestions = $suggestions
            ->map(fn($contentSuggestion) => $contentSuggestion->name)
            ->toArray();

        $this->content = $suggestions
            ->filter(fn($suggestion) => $suggestion->is_default)
            ->first()
            ?->name ?? '';
    }


}
