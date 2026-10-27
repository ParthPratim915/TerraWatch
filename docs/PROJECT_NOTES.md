# Project notes

## Architecture

- `public/`: browser-facing pages and static assets
- `app/bootstrap.php`: configuration loading, PDO connection, escaping, CSRF helpers
- `app/layout/`: shared header and footer
- `config/config.example.php`: example local configuration
- `database/schema.sql`: database creation and demo seed data

## Current limitations

- Incident entries submitted through the form are stored as demo/unverified records.
- No authentication or moderation queue exists yet.
- No live external data source is connected.
- The globe on the homepage is a CSS illustration, not a geographic map.
- The included records are illustrative and must not be interpreted as current alerts.

## Good next PRs

1. Add Leaflet and map pins based on incident coordinates.
2. Add USGS earthquake GeoJSON integration with caching and source attribution.
3. Add an admin role and review queue for public submissions.
4. Add tests for input validation, filtering, and CSRF handling.
5. Add pagination controls that preserve filters and accessibility improvements.
