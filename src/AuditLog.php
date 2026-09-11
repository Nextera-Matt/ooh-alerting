<?php

/**
 * Records every decision the escalator makes - paged, suppressed as a
 * duplicate, or not escalated at all - so there's a full trail of why
 * a given ticket did or didn't result in a page.
 */
class AuditLog
{
    public array $entries = [];

    public function record(string $ticketId, string $outcome, string $reason): void
    {
        $this->entries[] = [
            'ticket_id' => $ticketId,
            'outcome' => $outcome, // 'paged' | 'suppressed_duplicate' | 'not_escalated'
            'reason' => $reason,
            'at' => date('Y-m-d H:i:s'),
        ];
    }
}
