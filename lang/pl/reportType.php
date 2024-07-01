<?php

use xGrz\Dhl24\Enums\DHLReportType;

return [
    DHLReportType::ALL->name => 'Wszystkie przesyłki',
    DHLReportType::EX->name => 'Przesyłki ekspresowe',
    DHLReportType::DR->name => 'Przesyłki drobnicowe',
    DHLReportType::PREMIUM->name => 'Przesyłki premium',
    DHLReportType::EUROPE->name => 'Przesyłki zagraniczne'
];
