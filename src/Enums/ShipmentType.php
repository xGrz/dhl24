<?php

namespace xGrz\Dhl24\Enums;

enum ShipmentType: string
{
    case DOMESTIC = 'AH';
    case PREMIUM = 'PR';
    case DOMESTIC09 = '09';
    case DOMESTIC12 = '12';
    case EVENING_DELIVERY = 'DW';
    case CONNECT = 'EK';
    case CONNECT_PLUS = 'CP';
    case CONNECT_PLUS_PALLET = 'CM';
    case INTERNATIONAL = 'PI';

}
