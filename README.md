# TerraWatch — Natural Disaster & Earth Observation Portal

TerraWatch is a ready-to-customize PHP + MySQL portfolio project for exploring and managing natural hazard events such as earthquakes, floods, wildfires, cyclones, and volcanic activity.

## Features

- Responsive landing page and monitoring dashboard
- Summary cards for active events, high-severity events, and hazard categories
- Search and filters for hazard type, severity, and text
- Incident detail pages with location, coordinates, magnitude/intensity, status, and source notes
- Submit-an-event form with server-side validation and CSRF protection
- PDO prepared statements and output escaping
- MySQL schema and realistic **demo-only** seed records
- Lightweight JavaScript filtering helpers and responsive CSS
- No API key required to run the starter

## Important data note

The included incidents are sample/demo records, not a live emergency feed. Do not use this project as an operational emergency warning service. A future enhancement could integrate trusted sources such as USGS earthquake feeds, NASA EONET, or official national meteorological and disaster-management agencies. Verify each provider's terms, attribution requirements, update cadence, and data quality before publishing live data.

## Requirements

- PHP 8.1+
- MySQL 8.0+ or a compatible MariaDB version
- PHP PDO MySQL extension enabled
- Apache/Nginx or PHP's built-in development server

## Setup (XAMPP / local)

1. Copy the `TerraWatch` folder into your web server's document root (for XAMPP, usually `htdocs`).
2. Start Apache and MySQL.
3. Open phpMyAdmin or your MySQL client and import `database/schema.sql`.
   - This creates the `terrawatch` database and adds demo incidents.
4. Copy `config/config.example.php` to `config/config.php`.
5. Edit `config/config.php` with your local database credentials.
6. Open `http://localhost/TerraWatch/public/` in your browser.

If your server serves the `public` folder as the document root, use that configuration instead. Keep `config/` and `database/` outside the public web root where possible.

## Quick development server

From the project root:

```bash
php -S 127.0.0.1:8000 -t public
```

Then visit `http://127.0.0.1:8000`. The project still needs a configured MySQL database.

## Suggested GitHub workflow

1. Create a public repository named `terrawatch-disaster-monitor`.
2. Upload the project files, excluding `config/config.php` and any secrets.
3. Add screenshots to `docs/screenshots/`.
4. Keep the demo-data warning in the README.
5. Add live data integration only after testing source reliability and attribution.

## Suggested next milestones

- Integrate a live earthquake feed (for example, USGS GeoJSON)
- Add a Leaflet map with event markers
- Add user accounts and saved filters
- Add pagination and server-side rate limiting
- Build an admin review queue for submitted incidents
- Add automated tests and deployment instructions

## Security notes

This is a learning starter, not a fully audited production emergency-management system. Before public deployment, add HTTPS, rate limiting, spam controls, logging, a review/moderation process, backups, and production-specific security configuration. Do not commit database passwords or other secrets.
