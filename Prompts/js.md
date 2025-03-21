### JavaScript Interactivity & Animation Guidelines:
- Every page must include **at least one interactive feature**, such as a modal, accordion, image slider, form validation, or animated counters.
- Add **scroll-based animations** using **AOS.js** or **GSAP** for elements such as headings, images, and call-to-action buttons.
- Include hover effects and transitions for buttons and links using **CSS and JS combined**.
- Generate **modular JavaScript files**:
  - Create a **main.js** file for reusable components (e.g., dark mode toggle, scroll-to-top button, navbar animation).
  - For each page, generate a **dedicated JS file** (e.g., `assets/js/home.js`) with page-specific interactions.
- Animate page elements using **GSAP** for complex motion or **AOS** for quick drop-ins.
- Include script tags in the corresponding HTML/PHP files, linking to the JS files.
- Use **lazy loading** for images and videos for performance.
- Add **dark mode toggle** with persistent user preference (store in `localStorage`).


