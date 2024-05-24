<?php

namespace xGrz\Dhl24\Helpers;

use Illuminate\Database\Seeder;
use xGrz\Dhl24\Enums\DHLStatusType;
use xGrz\Dhl24\Models\DHLStatus;

class DHLStatusSetup extends Seeder
{
    public function run(): void
    {
        $states = [
            'ERR' => [
                'type' => DHLStatusType::NOT_FOUND,
                'description' => 'Nie znaleziono przesyłki o takim numerze'
            ],
            'EDWP' => [
                'type' => DHLStatusType::CREATED,
                'description' => 'DHL otrzymał dane elektroniczne przesyłki'
            ],
            'DWP' => [
                'type' => DHLStatusType::SENT,
                'description' => 'przesyłka odebrana od nadawcy'
            ],


            'SORT' => [
                'type' => DHLStatusType::IN_TRANSPORT,
                'description' => "przesyłka jest obsługiwana w centrum sortowania"
            ],
            'LP' => [
                'type' => DHLStatusType::IN_TRANSPORT,
                'description' => 'przesyłka dotarła do oddziału'
            ],
            'LK' => [
                'type' => DHLStatusType::IN_DELIVERY,
                'description' => 'przesyłka przekazana kurierowi do doręczenia'
            ],
            'DOR' => [
                'type' => DHLStatusType::DELIVERED,
                'description' => 'przesyłka doręczona do odbiorcy'
            ],
            'AWI' => [
                'type' => DHLStatusType::DELIVERY_FAILED,
                'description' => 'próba doręczenia zakończona niepowodzeniem. Odbiorcy nie było w domu w momencie doręczenia przesyłki'
            ],
            'AWI_NSP' => [
                'type' => DHLStatusType::DELIVERY_FAILED,
                'description' => 'Przesyłka zostanie doręczona do odbiorcy dziś lub w ciągu najbliższych dwóch dni roboczych'
            ],
            'BRG' => [
                'type' => DHLStatusType::DELIVERY_FAILED,
                'description' => 'doręczenie wstrzymane do czasu uregulowania opłat przez
                odbiorcę'
            ],
            'SP_DW' => [
                'type' => DHLStatusType::IN_DELIVERY,
                'description' => 'Przesyłka w drodze do Punktu DHL POP'
            ],
            'SP_DSP' => [
                'type' => DHLStatusType::WAITING_TO_BE_PICKED_UP,
                'description' => 'przesyłka oczekuje na odbiór w DHL POP'
            ],
            'SP_DOR' => [
                'type' => DHLStatusType::PICKED_UP,
                'description' => 'przesyłka odebrana przez odbiorcę finalnego z punktu
                DHL POP'
            ],
            'TRM' => [
                'type' => DHLStatusType::HOLD,
                'description' => 'przesyłka oczekuje na kolejny cykl doręczenia'
            ],
            'TRM2' => [
                'type' => DHLStatusType::HOLD,
                'description' => 'przesyłka dotarła do Terminala DHL. Doręczenie jej do odbiorcy planowane jest dzisiaj lub w umówionym z odbiorcą terminie'
            ],
            'ZWW' => [
                'type' => DHLStatusType::HOLD,
                'description' => 'przesyłka oczekuje na kolejny cykl doręczenia'
            ],
            'PSHOP' => [
                'type' => DHLStatusType::IN_TRANSPORT,
                'description' => 'decyzja Odbiorcy: przesyłka będzie oczekiwała na odbiór osobisty w DHL Parcelshop'
            ],
            'ZWW_NSP' => [
                'type' => DHLStatusType::HOLD,
                'description' => 'Doręczenie do DHL POP planowane w kolejnym dniu roboczym'
            ],
            'AN' => [
                'type' => DHLStatusType::ERROR,
                'description' => 'przesyłka błędnie zaadresowana. Prosimy o kontakt z naszym Działem Obsługi Klienta'],
            'OWL' => [
                'type' => DHLStatusType::WAITING_TO_BE_PICKED_UP,
                'description' => 'przesyłka oczekuje na odbiór przez klienta w terminalu DHL'],
        ];

        foreach ($states as $symbol => $state) {
            if (!DHLStatus::where('symbol', $symbol)->exists()) {
                (new DHLStatus())
                    ->fill(['symbol' => $symbol])
                    ->fill($state)
                    ->save();
            }
        }
    }

}


