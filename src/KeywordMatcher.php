<?php

/**
 * Matches ticket subjects against emergency keywords, and against skip
 * patterns that should suppress a match even if a keyword hits (e.g. a
 * subject like "test ticket - please ignore - server down" shouldn't page
 * anyone just because it contains "down").
 */
class KeywordMatcher
{
    public function __construct(
        private array $emergencyKeywords,
        private array $skipPatterns,
    ) {
    }

    public function hasEmergencyKeyword(string $subject): bool
    {
        $normalised = strtolower($subject);
        foreach ($this->emergencyKeywords as $keyword) {
            if (str_contains($normalised, strtolower($keyword))) {
                return true;
            }
        }
        return false;
    }

    public function matchesSkipPattern(string $subject): bool
    {
        $normalised = strtolower($subject);
        foreach ($this->skipPatterns as $pattern) {
            if (str_contains($normalised, strtolower($pattern))) {
                return true;
            }
        }
        return false;
    }
}
