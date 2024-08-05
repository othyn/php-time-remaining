<?php

declare(strict_types=1);

namespace Othyn\Tests\Feature;

use Othyn\TimeRemaining\TimeRemaining;
use PHPUnit\Framework\TestCase;

final class TimeRemainingFeatureTest extends TestCase
{
    private TimeRemaining $timeRemaining;

    protected function setUp(): void
    {
        $this->timeRemaining = new TimeRemaining(100);
    }

    public function test_progress_tracking(): void
    {
        usleep(1000000);

        $currentItem = 50;
        $elapsedTime = $this->timeRemaining->getElapsedTime();
        $estimatedTotalTime = $this->timeRemaining->getEstimatedTotalTime($currentItem);

        $this->assertGreaterThan(0, $elapsedTime);
        $this->assertGreaterThan($elapsedTime, $estimatedTotalTime);
    }

    public function test_dynamic_updates(): void
    {
        $currentItem = 10;
        usleep(2000000);
        $initialRemainingTime = $this->timeRemaining->getRemainingTime($currentItem);

        usleep(1000000);
        $updatedRemainingTime = $this->timeRemaining->getRemainingTime($currentItem);

        $this->assertGreaterThan(0, $initialRemainingTime);
        $this->assertLessThanOrEqual($updatedRemainingTime, $initialRemainingTime);
    }

    public function test_formatted_output(): void
    {
        $currentItem = 20;
        usleep(500000);

        $formattedProgress = $this->timeRemaining->getFormattedProgress($currentItem);
        $this->assertStringContainsString('[20% - 20 / 100]', $formattedProgress);
        $this->assertStringContainsString('~', $formattedProgress);
        $this->assertStringContainsString('remaining', $formattedProgress);
    }

    public function test_changing_total_items(): void
    {
        $this->timeRemaining->setTotalItems(200);
        $currentItem = 50;
        usleep(500000);

        $formattedProgress = $this->timeRemaining->getFormattedProgress($currentItem);
        $this->assertStringContainsString('[25% - 50 / 200]', $formattedProgress);
    }
}
