<?php

require_once __DIR__ . '/KeywordMatcher.php';

/**
 * Classifies a ticket into one of four escalation tiers, checked in order
 * of precedence. Returns null if the ticket doesn't warrant escalation.
 */
class TierClassifier
{
    public const TIER_VIP = 'vip';
    public const TIER_PRIORITY = 'priority';
    public const TIER_KEYWORD = 'keyword';
    public const TIER_WATCHED = 'watched';

    public function __construct(
        private array $vipClientIds,
        private array $priorityClientIds,
        private array $watchedTicketIds,
        private KeywordMatcher $keywordMatcher,
    ) {
    }

    public function classify(array $ticket): ?string
    {
        if (in_array($ticket['client_id'], $this->vipClientIds, true)) {
            return self::TIER_VIP;
        }

        if (in_array($ticket['client_id'], $this->priorityClientIds, true)) {
            return self::TIER_PRIORITY;
        }

        if (in_array($ticket['id'], $this->watchedTicketIds, true)) {
            return self::TIER_WATCHED;
        }

        if (
            $this->keywordMatcher->hasEmergencyKeyword($ticket['subject'])
            && !$this->keywordMatcher->matchesSkipPattern($ticket['subject'])
        ) {
            return self::TIER_KEYWORD;
        }

        return null;
    }
}
