# Project Context & Architecture

## Tech Stack
- **Framework**: Laravel 11 (Backend) + Vue 3 (Frontend) + Inertia.js v2 (Glue).
- **UI Library**: PrimeVue v4 (Aura Theme).
- **Styling**: Tailwind CSS v4.
- **Database**: MySQL.
- **Icons**: PrimeIcons.

## Directory Structure
- **Controllers**: `app/Http/Controllers/`
- **Models**: `app/Models/`
- **Frontend Pages**: `resources/js/Pages/`
- **Components**: `resources/js/Components/` (Global reusable components)
- **Layouts**: `resources/js/Layouts/` (`AuthenticatedLayout.vue`, `GuestLayout.vue`)

## Key Conventions
- **Routing**: Use `routes/web.php` for main routes. For new modules, create separate route files in `routes/` and register them.
- **State Management**: Uses Pinia (if applicable) or Inertia shared props.
- **Permissions**: Role-based access control via `auth.role` middleware (e.g., `auth.role:admin,user`).

## Development Workflow
- **Adding Pages**: Create Vue component in `Pages/`, add route in `web.php` pointing to Controller.
- **Sidebar**: Manage sidebar links in `resources/js/routes.js`.
- **Database**: Use Migrations and Seeders for all schema/data changes.
