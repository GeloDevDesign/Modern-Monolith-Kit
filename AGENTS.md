<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.3.16
- inertiajs/inertia-laravel (INERTIA) - v2
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- tightenco/ziggy (ZIGGY) - v2
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- @inertiajs/vue3 (INERTIA) - v2
- vue (VUE) - v3
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `pest-testing` — Tests applications using the Pest 4 PHP framework. Activates when writing tests, creating unit or feature tests, adding assertions, testing Livewire components, browser testing, debugging test failures, working with datasets or mocking; or when the user mentions test, spec, TDD, expects, assertion, coverage, or needs to verify functionality works.
- `inertia-vue-development` — Develops Inertia.js v2 Vue client-side applications. Activates when creating Vue pages, forms, or navigation; using &lt;Link&gt;, &lt;Form&gt;, useForm, or router; working with deferred props, prefetching, or polling; or when user mentions Vue with Inertia, Vue pages, Vue forms, or Vue navigation.
- `tailwindcss-development` — Styles applications using Tailwind CSS v4 utilities. Activates when adding styles, restyling components, working with gradients, spacing, layout, flex, grid, responsive design, dark mode, colors, typography, or borders; or when the user mentions CSS, styling, classes, Tailwind, restyle, hero section, cards, buttons, or any visual/UI changes.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies (ULTRA-CONCISE)

- **Be brief**: Skip fluff, intro/outro, and obvious explanations.
- **Code first**: Prioritize showing code or taking action.
- **Minimal summaries**: Only explain what is non-obvious.
- **No yapping**: Keep the conversation efficient.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->

```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/Pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

=== inertia-laravel/v2 rules ===

# Inertia v2

- Use all Inertia features from v1 and v2. Check the documentation before making changes to ensure the correct approach.
- New features: deferred props, infinite scrolling (merging props + `WhenVisible`), lazy loading on scroll, polling, prefetching.
- When using deferred props, add an empty state with a pulsing or animated skeleton.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- **Form Requests**: ALWAYS create Form Request classes for validation (`php artisan make:request`) rather than inline validation in controllers. Include both validation rules and custom error messages.
- **Secure Updates**: In `update` methods, explicitly use `$request->safe()->only([...])` or `$request->safe()->except([...])` to filter out immutable fields. Do not rely on validation rules alone.
- **Data Access**: NEVER use `$request->all()`. STRICTLY use `$request->validated()` or `$request->safe()` to ensure only valid data is processed.
- **Response Handling**: Use Laravel's native redirect methods with session flashing (`return back()->with('success', '...');`). Avoid returning JSON or manual toasts from controllers; the frontend is configured to display session flash notifications automatically.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

# Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.
- CRITICAL: ALWAYS use `search-docs` tool for version-specific Pest documentation and updated code examples.
- IMPORTANT: Activate `pest-testing` every time you're working with a Pest or testing-related task.

=== inertia-vue/core rules ===

# Inertia + Vue

- Vue components must have a single root element.
- **PrimeVue Usage**: Register all PrimeVue components globally in `resources/js/app.js`. **Do not** import them locally within `.vue` files.
- **Form Buttons**: Bind the `:loading` prop to `form.processing`. Text must update (e.g., `<Button :loading="form.processing">{{ form.processing ? 'Saving...' : 'Save' }}</Button>`).
- **Sidebar Consistency**: For sidebar items, use standard classes: `flex items-center cursor-pointer px-3 py-2 rounded text-primary-100 dark:text-zinc-200 hover:bg-primary-800/50 dark:hover:bg-zinc-800 hover:text-white dark:hover:text-white duration-150 transition-colors p-ripple no-underline mt-1`.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

=== tailwindcss/core rules ===

# Tailwind CSS

- Always use existing Tailwind conventions; check project patterns before adding new ones.
- IMPORTANT: Always use `search-docs` tool for version-specific Tailwind CSS documentation and updated code examples. Never rely on training data.
- IMPORTANT: Activate `tailwindcss-development` every time you're working with a Tailwind CSS or styling-related task.
  </laravel-boost-guidelines>

<!-- ============================= -->
<!-- PROJECT-SPECIFIC CUSTOM RULES -->
<!-- ============================= -->

# Project Context

- **Stack**: Laravel 12 + Vue 3 + Inertia.js v2 + PrimeVue 4 (Aura) + Tailwind CSS 4
- **Database**: MySQL
- **Icons**: PrimeIcons
- **State**: Pinia or Inertia shared props
- **Permissions**: Role-based via `auth.role` middleware (e.g., `auth.role:admin,user`)

## Key Directories

| Purpose       | Path                       |
| ------------- | -------------------------- |
| Controllers   | `app/Http/Controllers/`    |
| Models        | `app/Models/`              |
| Repositories  | `app/Repositories/`        |
| Pages (Vue)   | `resources/js/Pages/`      |
| Components    | `resources/js/Components/` |
| Layouts       | `resources/js/Layouts/`    |
| Sidebar Links | `resources/js/routes.js`   |

# Custom Rules

## File Uploads

- On **update** with files/images, use Method Spoofing: send `POST` with `_method: 'PUT'` so `multipart/form-data` is parsed correctly.

## Modular Routing

- New modules get their own route file (e.g., `routes/users.php`). Register it in `bootstrap/app.php`. Don't dump everything in `web.php`.

## Repository Pattern (Simplified)

- **NO INTERFACES**: Never create or use Interfaces for the Repository pattern.
- **BASE REPOSITORY**: Use `app/Repositories/BaseRepository.php` that handles:
    - `all()`: Get all records.
    - `paginate(int $perPage)`: Standard pagination.
    - `find(int|string $id)`: Uses `findOrFail($id)` to throw 404s automatically.
    - `findWhere(string $column, mixed $value)`: Get collection by column.
    - `findFirstWhere(string $column, mixed $value)`: Uses `where($column, $value)->firstOrFail()`.
    - `create(array $data)`: Standard model creation.
    - `update(int|string $id, array $data)`: Find and update record.
    - `delete(int|string $id)`: Find and delete record.
- **CONCRETE REPOSITORIES**: Every module must have a concrete Repository class (e.g., `UserRepository`) that extends `BaseRepository`.
- **CONSTRUCTOR**: The concrete repository must contain a constructor that passes the specific Eloquent Model to the parent `BaseRepository`.
- **DIRECT DI**: In Controllers, use Dependency Injection to inject the concrete Repository class directly (e.g., `protected UserRepository $userRepo`).
- **MINIMAL CHANGES**: Modify only the minimum lines necessary and do not refactor or restyle existing UI/logic unless explicitly requested.

## Components

- Check `resources/js/Components/` before creating new ones. Maximize reuse and respect existing props.
- PrimeVue: global registration only in `resources/js/app.js`.
- Form buttons: always bind `:loading="form.processing"` and show dynamic label.

## Development Workflow

- **New Page**: Create Vue component in `Pages/`, add route, add controller method.
- **Sidebar**: Update sidebar links in `resources/js/routes.js`.
- **Database**: Always use Migrations + Seeders.

# Auto-Documentation (MANDATORY)

Whenever you create, modify, or delete a module, you MUST update `docs/modules.md`.

## Rules

1. After completing any module-related work (new module, new feature in a module, structural change), update `docs/modules.md`.
2. Each module entry must include: name, short description, key features (bullets), related files.
3. Keep entries concise — no more than 10 lines per module.
4. If `docs/modules.md` does not exist, create it.
5. This is **not optional**. Every module change = doc update.

## Format

```markdown
# Module Documentation

> Auto-maintained by AI. Last updated: YYYY-MM-DD

## Module Name

Short description of what this module does.

- **Feature 1**: Brief explanation
- **Feature 2**: Brief explanation

**Files**: `Controllers/XController.php`, `Pages/X/Index.vue`, `routes/x.php`, `Models/X.php`

---
```
