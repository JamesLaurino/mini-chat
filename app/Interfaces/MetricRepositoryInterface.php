<?php

namespace App\Interfaces;

interface MetricRepositoryInterface
{
    public function getQuota($user);
}
