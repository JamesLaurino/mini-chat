<?php

namespace App\Services;

use App\Interfaces\MetricRepositoryInterface;

class MetricService
{

    protected $metricRepository;

    public function __construct(MetricRepositoryInterface $metricRepository)
    {
        $this->metricRepository = $metricRepository;
    }

    public function getQuota($user) {
        return $this->metricRepository->getQuota($user);
    }
}
