<?php

/**
 * Mock notifier standing in for a real VictorOps REST API call.
 * Just records what would have been sent, for the demo to display.
 */
class VictorOpsNotifier
{
    public array $sent = [];

    public function page(array $ticket, string $tier): void
    {
        $this->sent[] = [
            'ticket_id' => $ticket['id'],
            'tier' => $tier,
            'subject' => $ticket['subject'],
        ];
    }
}
