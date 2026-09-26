# Changelog

## Unreleased

### Changed
- New visual base: lobby teal and brass palette, Big Shoulders Display and Figtree type, shared button, field and card styles.
- Flash messages show both success and error notices in one stack; each stays until dismissed.
- The confirm dialog uses the native dialog element: Escape closes it and focus starts on Cancel.
- Staff pages get a sidebar that marks the current page and folds behind a Menu button on phones.
- The dashboard shows totals for rooms, available rooms, open reservations and guests, recent reservations by room number and status, rooms not available, and new guests.

### Fixed
- Pages no longer print markup before the doctype, which forced browsers into quirks mode.
- Error notices such as "Photo upload failed!" are now shown to staff.
- Dashboard counts were capped at 5 because they counted the latest five records; they now count the whole table.
