<?php

use xGrz\Dhl24\Enums\DHLReportType;

return [
    DHLReportType::ALL->name => 'All shipments',
    DHLReportType::EX->name => 'Express shipments',
    DHLReportType::DR->name => 'Przesyłki drobnicowe',
    DHLReportType::PREMIUM->name => 'Premium shipments',
    DHLReportType::EUROPE->name => 'Foreign shipments',
];
