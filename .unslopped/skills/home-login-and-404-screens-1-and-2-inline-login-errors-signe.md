---
name: home-login-and-404-screens-1-and-2-inline-login-errors-signe
title: Home, login and 404: Screens 1 and 2, inline login errors, signed in staff skip the login form
created: 2026-09-27T06:30:59.107Z
updated: 2026-09-27T06:30:59.107Z
runs: 1
completed: 1
abandoned: 0
gateFailures: 0
lastCycle: 20260927-3f3c6c
tags: home, login, 404, screens, inline, errors, signed, app, config, resources, routes, tests
---
# Home, login and 404: Screens 1 and 2, inline login errors, signed in staff skip the login form

## When to use
Requests like:
- "Home, login and 404: Screens 1 and 2, inline login errors, signed in staff skip the login form"

## Playbook
Files touched:
- CHANGELOG.md
- app/Http/Controllers/AuthenticationController.php
- config/app.php
- resources/views/authentication/login.blade.php
- resources/views/components/alert.blade.php
- resources/views/components/title.blade.php
- resources/views/errors/404.blade.php
- resources/views/home.blade.php
- resources/views/staff/rooms/create.blade.php
- resources/views/staff/rooms/edit.blade.php
- routes/web.php
- tests/Feature/PublicPagesTest.php

Acceptance criteria that passed:
- The home page shows "ACLC Hotel Reservation System", links Reserve a room to /search-room and Manage to /dashboard
- With APP_VERSION 2.1.0 the footer shows "Version 2.1.0"; without it no version text appears
- A wrong password returns to /login with the error under the Username field and the username kept
- Pages with inline errors (login, add room, edit room) do not repeat the errors in the corner; other pages still show the summary
- A login post without a password returns to /login with an error under the Password field
- The right password still signs in and lands on /dashboard
- A signed in staff member opening /login is redirected to /dashboard
- An unknown URL returns 404 with "Page not found" and links to /home and /search-room
- At 390px home, login and 404 have no horizontal scroll
- `npm run build` exits 0 and all tests pass

Gate runs: 8 (0 failed)

## Known failures
- none recorded

## Notes
- No gate failed. The browser run showed each login error twice (corner summary and inline); pages with inline errors now declare it and the summary stays for other pages.
- Review found a 500 on a login post with a missing field; fixed with a red test.
- Login rate limiting is logged as debt for the owner to decide.
