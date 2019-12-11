<?php

namespace ddroche\shasta\resources;

class CardPayinsRefunds extends ShastaResource
{
    public function getResource()
    {
        return '/acquiring/card_payin_refunds';
    }
}
