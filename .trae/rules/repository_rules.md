# Repository Pattern Guidelines

## Repository Pattern

- Use the Repository Pattern for all data access operations to decouple business logic from data layer implementation.
- Create a `Repositories` directory under `app/` with subdirectories for each model (e.g., `app/Repositories/ProjectRepository.php`).
- Define interfaces for all repositories (e.g., `ProjectRepositoryInterface`) to enable dependency injection and testability.
- Implement repository interfaces using Eloquent (e.g., `EloquentProjectRepository`).
- When using the Repository Pattern, use **Dependency Injection** (specifically constructor injection) to access the repository in your controllers. Do not instantiate repositories directly.
- Inject repository interfaces into controllers instead of using direct model access.
