---
name: component-architecture
description: Activates when working with Vue components, PrimeVue, Inertia forms, or UI/Sidebar styling.
---

# Component Rules

- **DRY**: Check `resources/js/Components/` before creating new ones.
- **PrimeVue (STRICT)**: **NEVER** import PrimeVue components inside `.vue` files. They are already mounted globally in `resources/js/app.ts`. Use them directly in templates.
- **Inertia Forms**: 
  - Bind `:loading="form.processing"` to buttons.
  - Update button text based on state (e.g., "Saving...").
- **Sidebar**: Use standard classes for items:
  `flex items-center cursor-pointer px-3 py-2 rounded text-primary-100 dark:text-zinc-200 hover:bg-primary-800/50 dark:hover:bg-zinc-800 hover:text-white dark:hover:text-white duration-150 transition-colors p-ripple no-underline mt-1`
