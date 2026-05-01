# SEO Optimization Summary - Lanyard Bagus Website

## Perubahan SEO & SSR yang Dilakukan

### 1. Struktur HTML Semantic

#### +page.svelte
- **Added `<main>` wrapper** dengan `id="main-content"` untuk semantic structure
- **Added "Skip to content" link** untuk accessibility (screen readers)
- **SSR Fallback Hero**: Konten hero dengan H1 yang tampil di HTML awal tanpa JavaScript
- **Semantic `<section>` tags** dengan proper `aria-labelledby` attributes

#### PublicHeader.svelte
- **`<header role="banner">`** - semantic header element
- **`<nav aria-label="Navigasi Utama">`** - semantic navigation
- **Descriptive aria-labels** untuk logo dan navigation links

### 2. Heading Hierarchy (H1 → H2 → H3)

```
H1: Hero Section (Cetak Lanyard Custom Premium)
├── H2: Problem Section (Membangun Identitas...)
│   └── H3: Problem Cards (Tenggat Waktu Kritis, etc.)
├── H2: Order Section (Isi Detail Pesanan)
├── H2: Story Section (Perjalanan Menciptakan Kebanggaan)
│   └── H3: Story Cards (Material Pilihan, etc.)
├── H2: Product Section (Pilih Kanvas...)
│   └── H3: Product Cards (Printing Premium, etc.)
├── H2: Pricing Section (Estimasi Daftar Harga)
├── H2: FAQ Section (Paling Sering Ditanyakan)
└── H2: Blog Section (Wawasan Identitas)

Outside main:
H2: CTA Section (Setiap Tim Hebat...)
```

### 3. Meta Tags & Head Optimization

#### Charset & Viewport
```html
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
```

#### Robots Meta
```html
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<meta name="googlebot" content="index, follow" />
<meta name="bingbot" content="index, follow" />
```

#### Canonical & Hreflang
```html
<link rel="canonical" href="https://lanyardbagus.com/" />
<link rel="alternate" hreflang="id" href="https://lanyardbagus.com/" />
<link rel="alternate" hreflang="x-default" href="https://lanyardbagus.com/" />
```

### 4. Open Graph (Facebook/WhatsApp)

```html
<meta property="og:type" content="website" />
<meta property="og:site_name" content={siteTitle} />
<meta property="og:url" content="https://lanyardbagus.com/" />
<meta property="og:title" content={homeTitle} />
<meta property="og:description" content={homeDescription} />
<meta property="og:locale" content="id_ID" />
<meta property="og:image" content={homeOgImage} />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:alt" content="..." />
```

### 5. Twitter Card

```html
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@lanyardbagus" />
<meta name="twitter:creator" content="@lanyardbagus" />
<meta name="twitter:title" content={homeTitle} />
<meta name="twitter:description" content={homeDescription} />
<meta name="twitter:image" content={homeOgImage} />
```

### 6. Schema.org Structured Data (JSON-LD)

Implemented `@graph` array with multiple schema types:

#### Organization
```json
{
  "@type": "Organization",
  "@id": "https://lanyardbagus.com/#organization",
  "name": siteTitle,
  "url": "https://lanyardbagus.com",
  "logo": { "@type": "ImageObject", "url": homeLogo },
  "sameAs": ["https://wa.me/...", "https://instagram.com/lanyardbagus"],
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+62-...",
    "contactType": "customer service",
    "availableLanguage": ["Indonesian"],
    "areaServed": "ID"
  }
}
```

#### LocalBusiness
```json
{
  "@type": "LocalBusiness",
  "@id": "https://lanyardbagus.com/#localbusiness",
  "name": siteTitle,
  "image": homeLogo,
  "url": "https://lanyardbagus.com",
  "telephone": "+62-...",
  "priceRange": "$$",
  "address": { "@type": "PostalAddress", "addressCountry": "ID", "addressRegion": "Jawa Barat" },
  "geo": { "@type": "GeoCoordinates", "latitude": "-6.9175", "longitude": "107.6191" },
  "openingHoursSpecification": { ... }
}
```

#### Service
```json
{
  "@type": "Service",
  "@id": "https://lanyardbagus.com/#service",
  "name": "Lanyard Bagus - Cetak Lanyard Custom Premium",
  "serviceType": "Custom Lanyard Printing",
  "description": homeDescription,
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "itemListElement": [
      {
        "@type": "Offer",
        "itemOffered": { "@type": "Service", "name": "Custom Lanyard Printing Premium (2cm)" },
        "priceCurrency": "IDR",
        "price": "12500"
      }
    ]
  }
}
```

#### WebSite & WebPage
```json
{
  "@type": "WebSite",
  "@id": "https://lanyardbagus.com/#website",
  "url": "https://lanyardbagus.com",
  "name": siteTitle,
  "inLanguage": "id"
},
{
  "@type": "WebPage",
  "@id": "https://lanyardbagus.com/#webpage",
  "name": homeTitle,
  "description": homeDescription,
  "inLanguage": "id",
  "potentialAction": {
    "@type": "SearchAction",
    "target": { "@type": "EntryPoint", "urlTemplate": "https://lanyardbagus.com/search?q={search_term_string}" },
    "query-input": "required name=search_term_string"
  }
}
```

### 7. Performance Optimizations

#### vite.config.js
```javascript
build: {
  cssCodeSplit: true,      // Extract CSS to separate files
  cssMinify: true,         // Minify CSS for production
  rollupOptions: {
    output: { manualChunks: undefined }
  }
},
css: {
  devSourcemap: true,
  transformer: 'postcss'   // Don't inject CSS into DOM during SSR
}
```

#### svelte.config.js
```javascript
kit: {
  inlineStyleThreshold: 0,  // Prevent CSS from being inlined into HTML
  // ...
}
```

#### app.css
```css
/* Tailwind v4 source directives for smaller CSS bundle */
@source "./src/**/*.{html,js,svelte,ts}";
@source "./src/routes/**/*.{svelte,js,ts}";
@source "./src/lib/components/**/*.{svelte,js,ts}";
```

### 8. Resource Hints (Preconnect & Preload)

```html
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="dns-prefetch" href="https://images.unsplash.com" />
<link rel="preload" href={heroBanner.image} as="image" fetchpriority="high" />
```

### 9. Image Optimization

All images now have:
- **Width & Height attributes** (prevents layout shift)
- **Loading attributes** (`eager` for hero, `lazy` for others)
- **Decoding attribute** (`async`)
- **Descriptive alt text** (or empty alt for decorative images with `role="presentation"`)
- **Unsplash images use `auto=format&fit=crop`** for optimization

### 10. Accessibility (a11y) Improvements

- **Skip to content link** for keyboard navigation
- **ARIA labels** for all navigation elements
- **ARIA labelledby** connecting headings to sections
- **Role attributes**: `role="banner"`, `role="presentation"`
- **Semantic HTML**: `<main>`, `<header>`, `<nav>`, `<section>`, `<footer>`

### 11. URL Structure

All internal links use:
- **Absolute paths**: `/blog`, `#harga` (not relative)
- **Named anchors**: `#order-section`, `#harga`, `#faq`
- **External links**: `rel="nofollow noopener noreferrer"` for security

## File Changes Summary

| File | Changes |
|------|---------|
| `+page.svelte` | Complete SEO overhaul: semantic HTML, Schema.org, meta tags, SSR fallback hero, heading hierarchy |
| `+layout.svelte` | SEO comments, proper svelte:head structure |
| `vite.config.js` | CSS extraction config, postcss transformer |
| `svelte.config.js` | `inlineStyleThreshold: 0` to prevent CSS inlining |
| `app.css` | `@source` directives for Tailwind v4 optimization |
| `postcss.config.js` | New file for Tailwind PostCSS processing |
| `PublicHeader.svelte` | Semantic `<header>` and `<nav>` with ARIA labels |

## Hasil yang Diharapkan

### Google Search
- ✅ H1 dan konten utama terbaca oleh Googlebot tanpa JavaScript
- ✅ Schema.org structured data untuk rich snippets
- ✅ Canonical dan hreflang yang tepat
- ✅ Meta description unik dengan keyword

### Social Media
- ✅ Open Graph cards untuk Facebook/WhatsApp
- ✅ Twitter Card dengan large image
- ✅ OG image dengan dimensi 1200x630

### Performance
- ✅ CSS di-extract ke file terpisah (bukan inline)
- ✅ Critical resource preloading
- ✅ Lazy loading untuk gambar non-kritis
- ✅ Image optimization dengan width/height

### Accessibility
- ✅ Semantic HTML5 elements
- ✅ Proper heading hierarchy
- ✅ ARIA labels dan roles
- ✅ Skip to content link

## Catatan Penting

1. **CSS Warnings**: Warning "Unknown at rule @source" dll di IDE adalah normal - ini Tailwind v4 directives yang IDE belum kenali tapi berfungsi dengan baik di build.

2. **Rebuild Required**: Jalankan `npm run build` untuk melihat hasil akhir.

3. **Testing**: Gunakan Google Rich Results Test untuk memvalidasi Schema.org:
   https://search.google.com/test/rich-results

4. **Lighthouse**: Jalankan Lighthouse audit di Chrome DevTools untuk memeriksa SEO score.
