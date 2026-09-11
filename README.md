<div align="center">

# 🚨 OOH Alerting System

**Automated out-of-hours escalation — four-tier classification, deduplication, and full audit logging, originally wired into VictorOps.**

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)

</div>

---

> ⚠️ **This repo is a clean, from-scratch recreation for portfolio purposes.** It reimplements the real architecture I built at THG Ingenuity, with synthetic client IDs and keyword lists — no real client data, credentials, or webhook endpoints anywhere in this code.

## 🎯 The Problem

Critical client tickets arriving outside business hours were getting missed — there was no automated way to detect and escalate them.

## ✨ The Solution

```
ticket arrives
     │
     ▼
out of hours? ──no──▶ not escalated (business hours team handles it)
     │yes
     ▼
classify tier: VIP → Priority → Keyword → Watched
     │
     ▼
already paged recently? ──yes──▶ suppressed (dedup)
     │no
     ▼
page + log
```

## 🚀 Features

- 🏷️ **Four-tier classification** — VIP client, priority client, emergency keyword match, or explicit watchlist
- 🔇 **Skip patterns** — a keyword match on a subject like "test ticket - please ignore" won't page anyone
- 🔁 **Deduplication** — a snooze window stops the same ticket re-paging every polling cycle
- 📝 **Full audit trail** — every decision (paged, suppressed, not escalated) is logged with a reason

## 📋 Requirements

```
✓ PHP 8+
```

## 🎮 Usage

```bash
git clone https://github.com/Nextera-Matt/ooh-alerting.git
cd ooh-alerting
php run-demo.php
```

Runs a batch of synthetic tickets through the full pipeline and prints what got paged, what was suppressed as a duplicate, and why.

## 🛠️ Stack

<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/VictorOps-000000?style=for-the-badge" />
</p>

## 📄 License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file.

## 📬 Support

<div align="center">

[![GitHub Issues](https://img.shields.io/github/issues/Nextera-Matt/ooh-alerting)](https://github.com/Nextera-Matt/ooh-alerting/issues)

[Open an Issue](https://github.com/Nextera-Matt/ooh-alerting/issues) • [Visit Portfolio](https://matthodges.uk)

</div>

---

<div align="center">

**Made by [Matt Hodges](https://matthodges.uk)**

</div>
