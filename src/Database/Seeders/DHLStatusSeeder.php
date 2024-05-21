<?php

namespace xGrz\Dhl24\Database\Seeders;

use Illuminate\Database\Seeder;
use xGrz\Dhl24\Enums\StatusType;
use xGrz\Dhl24\Models\DHLStatus;

class DHLStatusSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            'ERR' => [
                'type' => StatusType::NOT_FOUND,
                'description' => 'Nie znaleziono przesyłki o takim numerze'
            ],
            'EDWP' => [
                'type' => StatusType::CREATED,
                'description' => 'DHL otrzymał dane elektroniczne przesyłki'
            ],
            'DWP' => [
                'type' => StatusType::SENT,
                'description' => 'przesyłka odebrana od nadawcy'
            ],


            'SORT' => [
                'type' => StatusType::IN_TRANSPORT,
                'description' => "przesyłka jest obsługiwana w centrum sortowania"
            ],
            'LP' => [
                'type' => StatusType::IN_TRANSPORT,
                'description' => 'przesyłka dotarła do oddziału'
            ],
            'LK' => [
                'type' => StatusType::IN_DELIVERY,
                'description' => 'przesyłka przekazana kurierowi do doręczenia'
            ],
            'DOR' => [
                'type' => StatusType::DELIVERED,
                'description' => 'przesyłka doręczona do odbiorcy'
            ],
            'AWI' => [
                'type' => StatusType::DELIVERY_FAILED,
                'description' => 'próba doręczenia zakończona niepowodzeniem. Odbiorcy nie było w domu w momencie doręczenia przesyłki'
            ],
            'AWI_NSP' => [
                'type' => StatusType::DELIVERY_FAILED,
                'description' => 'Przesyłka zostanie doręczona do odbiorcy dziś lub w ciągu najbliższych dwóch dni roboczych'
            ],
            'BRG' => [
                'type' => StatusType::DELIVERY_FAILED,
                'description' => 'doręczenie wstrzymane do czasu uregulowania opłat przez
                odbiorcę'
            ],
            'SP_DW' => [
                'type' => StatusType::IN_DELIVERY,
                'description' => 'Przesyłka w drodze do Punktu DHL POP'
            ],
            'SP_DSP' => [
                'type' => StatusType::WAITING_TO_BE_PICKED_UP,
                'description' => 'przesyłka oczekuje na odbiór w DHL POP'
            ],
            'SP_DOR' => [
                'type' => StatusType::PICKED_UP,
                'description' => 'przesyłka odebrana przez odbiorcę finalnego z punktu
                DHL POP'
            ],
            'TRM' => [
                'type' => StatusType::HOLD,
                'description' => 'przesyłka oczekuje na kolejny cykl doręczenia'
            ],
            'TRM2' => [
                'type' => StatusType::HOLD,
                'description' => 'przesyłka dotarła do Terminala DHL. Doręczenie jej do odbiorcy planowane jest dzisiaj lub w umówionym z odbiorcą terminie'
            ],
            'ZWW' => [
                'type' => StatusType::HOLD,
                'description' => 'przesyłka oczekuje na kolejny cykl doręczenia'
            ],
            'PSHOP' => [
                'type' => StatusType::IN_TRANSPORT,
                'description' => 'decyzja Odbiorcy: przesyłka będzie oczekiwała na odbiór osobisty w DHL Parcelshop'
            ],
            'ZWW_NSP' => [
                'type' => StatusType::HOLD,
                'description' => 'Doręczenie do DHL POP planowane w kolejnym dniu roboczym'
            ],
            'AN' => [
                'type' => StatusType::ERROR,
                'description' => 'przesyłka błędnie zaadresowana. Prosimy o kontakt z naszym Działem Obsługi Klienta'],
            'OWL' => [
                'type' => StatusType::WAITING_TO_BE_PICKED_UP,
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


