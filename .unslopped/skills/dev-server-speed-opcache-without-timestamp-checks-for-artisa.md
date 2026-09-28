---
name: dev-server-speed-opcache-without-timestamp-checks-for-artisa
title: Dev server speed: opcache without timestamp checks for artisan serve, mounted from the repo
created: 2026-09-27T21:15:21.619Z
updated: 2026-09-27T21:15:21.619Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-5bfd2f
tags: dev, server, speed, opcache, without, timestamp, checks, artisan, docker
---
# Dev server speed: opcache without timestamp checks for artisan serve, mounted from the repo

## When to use
Requests like:
- "Dev server speed: opcache without timestamp checks for artisan serve, mounted from the repo"

## Playbook
Files touched:
- CHANGELOG.md
- README.md
- compose.yaml
- docker/php/opcache.ini

Acceptance criteria that passed:
- After `docker compose up -d`, `php -i` in the app container reports opcache.enable_cli On and opcache.validate_timestamps Off
- 15 requests each to /search-room, /dashboard, /rooms, /reservations and /guest have p90 under 0.5 s
- After editing a Blade view and running `docker compose restart laravel.test`, the page shows the edit
- The test suite still runs against the testing database and all tests pass
- `npm run build` exits 0

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- Benchmark runs vary with the load of about 90 other containers on the machine: the first run after the change had a p90 of 0.56 s on /search-room, the second 0.35 s. Before the change p90 ranged 1.2 to 4.4 s.
- Restarting the app container takes 10 to 40 seconds before /up answers; poll /up instead of sleeping.
- Reviewed in the session (14 lines of config and docs): no findings.
