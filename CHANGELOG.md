# Changelog

## Unreleased

### Changed
- New visual base: lobby teal and brass palette, Big Shoulders Display and Figtree type, shared button, field and card styles.
- Flash messages show both success and error notices in one stack; each stays until dismissed.
- The confirm dialog uses the native dialog element: Escape closes it and focus starts on Cancel.
- Staff pages get a sidebar that marks the current page and folds behind a Menu button on phones.
- The dashboard shows totals for rooms, available rooms, open reservations and guests, recent reservations by room number and status, rooms not available, and new guests.
- The staff rooms list has a search that keeps what was typed, a status filter, pages of 12 rooms ordered by room number, and room cards with a brass key tag.
- Add room and Edit room share one form with errors under each field, photo previews before upload, and photos can be added when editing. Saving an edit returns to the room info page.

### Fixed
- Pages no longer print markup before the doctype, which forced browsers into quirks mode.
- Error notices such as "Photo upload failed!" are now shown to staff.
- Dashboard counts were capped at 5 because they counted the latest five records; they now count the whole table.
- The staff rooms list showed only 10 rooms; every room is now reachable through the pager.
- Room photos that are missing or fail to load show "No photo yet" instead of a broken image.
- Whole number prices such as "100" are accepted when adding or editing a room.
- An invalid photo no longer leaves a half saved room behind; the room is only created once every photo passes validation.

### Security
- Uploaded room photos are stored under a name and extension taken from the file content, not the uploaded file name, so an image renamed to .html can no longer be served as a web page.
