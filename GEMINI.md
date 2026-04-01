# Project: Slim Skeleton PHP Project

## General Instructions

- When generating new PHP code, follow the existing MVC structure.
- Keep the code DRY (Don't Repeat Yourself) and minimal.
- Respect MVC principles: Controllers → logic, Models → data, Views → display/render.
- Do not create or modify the `app` folder.

---

## Project constraints

- **Do not create or modify the `app` folder.**
- Respect the existing **MVC structure**:
  - Controllers → application logic.
  - Models → data access and domain logic.
  - Views → HTML rendering only (no business logic).
- Prefer editing existing files over creating new ones unless the feature clearly requires new classes or views.
- If you update core structure, make sure you do not alterate Slim structure too much, keep it simple as possible and reusable, also run test afterwards using phpunit to prevent any errors

---

## Coding Style

- Use 4 spaces for indentation.
- Always type-hint method parameters and return types where possible.
- Use minimal boilerplate; only include necessary code.
- Routes should be declared in `src/routes.php` using Slim's routing syntax.

# Rendering and routing rules for the Slim Skeleton project

## Routes

All routes are accessible in `app/routes.php`

## Controller

All controller are located in `src/Application/Actions/`

## View rendering

HTML rendering must **always** be done via:

```php
$response->renderHtml('view-name');
```

• Views are already sanitized upstream by the controller.

• Views are written in HTML, PHP without logic, and use TailwindCSS.

• To output a raw (unescaped) value inside a view, explicitly use:

```php
$var->dangerousRaw();
```

• Any output that does not come from `dangerousRaw()` should be considered already escaped/safe on the view side.

Using routes in code

• URLs must be generated via the routing system, never hard‑coded.

• To generate the URL of a named route, by its name use:
`$this->route('my-route-name');`

• Any link or redirect must rely on `$this->route('route-name')` to stay decoupled from the actual path and keep routing DRY and maintainable.

---

Template:

Color:
Only use those colors, do not use other color :
`<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#0f172a', // Prussian Blue
                    secondary: '#475569', // Blue Slate
                    background: '#f8fafc', // Bright Snow
                    accent: '#007ea7', // Cerulean
                    success: '#10b981', // Mint Leaf
                }
            }
        }
    }
</script>`

Code must read only this colors as variables, ex: for prussian blue, use `text-primary`, for blue state, use `text-secondary`

For layout, use only `layout/header.php` and `layout/footer.php` and respect modern SAAS convention, really beautiful
