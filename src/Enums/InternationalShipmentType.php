<?php

namespace xGrz\Dhl24\Enums;

enum InternationalShipmentType: string
{
    case CONNECT = 'EK';
    case CONNECT_PLUS = 'CP';
    case CONNECT_PLUS_PALLET = 'CM';
    case INTERNATIONAL = 'PI';

}
