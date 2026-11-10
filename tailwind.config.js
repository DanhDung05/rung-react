import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{js,jsx,ts,tsx}',
    './app/View/Components/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: 'var(--color-primary)',
          hover:   'var(--color-primary-hover)',
          light:   'var(--color-primary-light)',
        },
        secondary: {
          DEFAULT: 'var(--color-secondary)',
          hover:   'var(--color-secondary-hover)',
        },
        heading: 'var(--color-heading)',
        text: {
          DEFAULT: 'var(--color-text)',
          muted:   'var(--color-text-muted)',
          subtle:  'var(--color-text-subtle)',
          inverse: 'var(--color-text-inverse)',
        },
        bg: {
          DEFAULT: 'var(--color-bg)',
          subtle:  'var(--color-bg-subtle)',
          muted:   'var(--color-bg-muted)',
        },
        border: {
          DEFAULT: 'var(--color-border)',
          strong:  'var(--color-border-strong)',
        },
        success: 'var(--color-success)',
        warning: 'var(--color-warning)',
        danger:  'var(--color-danger)',
        info:    'var(--color-info)',
      },
      fontFamily: {
        // Giữ nguyên font cũ
        sans:    ['var(--font-sans)', ...defaultTheme.fontFamily.sans],
        heading: ['var(--font-heading)', ...defaultTheme.fontFamily.sans],
        mono:    ['var(--font-mono)', ...defaultTheme.fontFamily.mono],
        // Thêm font viết tay
        script:  ['Caveat', 'cursive'],
      },
      fontSize: {
        xs:   'var(--text-xs)',
        sm:   'var(--text-sm)',
        base: 'var(--text-base)',
        lg:   'var(--text-lg)',
        xl:   'var(--text-xl)',
        '2xl':'var(--text-2xl)',
        '3xl':'var(--text-3xl)',
        '4xl':'var(--text-4xl)',
        '5xl':'var(--text-5xl)',
        '6xl':'var(--text-6xl)',
      },
      spacing: {
        '1':  'var(--space-1)',
        '2':  'var(--space-2)',
        '3':  'var(--space-3)',
        '4':  'var(--space-4)',
        '5':  'var(--space-5)',
        '6':  'var(--space-6)',
        '8':  'var(--space-8)',
        '10': 'var(--space-10)',
        '12': 'var(--space-12)',
        '16': 'var(--space-16)',
        '20': 'var(--space-20)',
        '24': 'var(--space-24)',
      },
      borderRadius: {
        sm:   'var(--radius-sm)',
        md:   'var(--radius-md)',
        lg:   'var(--radius-lg)',
        xl:   'var(--radius-xl)',
        '2xl':'var(--radius-2xl)',
        full: 'var(--radius-full)',
      },
      boxShadow: {
        xs: 'var(--shadow-xs)',
        sm: 'var(--shadow-sm)',
        md: 'var(--shadow-md)',
        lg: 'var(--shadow-lg)',
        xl: 'var(--shadow-xl)',
      },
      maxWidth: {
        'container-sm':  'var(--container-sm)',
        'container-md':  'var(--container-md)',
        'container-lg':  'var(--container-lg)',
        'container-xl':  'var(--container-xl)',
        'container-2xl': 'var(--container-2xl)',
        'container':     'var(--container-max)',
      },
      transitionDuration: {
        fast: '150ms',
        base: '200ms',
        slow: '300ms',
      },
      zIndex: {
        dropdown: 'var(--z-dropdown)',
        sticky:   'var(--z-sticky)',
        fixed:    'var(--z-fixed)',
        modal:    'var(--z-modal)',
        tooltip:  'var(--z-tooltip)',
      },
    },
  },
  plugins: [
    forms,
    typography,
  ],
};