<?php

require_once __DIR__ . '/src/OohDetector.php';
require_once __DIR__ . '/src/KeywordMatcher.php';
require_once __DIR__ . '/src/TierClassifier.php';
require_once __DIR__ . '/src/Deduplicator.php';
require_once __DIR__ . '/src/VictorOpsNotifier.php';
require_once __DIR__ . '/src/AuditLog.php';
require_once __DIR__ . '/src/Escalator.php';

// Synthetic config - fake client IDs and keyword lists, not real ones.
$vipClientIds = ['client-101', 'client-204'];
$priorityClientIds = ['client-330'];
$watchedTicketIds = ['tk-9001'];
$emergencyKeywords = ['down', 'unreachable', 'data loss', 'outage'];
$skipPatterns = ['test ticket', 'please ignore'];

$keywordMatcher = new KeywordMatcher($emergencyKeywords, $skipPatterns);
$tierClassifier = new TierClassifier($vipClientIds, $priorityClientIds, $watchedTicketIds, $keywordMatcher);
$oohDetector = new OohDetector();
$deduplicator = new Deduplicator(snoozeSeconds: 1800);
$notifier = new VictorOpsNotifier();
$auditLog = new AuditLog();

$escalator = new Escalator($oohDetector, $tierClassifier, $deduplicator, $notifier, $auditLog);

// Force "now" to a known out-of-hours moment (a Saturday) so the demo
// is deterministic regardless of when you actually run it.
$now = strtotime('next Saturday 02:00');

$tickets = [
    ['id' => 'tk-1001', 'client_id' => 'client-101', 'subject' => 'Billing question'],
    ['id' => 'tk-1002', 'client_id' => 'client-999', 'subject' => 'Server unreachable since 1am'],
    ['id' => 'tk-1003', 'client_id' => 'client-999', 'subject' => 'test ticket - server down - please ignore'],
    ['id' => 'tk-1005', 'client_id' => 'client-330', 'subject' => 'Routine account query'],
    ['id' => 'tk-9001', 'client_id' => 'client-500', 'subject' => 'Routine question'],
    ['id' => 'tk-1004', 'client_id' => 'client-999', 'subject' => 'Unrelated general enquiry'],
];

foreach ($tickets as $ticket) {
    $escalator->process($ticket, $now);
}

// Re-run the same emergency ticket a moment later, to demonstrate dedup.
$escalator->process($tickets[1], $now + 60);

echo "=== Paged ===\n";
foreach ($notifier->sent as $page) {
    echo "- [{$page['tier']}] {$page['ticket_id']}: {$page['subject']}\n";
}

echo "\n=== Audit log ===\n";
foreach ($auditLog->entries as $entry) {
    echo "- {$entry['ticket_id']}: {$entry['outcome']} ({$entry['reason']})\n";
}
