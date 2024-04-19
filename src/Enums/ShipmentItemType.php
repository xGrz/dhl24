<?php

namespace xGrz\Dhl24\Enums;

enum ShipmentItemType: string
{
    case ENVELOPE = 'envelope';
    case PACKAGE = 'package';
    case PALLET = 'pallet';
}
