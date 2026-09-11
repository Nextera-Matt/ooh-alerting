# OOH Alerting System

A reference recreation of the out-of-hours escalation system I built at THG
Ingenuity - automated paging for critical support tickets outside business
hours, originally wired into VictorOps, handling overnight callouts for a
set of VIP clients without manual intervention.

**This is a clean, from-scratch rebuild for portfolio purposes** - not the
original production code, and it contains no real client IDs, keyword lists,
or webhook endpoints. It reimplements the same architecture: four escalation
tiers, out-of-hours/weekend detection, deduplication, and audit logging.

## How escalation works

Every incoming ticket goes through the same pipeline:

1. **Is it out of hours?** (`OohDetector`) - outside business hours or a
   weekend. Tickets during business hours never escalate; the regular
   support team handles those.
2. **What tier is it?** (`TierClassifier`) - checked in priority order:
   - **VIP** - client is on the VIP list
   - **Priority** - client is on the priority list (not VIP, but still
     contractually above standard SLA)
   - **Keyword** - subject line matches an emergency keyword (e.g. "down",
     "unreachable", "data loss") and isn't excluded by a skip pattern
     (e.g. a subject containing "test ticket" shouldn't page anyone)
   - **Watched** - ticket ID is on an explicit watchlist, regardless of
     client or keywords
   - Anything matching none of these doesn't escalate.
3. **Has this already been paged?** (`Deduplicator`) - the same ticket
   shouldn't fire a fresh page every time the poller runs; a ticket that's
   been escalated recently is suppressed until its snooze window expires.
4. **Page** (`VictorOpsNotifier`) - mocked here; in production this called
   the VictorOps REST API.
5. **Log it** (`AuditLog`) - every escalation (and every suppressed
   duplicate) gets a record: which ticket, which tier, when, and why.

## Running the demo

```bash
php run-demo.php
```

Runs a batch of synthetic tickets through the pipeline and prints what
escalated, what was suppressed as a duplicate, and why.
