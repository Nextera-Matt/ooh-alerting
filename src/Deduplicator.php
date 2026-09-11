<?php

/**
 * Prevents the same ticket from re-triggering a page every polling cycle.
 * Once escalated, a ticket is suppressed for a snooze window - in
 * production this was backed by a small persistent store; here it's an
 * in-memory map, which is fine for a single run of the demo.
 */
class Deduplicator
{
    private array $lastEscalated = [];

    public function __construct(private int $snoozeSeconds = 1800)
    {
    }

    public function shouldSuppress(string $ticketId, int $now): bool
    {
        if (!isset($this->lastEscalated[$ticketId])) {
            return false;
        }
        return ($now - $this->lastEscalated[$ticketId]) < $this->snoozeSeconds;
    }

    public function markEscalated(string $ticketId, int $now): void
    {
        $this->lastEscalated[$ticketId] = $now;
    }
}
