---
name: FarmaVida Design System
colors:
  surface: '#fbf9f8'
  surface-dim: '#dbd9d9'
  surface-bright: '#fbf9f8'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f5f3f3'
  surface-container: '#efeded'
  surface-container-high: '#eae8e7'
  surface-container-highest: '#e4e2e2'
  on-surface: '#1b1c1c'
  on-surface-variant: '#3f4946'
  inverse-surface: '#303030'
  inverse-on-surface: '#f2f0f0'
  outline: '#6f7976'
  outline-variant: '#bfc9c5'
  surface-tint: '#28695c'
  primary: '#28695c'
  on-primary: '#ffffff'
  primary-container: '#98d8c8'
  on-primary-container: '#1d6053'
  inverse-primary: '#93d3c3'
  secondary: '#416373'
  on-secondary: '#ffffff'
  secondary-container: '#c4e8fb'
  on-secondary-container: '#476979'
  tertiary: '#874f4c'
  on-tertiary: '#ffffff'
  tertiary-container: '#ffbbb6'
  on-tertiary-container: '#7d4744'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#afefdf'
  primary-fixed-dim: '#93d3c3'
  on-primary-fixed: '#00201a'
  on-primary-fixed-variant: '#045044'
  secondary-fixed: '#c4e8fb'
  secondary-fixed-dim: '#a9ccde'
  on-secondary-fixed: '#001f2a'
  on-secondary-fixed-variant: '#294b5b'
  tertiary-fixed: '#ffdad7'
  tertiary-fixed-dim: '#fcb4b0'
  on-tertiary-fixed: '#360e0d'
  on-tertiary-fixed-variant: '#6b3836'
  background: '#fbf9f8'
  on-background: '#1b1c1c'
  surface-variant: '#e4e2e2'
typography:
  headline-xl:
    fontFamily: Quicksand
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Quicksand
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Quicksand
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 36px
  headline-md:
    fontFamily: Quicksand
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  body-lg:
    fontFamily: Quicksand
    fontSize: 18px
    fontWeight: '500'
    lineHeight: 28px
  body-md:
    fontFamily: Quicksand
    fontSize: 16px
    fontWeight: '500'
    lineHeight: 24px
  label-md:
    fontFamily: Quicksand
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.02em
  label-sm:
    fontFamily: Quicksand
    fontSize: 12px
    fontWeight: '700'
    lineHeight: 16px
    letterSpacing: 0.04em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 48px
  xl: 80px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 40px
---

## Brand & Style
The design system is centered on a "Healing Nature" narrative, blending modern minimalism with organic warmth. It aims to reduce the clinical anxiety often associated with pharmacy management by providing an interface that feels more like a wellness retreat than a corporate database. 

The aesthetic is **Organic Minimalism**, characterized by generous whitespace, soft environmental tones, and a lack of harsh structural boundaries. The system prioritizes breathing room, ensuring users feel calm and focused while navigating complex pharmaceutical data. It evokes trust through clarity and accessibility, rather than rigid authority.

## Colors
The palette is rooted in botanical and atmospheric tones. 
- **Primary (Mint Green):** Used for main actions, active states, and brand highlights to symbolize vitality and health.
- **Secondary (Sky Blue):** Utilized for information hierarchy, calm indicators, and supportive UI elements.
- **Accent (Coral):** Reserved for notifications, soft warnings, and items requiring gentle attention.
- **Neutral (Slate Gray):** Employed for all typography to maintain high legibility without the jarring contrast of pure black.
- **Backgrounds:** A tiered system of Off-white (#FAFAFA) for the base canvas and Pure White (#FFFFFF) for elevated interactive components.

## Typography
The system uses **Quicksand** exclusively to maintain a cohesive, friendly, and approachable character. Its rounded terminals mirror the organic shapes of the UI. 

- **Headlines:** Use SemiBold and Bold weights with slight negative letter spacing to create a grounded, trustworthy presence.
- **Body:** Set primarily in Medium weight (500) to ensure the text feels substantial and legible against soft backgrounds.
- **Labels:** Use uppercase and increased letter spacing for small metadata to differentiate from body prose without needing high-contrast color shifts.

## Layout & Spacing
This design system utilizes a **Fluid Grid** with expanded outer margins to emphasize the minimalist aesthetic.

- **Desktop (1440px+):** 12-column grid, 40px side margins, 24px gutters. Content is often centered in a max-width container of 1200px to prevent excessive line lengths.
- **Tablet (768px - 1024px):** 8-column grid, 24px margins, 16px gutters.
- **Mobile (Up to 767px):** 4-column grid, 16px margins, 16px gutters.

Spacing follows an 8px base rhythm. Significant vertical gaps (48px - 80px) are encouraged between major content sections to maintain the "spacious" feel.

## Elevation & Depth
Depth is conveyed through **Ambient Shadows** and **Tonal Layering**. The goal is a "Floating" effect where elements feel light and suspended.

- **Shadow Profile:** Very diffused, low-opacity shadows (e.g., `0 4px 15px rgba(0,0,0,0.06)`). Shadows should use a subtle tint of the primary color in dark mode, but remain neutral gray in light mode.
- **Layering:** Background (#FAFAFA) -> Surface Card (#FFFFFF) -> Hover State (Increased shadow spread + 2px upward Y-translation).
- **Interactions:** When an element is pressed, it should "sink" back toward the background by reducing the shadow spread and Y-offset.

## Shapes
Shapes are defined by high-radius curves to eliminate "sharp" clinical corners. 

- **Standard Elements (Buttons, Inputs):** 16px (1rem) corner radius.
- **Cards and Containers:** 24px (1.5rem) corner radius.
- **Specialty Elements (Chips, Pills):** Fully rounded (height / 2).
- **Focus States:** Follow the radius of the parent element with a 4px offset soft glow.

## Components
- **Floating Cards:** Main containers for content. Always pure white with 24px padding and the system's ambient shadow. No borders.
- **Horizontal Navigation:** Minimalist top-bar placement. Hover states use a 3px thick, rounded-cap underline in Mint Green. Active links are SemiBold.
- **Border-less Tables:** Rows are separated by whitespace rather than lines. Row hover triggers a slight background color shift to #F5F5F5 and a soft elevation lift.
- **Rounded Inputs:** 16px radius, soft light-gray background (#F0F0F0). On focus, the background turns white and gains a soft Sky Blue outer glow.
- **Notification Bubbles:** Replaces standard alerts. These are small, pill-shaped floating elements with a Primary or Accent background, typically appearing in the top-right or bottom-center.
- **Buttons:** Primary buttons use a solid Mint Green fill with white text. Secondary buttons are Sky Blue with Slate Gray text. All buttons have a 16px radius and "squishy" press transitions.
- **Icons:** Use hand-drawn or soft-stroke icons with rounded ends. Thematic motifs (leaf, drop, sun) should be integrated into empty states and illustrative headers.