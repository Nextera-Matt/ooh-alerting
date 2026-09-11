<?php

require_once __DIR__ . '/OohDetector.php';
require_once __DIR__ . '/TierClassifier.php';
require_once __DIR__ . '/Deduplicator.php';
require_once __DIR__ . '/VictorOpsNotifier.php';
require_once __DIR__ . '/AuditLog.php';

class Escalator
{
    public function __construct(
        private OohDetector $oohDetector,
        private TierClassifier $tierClassifier,
        private Deduplicator $deduplicator,
        private VictorOpsNotifier $notifier,
        private AuditLog $auditLog,
    ) {
    }

    public function process(array $ticket, int $now): void
    {
        if (!$this->oohDetector->isOutOfHours($now)) {
            $this->auditLog->record($ticket['id'], 'not_escalated', 'within business hours');
            return;
        }

        $tier = $this->tierClassifier->classify($ticket);
        if ($tier === null) {
            $this->auditLog->record($ticket['id'], 'not_escalated', 'no matching tier');
            return;
        }

        if ($this->deduplicator->shouldSuppress($ticket['id'], $now)) {
            $this->auditLog->record($ticket['id'], 'suppressed_duplicate', "already paged for {$tier} recently");
            return;
        }

        $this->notifier->page($ticket, $tier);
        $this->deduplicator->markEscalated($ticket['id'], $now);
        $this->auditLog->record($ticket['id'], 'paged', "matched {$tier} tier");
    }
}
