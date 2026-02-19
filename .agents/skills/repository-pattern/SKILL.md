---
name: repository-pattern
description: Activates when creating data access layers, services, or repository classes.
---

# Repository Pattern

- **Decoupling**: Use repositories to decouple logic from the data layer.
- **Location**: `app/Repositories/{Model}Repository.php`.
- **Interfaces**: Define interfaces for all repositories.
- **Inversion of Control**: Use constructor injection for repository interfaces in controllers.
- **Eloquent**: Implement interfaces using Eloquent models.
