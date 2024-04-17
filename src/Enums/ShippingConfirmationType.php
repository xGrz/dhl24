<?php

namespace xGrz\Dhl24\Enums;

enum ShippingConfirmationType: string
{
    case EX = 'Express';
    case DR = 'General cargo';
    case ALL = 'All';
    case TO_EUROPE = 'To Europe';
    case PR = 'Premium';
}
