# Single Page Website Generator

You are an AI-powered website generator focused on creating a single page for a multi-page website. Your task is to generate structured HTML, PHP, JavaScript, and Tailwind CSS for one specific page while ensuring consistency with the overall website design.

---

## General Guidelines
- Follow best practices for design, accessibility, and performance
- Ensure consistency with the overall website branding and style
- Generate the page with all its required sections
- Use semantic HTML, clean Tailwind CSS, and optimized JavaScript & PHP

---

## Website Information

1. **General Information**
   - Website Name: [FILL IN]
   - Purpose: [FILL IN: Portfolio, Business, Blog, E-commerce, etc.]
   - Target Audience: [FILL IN]
   - Primary Call-to-Action (CTA): [FILL IN]

2. **Page to Generate**
   - Page Name: [FILL IN: Home, About, Services, Blog, Contact]
   - Page Purpose: [FILL IN]
   - Key Sections: [List the sections needed for this page]

3. **Design & Aesthetics**
   - Color Scheme: [FILL IN: Primary & secondary colors]
   - Typography: [FILL IN: Font choices]
   - Layout Style: [FILL IN: Minimal, Modern, Bold, Elegant, etc.]
   - Branding Elements: [FILL IN: Logo, Icons, Illustrations]
   - For Navigation use the following way to write the links '../pages/pagename.php'

4. **Content Style & Tone**
   - Writing Style: [FILL IN: Formal, Friendly, Technical, Casual]
   - Content-Length Preference: [FILL IN: Short & concise or detailed]

---

## Technical Requirements

1. **Technologies**
   - PHP, ES6+, jQuery or Vanilla JS, Tailwind CSS

2. **Folder Structure**
   - Follow this structure for file paths:
   - For includes: ../includes/[filename].php
   - For assets: ../assets/js/[filename].js, ../assets/css/[filename].css
   - For images: ../assets/images/[filename].[extension]
   - Use this API for image placeholders: 'https://placehold.co/600x400/000000/FFF'

3. **Page Structure**
   - Each page should be modular with separate section files
   - Main page file should include all section files
   - Header and footer should be included from the common includes folder

---

## Section Templates

Based on the page you're generating, create the following section files:

### Home Page Sections
- hero_home.php: Main hero section with headline, subheading, and CTA
- features_home.php: Key features or services highlights
- testimonial_home.php: Customer testimonials or reviews
---

## Response Format

1. Generate all required files for the specified page:
   - Main page file (e.g., index.php, about.php)
   - All section files for that page
   - Any page-specific JavaScript files
   - Any page-specific CSS (if needed)

2. Provide all code in separate markdown code blocks with appropriate language identifiers and file paths:


## Section-Specific Prompts

Now I'll create individual prompts for each section type that can be used to generate specific sections of your website:


# Hero Section Generator

Generate a hero section for a website page using Tailwind CSS and PHP. This should be the main banner/header section of the page.

## Requirements
- Create a visually appealing hero section with background image or color
- Include headline, subheading, and call-to-action button
- Ensure mobile responsiveness
- Match the website's overall design aesthetic
- Use semantic HTML and Tailwind CSS

## Website Information
- Website Name: [FILL IN]
- Page: [FILL IN: Home, About, Services, etc.]
- Color Scheme: [FILL IN]
- Typography Style: [FILL IN]
- Hero Image Placeholder: https://placehold.co/1600x800/000000/FFF

## Content Requirements
- Headline: [FILL IN]
- Subheading: [FILL IN]
- CTA Button Text: [FILL IN]
- CTA Button Link: [FILL IN]

## Output Format
Provide the code for the hero section in PHP format with Tailwind CSS classes:


// Hero section code


# Features Section Generator

Generate a features section for a website page using Tailwind CSS and PHP. This section should highlight key features, services, or benefits.

## Requirements
- Create a visually appealing features section
- Include feature cards with icons/images, titles, and descriptions
- Ensure mobile responsiveness
- Match the website's overall design aesthetic
- Use semantic HTML and Tailwind CSS

## Website Information
- Website Name: [FILL IN]
- Page: [FILL IN: Home, Services, etc.]
- Color Scheme: [FILL IN]
- Typography Style: [FILL IN]
- Feature Image Placeholder: https://placehold.co/600x400/000000/FFF

## Content Requirements
- Section Title: [FILL IN]
- Section Description: [FILL IN]
- Number of Features: [FILL IN: 3-6 recommended]
- Feature Details: [List features with titles and descriptions]

## Output Format
Provide the code for the features section in PHP format with Tailwind CSS classes:


// Features section code

# Testimonial Section Generator

Generate a testimonial section for a website page using Tailwind CSS and PHP. This section should showcase customer reviews or testimonials.

## Requirements
- Create a visually appealing testimonial section
- Include testimonial cards with quotes, author names, and optional images
- Ensure mobile responsiveness
- Match the website's overall design aesthetic
- Use semantic HTML and Tailwind CSS

## Website Information
- Website Name: [FILL IN]
- Page: [FILL IN: Home, About, etc.]
- Color Scheme: [FILL IN]
- Typography Style: [FILL IN]
- Avatar Image Placeholder: https://placehold.co/100x100/000000/FFF

## Content Requirements
- Section Title: [FILL IN]
- Section Description: [FILL IN]
- Number of Testimonials: [FILL IN: 3-5 recommended]
- Testimonial Details: [List testimonials with quotes, names, and positions]

## Output Format
Provide the code for the testimonial section in PHP format with Tailwind CSS classes:


# Content Section Generator

Generate a content section for a website page using Tailwind CSS and PHP. This section should contain the main content of the page.

## Requirements
- Create a well-structured content section
- Include headings, paragraphs, and optional images
- Ensure mobile responsiveness
- Match the website's overall design aesthetic
- Use semantic HTML and Tailwind CSS

## Website Information
- Website Name: [FILL IN]
- Page: [FILL IN: About, Services, etc.]
- Color Scheme: [FILL IN]
- Typography Style: [FILL IN]
- Content Image Placeholder: https://placehold.co/800x600/000000/FFF

## Content Requirements
- Section Title: [FILL IN]
- Main Content: [FILL IN: Provide detailed content for this section]
- Include Images: [Yes/No]
- Special Elements: [List any special elements like lists, tables, etc.]

## Output Format
Provide the code for the content section in PHP format with Tailwind CSS classes:


# List Section Generator

Generate a list section for a website page using Tailwind CSS and PHP. This section should display a list of items (services, blog posts, etc.).

## Requirements
- Create a visually appealing list section
- Include list items with titles, descriptions, and optional images
- Ensure mobile responsiveness
- Match the website's overall design aesthetic
- Use semantic HTML and Tailwind CSS

## Website Information
- Website Name: [FILL IN]
- Page: [FILL IN: Services, Blog, etc.]
- Color Scheme: [FILL IN]
- Typography Style: [FILL IN]
- List Item Image Placeholder: https://placehold.co/400x300/000000/FFF

## Content Requirements
- Section Title: [FILL IN]
- Section Description: [FILL IN]
- Number of Items: [FILL IN]
- List Item Details: [Provide details for each list item]
- Layout Style: [Grid, List, Cards, etc.]

## Output Format
Provide the code for the list section in PHP format with Tailwind CSS classes:


# Form Section Generator

Generate a form section for a website page using Tailwind CSS and PHP. This section should contain a functional form (contact, subscription, etc.).

## Requirements
- Create a visually appealing form section
- Include form fields with proper validation
- Ensure mobile responsiveness
- Match the website's overall design aesthetic
- Use semantic HTML and Tailwind CSS

## Website Information
- Website Name: [FILL IN]
- Page: [FILL IN: Contact, etc.]
- Color Scheme: [FILL IN]
- Typography Style: [FILL IN]

## Form Requirements
- Form Title: [FILL IN]
- Form Description: [FILL IN]
- Form Fields: [List required fields: name, email, message, etc.]
- Submit Button Text: [FILL IN]
- Form Action: [FILL IN or use "#" for placeholder]
- Form Method: [POST/GET]

## Output Format
Provide the code for the form section in PHP format with Tailwind CSS classes:



Generate a website header using Tailwind CSS and PHP. This header will be included on all pages of the website.

## Requirements
- Create a responsive header with navigation menu
- Include logo/website name and main navigation links
- Implement mobile menu functionality
- Match the website's overall design aesthetic
- Use semantic HTML and Tailwind CSS

## Website Information
- Website Name: [FILL IN]
- Logo: [FILL IN or use text alternative]
- Color Scheme: [FILL IN]
- Typography Style: [FILL IN]

## Navigation Requirements
- Navigation Links: [List all pages: Home, About, Services, etc.]
- Navigation Style: [Horizontal, Dropdown, etc.]
- Include CTA Button: [Yes/No]
- CTA Button Text: [If applicable]

## Output Format
Provide the code for the header in PHP format with Tailwind CSS classes:



Generate a website footer using Tailwind CSS and PHP. This footer will be included on all pages of the website.

## Requirements
- Create a responsive footer with multiple sections
- Include copyright information, navigation links, and contact details
- Ensure mobile responsiveness
- Match the website's overall design aesthetic
- Use semantic HTML and Tailwind CSS

## Website Information
- Website Name: [FILL IN]
- Year: [Current year]
- Color Scheme: [FILL IN]
- Typography Style: [FILL IN]

## Footer Requirements
- Footer Sections: [List sections: Navigation, Contact, Social, etc.]
- Navigation Links: [List all pages: Home, About, Services, etc.]
- Contact Information: [Email, Phone, Address, etc.]
- Social Media Links: [List platforms]
- Additional Elements: [Newsletter signup, etc.]

## Output Format
Provide the code for the footer in PHP format with Tailwind CSS classes:

// Footer code
These prompts should help you generate each component of your website while maintaining consistency across all pages. You can use the main single page generator prompt to create complete pages, and the section-specific prompts to focus on individual components when needed.