---
name: staff-shell-and-dashboard-responsive-sidebar-navigation-real
title: Staff shell and dashboard: responsive sidebar navigation, real totals and recent activity
created: 2026-09-26T21:15:04.530Z
updated: 2026-09-26T21:15:04.530Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260926-b32388
tags: staff, shell, dashboard, responsive, sidebar, navigation, real, totals, app, resources, tests
---
# Staff shell and dashboard: responsive sidebar navigation, real totals and recent activity

## When to use
Requests like:
- "Staff shell and dashboard: responsive sidebar navigation, real totals and recent activity"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/ManageController.php
- resources/css/app.css
- resources/views/components/staff-nav.blade.php
- resources/views/components/status-badge.blade.php
- resources/views/layout/staff.blade.php
- resources/views/staff/dashboard.blade.php
- tests/Feature/DashboardTest.php

Acceptance criteria that passed:
- With 7 rooms in the database, the dashboard reports 7 rooms, not 5
- The dashboard lists rooms that are not available with their status
- The dashboard reports available rooms, open reservations (confirmed or checked in) and guest totals from the whole table
- Recent reservations on the dashboard show the room number, not the room id
- The Dashboard link in the navigation carries `aria-current="page"` on the dashboard
- At 390px wide the navigation is hidden until Menu is pressed, and Menu reports its state with `aria-expanded`
- A visitor who is not signed in is still redirected from /dashboard to /login
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. Review before commit found 3 minor issues (badge contrast, a misleading "active stays" label, a lost room status list); all fixed before the commit, so no follow up commit was needed. Run the review before proposing the commit.
- Blade prints two spaces before an attribute emitted with inline `@if`; emit fixed attributes with a ternary so exact markup tests stay stable.
- Probe after deploy: / and /login return 200, /dashboard redirects signed out visitors (302).
