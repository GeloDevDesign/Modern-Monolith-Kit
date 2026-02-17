# Behavior Rules

## Laravel Best Practices
- When working with Laravel, always provide best practice suggestions.
- Ensure solutions align with the "Laravel Way" and modern framework standards.
- Proactively suggest improvements for code quality, security, and performance.

## File Uploads
- **File Uploads on Update**: When updating records that include files or images, ALWAYS use Method Spoofing. Send a POST request with `_method` set to 'PUT' or 'PATCH' to ensure `multipart/form-data` is parsed correctly. Do NOT use complex data transformation layers unless a dedicated Edit page is unavailable (e.g., for inline or modal edits).

## Module Development
- **Modular Routing**: When creating a new module, ALWAYS create a separate route file (e.g., `routes/module.php`) instead of adding to `web.php`.
