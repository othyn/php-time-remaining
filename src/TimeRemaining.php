<?php

declare(strict_types=1);

namespace Othyn\TimeRemaining;

class TimeRemaining
{
    protected float $startTime;
    protected int $totalItems;
    protected string $dateFormat = 'Y-m-d H:i:s';

    public function __construct(int $totalItems = 0)
    {
        $this->startTime = microtime(true);
        $this->totalItems = $totalItems;
    }

    public function setTotalItems(int $totalItems): void
    {
        $this->totalItems = $totalItems;
    }

    public function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }

    public function getElapsedTime(): float
    {
        return microtime(true) - $this->startTime;
    }

    public function getProgress(int $currentItem): float
    {
        return $this->totalItems === 0
            ? 0.0
            : $currentItem / $this->totalItems;
    }

    public function getPercentageProgress(int $currentItem): float
    {
        return $this->getProgress($currentItem) * 100;
    }

    public function getEstimatedTotalTime(int $currentItem): float
    {
        $progress = $this->getProgress($currentItem);

        return $progress === 0.0
            ? 0.0
            : $this->getElapsedTime() / $progress;
    }

    public function getRemainingTime(int $currentItem): float
    {
        return $this->getEstimatedTotalTime($currentItem) - $this->getElapsedTime();
    }

    public function getEstimatedCompletionDateTime(int $currentItem): string
    {
        $remainingSeconds = $this->getRemainingTime($currentItem);
        $estimatedCompletionTimestamp = $this->startTime + $this->getElapsedTime() + $remainingSeconds;

        return date($this->dateFormat, (int) $estimatedCompletionTimestamp);
    }

    public function format(int $currentItem, float $remainingSeconds, string $format): string
    {
        $hours = (int) floor($remainingSeconds / 3600);
        $minutes = (int) floor(((int) $remainingSeconds % 3600) / 60);
        $seconds = (int) $remainingSeconds % 60;
        $estimatedCompletion = $this->getEstimatedCompletionDateTime($currentItem);

        return sprintf($format, $this->getPercentageProgress($currentItem), $currentItem, $this->totalItems, $hours, $minutes, $seconds, $estimatedCompletion);
    }

    public function getFormattedProgress(int $currentItem, string $format = '[%d%% - %d / %d][~ %dh %dm %ds remaining][est. %s]'): string
    {
        return $this->format(
            currentItem: $currentItem,
            remainingSeconds: $this->getRemainingTime($currentItem),
            format: $format
        );
    }
}
