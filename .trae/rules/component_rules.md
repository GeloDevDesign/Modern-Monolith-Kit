# Component Usage Rules

## Component Usage
- All reusable components are located in `resources/js/Components/**`.
- When using these components, you MUST check their defined props and maximize their usage.
- Always prefer using existing component props over re-implementing logic or styles manually.
- If a component has a prop that achieves the desired effect (e.g., `isWide`), use it instead of adding custom classes.
- When creating a new file or UI element, you MUST first look up the `resources/js/Components` directory to see if a suitable component already exists.
- Maximize the usage of all existing components to avoid duplication.

## PrimeVue Components
- When using a new PrimeVue component, import and register it globally in `resources/js/app.js` using `.component()`. Do not import PrimeVue components locally in Vue files.

## Form Buttons
- When using a Button component inside a form, ALWAYS bind the `loading` prop to `form.processing` (from Inertia `useForm`).
- The button label/text MUST dynamically reflect the state (e.g., "Save" normally, "Saving..." when processing).

## Sidebar Item Styling
- **Standard Classes**: When creating or modifying sidebar items, use the following Tailwind classes for consistency:
  `class="flex items-center cursor-pointer px-3 py-2 rounded text-primary-100 dark:text-zinc-200 hover:bg-primary-800/50 dark:hover:bg-zinc-800 hover:text-white dark:hover:text-white duration-150 transition-colors p-ripple no-underline mt-1"`
- **Reference**: See `resources/js/components/SidebarItem.vue`.
