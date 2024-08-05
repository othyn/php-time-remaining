<?php

declare(strict_types=1);

namespace Othyn\Tests\Unit;

use Othyn\TimeRemaining\TimeRemaining;
use PHPUnit\Framework\TestCase;

final class TimeRemainingUnitTest extends TestCase
{
    private TimeRemaining $timeRemaining;

    protected function setUp(): void
    {
        $this->timeRemaining = new TimeRemaining(100);
    }

    public function test_constructor_with_default_total_items(): void
    {
        $timeRemaining = new TimeRemaining();

        $currentItem = 0;

        $progress = $timeRemaining->getProgress($currentItem);
        $this->assertEquals(0.0, $progress);

        $estimatedTotalTime = $timeRemaining->getEstimatedTotalTime($currentItem);
        $this->assertEquals(0.0, $estimatedTotalTime);
    }

    public function test_get_elapsed_time(): void
    {
        usleep(500000);
        $elapsedTime = $this->timeRemaining->getElapsedTime();
        $this->assertGreaterThan(0, $elapsedTime);
    }

    public function test_get_progress(): void
    {
        $currentItem = 50;
        $progress = $this->timeRemaining->getProgress($currentItem);
        $this->assertEquals(0.5, $progress);
    }

    public function test_get_percentage_progress(): void
    {
        $currentItem = 50;
        $percentageProgress = $this->timeRemaining->getPercentageProgress($currentItem);
        $this->assertEquals(50.0, $percentageProgress);
    }

    public function test_get_progress_with_zero_total_items(): void
    {
        $this->timeRemaining->setTotalItems(0);
        $currentItem = 50;
        $progress = $this->timeRemaining->getProgress($currentItem);
        $this->assertEquals(0.0, $progress);
    }

    public function test_get_estimated_total_time(): void
    {
        $currentItem = 50;
        usleep(500000);
        $estimatedTotalTime = $this->timeRemaining->getEstimatedTotalTime($currentItem);
        $this->assertGreaterThan(0, $estimatedTotalTime);
    }

    public function test_get_estimated_total_time_with_zero_progress(): void
    {
        $currentItem = 0;
        usleep(500000);
        $estimatedTotalTime = $this->timeRemaining->getEstimatedTotalTime($currentItem);
        $this->assertEquals(0.0, $estimatedTotalTime);
    }

    public function test_get_remaining_time(): void
    {
        $currentItem = 50;
        usleep(500000);
        $remainingTime = $this->timeRemaining->getRemainingTime($currentItem);
        $this->assertGreaterThan(0, $remainingTime);
    }

    public function test_format(): void
    {
        $currentItem = 50;
        $remainingSeconds = 3600;
        $formatted = $this->timeRemaining->format($currentItem, $remainingSeconds, '[%d%% - %d / %d items][~ %dh %dm %ds remaining]');
        $this->assertEquals('[50% - 50 / 100 items][~ 1h 0m 0s remaining]', $formatted);
    }

    public function test_get_formatted_progress(): void
    {
        $currentItem = 50;
        usleep(500000);
        $formattedProgress = $this->timeRemaining->getFormattedProgress($currentItem);
        $this->assertStringContainsString('[50% - 50 / 100]', $formattedProgress);
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
