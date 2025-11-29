# 🌟 The Good Beacon - USA Good News Outlet

A beautiful, bright, and colorful news website that automatically updates with positive news from across the United States!

## ✨ Features

- 🎨 **Bright & Happy Design** - Colorful theme with orange (#ffa500) as the primary color
- 🔄 **Auto-Updates** - News refreshes automatically every 10 minutes
- 📱 **Fully Responsive** - Works perfectly on desktop, tablet, and mobile
- 🌈 **Category Sections** - US News, Technology, Health, Environment
- 🎯 **Positive News Filter** - Only shows uplifting and positive stories
- ⚡ **Fast & Modern** - Built with vanilla JavaScript, no frameworks needed

## 🚀 Quick Start

1. **Open the website**

   - Simply open `index.html` in your web browser
   - The site works immediately with sample good news

2. **Enable Live News Updates** (Optional)
   - Get a free API key from [NewsAPI.org](https://newsapi.org)
   - Open `app.js`
   - Replace `YOUR_NEWS_API_KEY` with your actual API key
   - Refresh the page to see live news!

## 📁 Project Structure

```
TheGoodBeaconNewsOutlet/
├── index.html       # Main HTML structure
├── styles.css       # All styling (bright & colorful theme)
├── app.js          # JavaScript for news fetching and rendering
└── README.md       # This file
```

## 🎨 Design Theme

- **Primary Color**: Orange (#ffa500) - Warm and inviting
- **Supporting Colors**:
  - Bright Yellow (#ffd700)
  - Sky Blue (#4a90e2)
  - Coral (#ff6b6b)
  - Mint (#51cf66)
  - Purple (#9c27b0)
- **Typography**: Poppins font family (friendly and modern)
- **Style**: Cheerful, not serious business - think celebration, not corporate

## 🔧 Customization

### Change Update Frequency

Edit `app.js` line 9:

```javascript
this.updateInterval = 10 * 60 * 1000; // Change to desired milliseconds
```

### Modify Colors

Edit `styles.css` in the `:root` section:

```css
:root {
  --primary-orange: #ffa500; /* Change your primary color here */
  /* ... other colors ... */
}
```

### Add More Categories

Add new sections in `index.html` and update the `fetchNews()` function in `app.js`

## 🌐 Deployment

### GitHub Pages (Free!)

1. Create a GitHub repository
2. Push all files to the repository
3. Go to Settings → Pages
4. Select main branch as source
5. Your site will be live at `https://yourusername.github.io/TheGoodBeaconNewsOutlet`

### Netlify (Free!)

1. Drag and drop the project folder to [Netlify](https://netlify.com)
2. Your site goes live instantly!

### Vercel (Free!)

1. Install Vercel CLI: `npm install -g vercel`
2. Run `vercel` in the project folder
3. Follow the prompts

## 📝 Notes

- The site uses NewsAPI.org for live news (free tier: 100 requests/day)
- Without an API key, the site shows curated sample good news
- All news is filtered for positive keywords to ensure uplifting content
- Images fallback to Unsplash if sources don't provide images

## 🎯 Future Enhancements

- [ ] Add search functionality
- [ ] User preference for news categories
- [ ] Dark mode toggle (while keeping it bright and happy!)
- [ ] Social sharing buttons
- [ ] Newsletter signup
- [ ] RSS feed

## 📄 License

Free to use and modify! Spread positivity! 🌟

## 🙏 Credits

- News powered by [NewsAPI.org](https://newsapi.org)
- Placeholder images from [Unsplash](https://unsplash.com)
- Icons: Unicode emoji (universal support!)

---

Made with ❤️ and ☀️ to spread good news across America!
