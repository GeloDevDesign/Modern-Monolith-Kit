---
name: laravel-development
description: Activates when creating modules, controllers, routes, or handling file uploads in Laravel.
---

# Laravel Best Practices

- **Modular Routing**: Create separate `routes/*.php` files for new modules. Register in `bootstrap/app.php`.
- **File Uploads**: Use `_method: 'PUT'` via `POST` for multipart/form-data updates.
- **Controllers**: Use `$request->safe()`. NEVER `$request->all()`. 
- **Validation**: Always use Form Requests.
- **Naming**: Use descriptive names like `isRegisteredForDiscounts`.
