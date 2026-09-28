---
name: docs-a-update-system-documentation-sections-1-to-4-and-8-to-
title: Docs A: update SYSTEM_DOCUMENTATION sections 1 to 4 and 8 to 14 to the rebuilt app
created: 2026-09-28T03:53:23.258Z
updated: 2026-09-28T03:53:23.258Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260928-f9b423
tags: docs, update, system_documentation, sections, 14, rebuilt, app
---
# Docs A: update SYSTEM_DOCUMENTATION sections 1 to 4 and 8 to 14 to the rebuilt app

## When to use
Requests like:
- "Docs A: update SYSTEM_DOCUMENTATION sections 1 to 4 and 8 to 14 to the rebuilt app"

## Playbook
Files touched:
- CHANGELOG.md
- SYSTEM_DOCUMENTATION.txt

Acceptance criteria that passed:
- Section 4 lists every route that `php artisan route:list --except-vendor` prints (26), with the right method and purpose
- Section 9 states the current rules: prices with up to two decimals, photos validated before the room is saved, booking dates, total, overlap check and the 30 night cap
- Section 12 gives the current test count and the Docker test command
- Section 13 marks the fixed limitations as resolved and lists the three open owner decisions
- Section 14 describes the Docker startup on port 8080 and the restart after edits
- No statement in sections 1 to 4 or 8 to 14 contradicts the code

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. A fact check of every changed statement against the code found 4 errors (hard coded timezone, missing Sail build variables, a deprecation notice limited to PHP 8.5, seed status case); all corrected. Fact check documentation against the code before committing it.
- The debt entry about the timezone also says to set APP_TIMEZONE; the log is append only, so the corrected advice lives in the documentation.
