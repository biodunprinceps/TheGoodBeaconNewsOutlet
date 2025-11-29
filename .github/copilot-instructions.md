# The Good Beacon - AI Agent Instructions

## Project Overview

A bright, colorful USA good news outlet that auto-updates every 10 minutes. Think CNN meets celebration - not serious business, but happy and uplifting!

## Architecture & Components

### Core Structure

- **Single Page Application** - Pure HTML/CSS/JS, no framework dependencies
- **index.html** - Semantic structure with hero section, news grids, and breaking ticker
- **styles.css** - Bright color system with #ffa500 (orange) as primary, gradient backgrounds
- **app.js** - `GoodBeaconNews` class handles all news fetching, filtering, and rendering

### Key Design Philosophy

- **Color-first approach**: Use vibrant gradients (`--gradient-sunset`, `--gradient-sky`) from CSS variables
- **Mobile-responsive**: Grid layouts collapse gracefully (1400px → 1024px → 768px → 480px breakpoints)
- **Emoji-enhanced**: Use emoji in headings/categories for friendly, non-corporate feel

## Critical Workflows

### News Update Cycle

```javascript
// app.js - Auto-refresh every 10 minutes
loadNews() → fetchNews(categories) → filterPositiveNews() → render*() functions
```

- Always filter news with positive keywords (line 48-56 in app.js)
- Fallback to mock data if API unavailable (line 36-40)
- Update timestamp on each refresh (line 271-279)

### Adding New Categories

1. Add section in `index.html` with id matching pattern: `{category}-grid`
2. Update `fetchNews()` Promise.all in `loadNews()` (line 23)
3. Add corresponding render call (line 42)
4. Define category color in `:root` and `.category-{name}` class

### Styling Conventions

- **Card hover effects**: Always include `transform: translateY(-5px)` + box-shadow increase
- **Border radius**: 15-20px for main elements, 25px for pills/badges
- **Spacing**: 20-30px for cards, 50px for sections
- **Colors from variables**: Never hardcode - use `var(--primary-orange)` etc.

## External Dependencies

### NewsAPI Integration

- Free tier: 100 requests/day from newsapi.org
- API key in `app.js` line 7: Replace `YOUR_NEWS_API_KEY`
- Endpoint pattern: `${baseUrl}/top-headlines?country=us&category={category}`
- Always handle errors gracefully → show mock data

### Image Handling

- Primary: Article's `urlToImage` field
- Fallback: Unsplash Source API with random relevant images
- Use `onerror="this.src='...'"` inline handlers

### External Resources

- Font: Google Fonts Poppins (weights: 400, 600, 700, 800)
- No build process, no npm, no bundlers - everything loads directly in browser

## Project-Specific Patterns

### Rendering Pattern

All render functions follow: `getData() → .map() HTML template → container.innerHTML = result`

- Example: `renderNewsGrid()` (line 123-149)
- Always sanitize URLs with inline onclick for security
- Include error states in templates

### Date Formatting

Custom relative time (e.g., "2h ago") in `formatDate()` - don't use libraries

- < 60 mins: "Xm ago"
- < 24 hours: "Xh ago"
- < 7 days: "Xd ago"
- Else: "Mon DD" format

### Responsive Grid System

```css
grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
```

Auto-adapts without media queries for card layouts. Only use media queries for nav/hero layout changes.

## Development Notes

- **No build step**: Just open index.html - instant preview
- **Testing**: Simulate API failure by keeping default key to see mock data flow
- **Deployment**: Static hosting only (GitHub Pages, Netlify, Vercel)
- **Browser support**: Modern browsers with CSS Grid and Fetch API

## Common Tasks

**Change primary color**: Update `--primary-orange` in `:root` (styles.css line 14)
**Adjust update frequency**: Modify `updateInterval` (app.js line 9)
**Add news source**: Extend `fetchNews()` Promise.all with new category
**Customize positive filters**: Edit `positiveKeywords` array (app.js line 47-52)

## Important Constraints

- Never use external CSS frameworks (Bootstrap, Tailwind) - keep it lightweight
- Maintain cheerful, non-corporate tone in all copy
- All animations should feel playful (ticker scroll, card hover)
- Gradients over solid colors when possible
- Keep accessibility: sufficient contrast despite bright colors (WCAG AA minimum)
