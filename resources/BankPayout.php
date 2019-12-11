<?php

namespace ddroche\shasta\resources;

class BankPayout extends ShastaResource
{
    public function getResource()
    {
        return '/bank_payouts';
    }
}
