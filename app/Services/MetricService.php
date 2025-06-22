<?php

namespace App\Services;

use App\Interfaces\MetricRepositoryInterface;
use Exception;

class MetricService
{

    protected $metricRepository;

    public function __construct(MetricRepositoryInterface $metricRepository)
    {
        $this->metricRepository = $metricRepository;
    }

    public function getQuota($user) {

        try {
            $metric = $this->metricRepository->getQuota($user);
        } catch (Exception $e) {
            logger()->error("Erreur de la récupération des métriques pour le user $user : " . $e->getMessage());
        }

        return $metric;
    }
}
