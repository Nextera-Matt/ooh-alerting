<?php

/**
 * Determines whether a given timestamp falls outside normal business
 * hours (weekday 09:00-18:00) or on a weekend - either counts as "OOH"
 * for escalation purposes.
 */
class OohDetector
{
    public function __construct(
        private int $businessStartHour = 9,
        private int $businessEndHour = 18,
    ) {
    }

    public function isOutOfHours(int $timestamp): bool
    {
        if ($this->isWeekend($timestamp)) {
            return true;
        }

        $hour = (int) date('G', $timestamp);
        return $hour < $this->businessStartHour || $hour >= $this->businessEndHour;
    }

    public function isWeekend(int $timestamp): bool
    {
        $dayOfWeek = (int) date('N', $timestamp); // 1 (Mon) - 7 (Sun)
        return $dayOfWeek >= 6;
    }
}
