// ===================================
// THE GOOD BEACON - NEWS APP
// Auto-updating Good News Aggregator
// ===================================

class GoodBeaconNews {
  constructor() {
    this.apiKey = "YOUR_NEWS_API_KEY"; // Get free key from newsapi.org
    this.baseUrl = "https://newsapi.org/v2";
    this.updateInterval = 10 * 60 * 1000; // 10 minutes
    this.currentNews = [];

    this.init();
  }

  init() {
    this.loadNews();
    this.startAutoUpdate();
    this.updateLastUpdateTime();
  }

  // Fetch news from multiple sources
  async loadNews() {
    try {
      // Show loading state
      this.showLoading();

      // Fetch different categories
      const [general, tech, health, us] = await Promise.all([
        this.fetchNews("general"),
        this.fetchNews("technology"),
        this.fetchNews("health"),
        this.fetchNews("us"),
      ]);

      // Filter for positive news
      this.currentNews = {
        general: this.filterPositiveNews(general, true),
        tech: this.filterPositiveNews(tech, true),
        health: this.filterPositiveNews(health, true),
        us: this.filterPositiveNews(us, true),
      };

      // Render all sections
      this.renderHeroSection();
      this.renderTrendingStories();
      this.renderNewsGrid(this.currentNews.general, "news-grid");
      this.renderNewsGrid(this.currentNews.us, "us-grid");
      this.renderNewsGrid(this.currentNews.tech, "tech-grid");
      this.renderNewsGrid(this.currentNews.health, "health-grid");
      this.renderBreakingTicker();

      this.updateLastUpdateTime();
    } catch (error) {
      console.error("Error loading news:", error);
      this.showError();
    }
  }

  // Fetch news from API
  async fetchNews(category) {
    // If no API key, use mock data
    if (!this.apiKey || this.apiKey === "YOUR_NEWS_API_KEY") {
      console.log("Using mock data for category:", category);
      return this.getMockNews(category);
    }

    try {
      const url = `${this.baseUrl}/top-headlines?country=us&category=${category}&pageSize=20&apiKey=${this.apiKey}`;
      const response = await fetch(url);
      const data = await response.json();
      return data.articles || [];
    } catch (error) {
      console.error("API fetch failed, using mock data:", error);
      return this.getMockNews(category);
    }
  }

  // Filter for positive news keywords
  filterPositiveNews(articles, isMockData = false) {
    // If using mock data or no API key, return all articles (they're pre-filtered as positive)
    if (isMockData || !this.apiKey || this.apiKey === "YOUR_NEWS_API_KEY") {
      return articles.slice(0, 12);
    }

    const positiveKeywords = [
      "breakthrough",
      "success",
      "innovation",
      "achievement",
      "progress",
      "improve",
      "benefit",
      "win",
      "celebrate",
      "hero",
      "rescue",
      "save",
      "launch",
      "discover",
      "award",
      "champion",
      "record",
      "milestone",
      "recovery",
      "growth",
      "breakthrough",
      "advance",
      "solution",
      "help",
      "cure",
      "clean",
      "green",
      "sustainable",
      "hope",
      "positive",
      "good",
    ];

    return articles
      .filter((article) => {
        const text = `${article.title} ${article.description}`.toLowerCase();
        return positiveKeywords.some((keyword) => text.includes(keyword));
      })
      .slice(0, 12);
  }

  // Render hero section
  renderHeroSection() {
    const heroStory = document.getElementById("hero-story");
    const topStory = this.currentNews.general[0] || this.currentNews.us[0];

    if (!topStory) return;

    heroStory.innerHTML = `
            <img src="${
              topStory.urlToImage || "https://picsum.photos/seed/hero/1200/600"
            }" 
                 alt="${topStory.title}" 
                 class="hero-image"
                 onerror="this.src='https://picsum.photos/seed/hero-fallback/1200/600'">
            <div class="hero-content">
                <span class="hero-category">🌟 Top Story</span>
                <h2>${topStory.title}</h2>
                <p>${topStory.description || ""}</p>
                <div class="hero-meta">
                    <span>${topStory.source.name}</span> • 
                    <span>${this.formatDate(topStory.publishedAt)}</span>
                </div>
            </div>
        `;

    heroStory.onclick = () => window.open(topStory.url, "_blank");
  }

  // Render trending stories
  renderTrendingStories() {
    const container = document.getElementById("trending-stories");
    const stories = [
      ...this.currentNews.general,
      ...this.currentNews.tech,
    ].slice(1, 6);

    container.innerHTML = stories
      .map(
        (story, index) => `
            <div class="trending-item" onclick="window.open('${
              story.url
            }', '_blank')">
                <span class="trending-number">${index + 1}</span>
                <h4>${story.title}</h4>
                <div class="meta">${story.source.name} • ${this.formatDate(
          story.publishedAt
        )}</div>
            </div>
        `
      )
      .join("");
  }

  // Render news grid
  renderNewsGrid(articles, containerId) {
    const container = document.getElementById(containerId);

    if (!articles || articles.length === 0) {
      container.innerHTML =
        '<p style="text-align: center; padding: 40px; color: #999;">No stories available at the moment. Check back soon!</p>';
      return;
    }

    container.innerHTML = articles
      .map(
        (article) => `
            <div class="news-card" onclick="window.open('${
              article.url
            }', '_blank')">
                <img src="${article.urlToImage || this.getPlaceholderImage()}" 
                     alt="${article.title}" 
                     class="news-card-image"
                     onerror="this.src='${this.getPlaceholderImage()}'">
                <div class="news-card-content">
                    <span class="news-card-category ${this.getCategoryClass(
                      article
                    )}">${this.getCategory(article)}</span>
                    <h3>${article.title}</h3>
                    <p>${this.truncate(article.description || "", 120)}</p>
                    <div class="news-card-meta">
                        <span class="news-source">${article.source.name}</span>
                        <span>${this.formatDate(article.publishedAt)}</span>
                    </div>
                </div>
            </div>
        `
      )
      .join("");
  }

  // Render breaking news ticker
  renderBreakingTicker() {
    const ticker = document.getElementById("ticker");
    const headlines = [...this.currentNews.general, ...this.currentNews.us]
      .slice(0, 5)
      .map((article) => article.title)
      .join(" • ");

    ticker.textContent = headlines + " • " + headlines; // Duplicate for seamless scroll
  }

  // Get category from article
  getCategory(article) {
    if (
      article.source.name.includes("Tech") ||
      article.title.includes("AI") ||
      article.title.includes("tech")
    ) {
      return "💻 Tech";
    } else if (
      article.title.includes("health") ||
      article.title.includes("medical")
    ) {
      return "❤️ Health";
    } else if (
      article.title.includes("environment") ||
      article.title.includes("climate")
    ) {
      return "🌱 Environment";
    } else if (
      article.title.includes("business") ||
      article.title.includes("economy")
    ) {
      return "💼 Business";
    } else if (article.title.includes("science")) {
      return "🔬 Science";
    }
    return "🇺🇸 US News";
  }

  getCategoryClass(article) {
    const category = this.getCategory(article);
    if (category.includes("Tech")) return "category-tech";
    if (category.includes("Health")) return "category-health";
    if (category.includes("Environment")) return "category-environment";
    if (category.includes("Business")) return "category-business";
    if (category.includes("Science")) return "category-science";
    return "category-us";
  }

  // Helper functions
  formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMins / 60);
    const diffDays = Math.floor(diffHours / 24);

    if (diffMins < 60) return `${diffMins}m ago`;
    if (diffHours < 24) return `${diffHours}h ago`;
    if (diffDays < 7) return `${diffDays}d ago`;
    return date.toLocaleDateString("en-US", { month: "short", day: "numeric" });
  }

  truncate(str, length) {
    if (!str) return "";
    return str.length > length ? str.substring(0, length) + "..." : str;
  }

  getPlaceholderImage() {
    // Using Picsum for reliable placeholder images
    const seed = Math.floor(Math.random() * 1000);
    return `https://picsum.photos/seed/${seed}/800/600`;
  }

  updateLastUpdateTime() {
    const element = document.getElementById("last-update");
    if (element) {
      const now = new Date();
      element.textContent = now.toLocaleTimeString("en-US", {
        hour: "numeric",
        minute: "2-digit",
      });
    }
  }

  showLoading() {
    const grids = ["news-grid", "us-grid", "tech-grid", "health-grid"];
    grids.forEach((gridId) => {
      const grid = document.getElementById(gridId);
      if (grid) {
        grid.innerHTML = `
                    <div class="loading-card">
                        <div class="loading-spinner"></div>
                        <p>Loading amazing good news...</p>
                    </div>
                `;
      }
    });
  }

  showError() {
    const grids = ["news-grid", "us-grid", "tech-grid", "health-grid"];
    grids.forEach((gridId) => {
      const grid = document.getElementById(gridId);
      if (grid) {
        grid.innerHTML = `
                    <div class="loading-card">
                        <h3>😊 Using Sample Good News</h3>
                        <p>Add your NewsAPI.org key in app.js to see live updates!</p>
                        <p><a href="https://newsapi.org" target="_blank" style="color: #ffa500;">Get a free API key →</a></p>
                    </div>
                `;
      }
    });
    // Load mock data instead
    this.currentNews = {
      general: this.getMockNews("general"),
      tech: this.getMockNews("technology"),
      health: this.getMockNews("health"),
      us: this.getMockNews("us"),
    };
    setTimeout(() => {
      this.renderHeroSection();
      this.renderTrendingStories();
      this.renderNewsGrid(this.currentNews.general, "news-grid");
      this.renderNewsGrid(this.currentNews.us, "us-grid");
      this.renderNewsGrid(this.currentNews.tech, "tech-grid");
      this.renderNewsGrid(this.currentNews.health, "health-grid");
      this.renderBreakingTicker();
    }, 1000);
  }

  startAutoUpdate() {
    setInterval(() => {
      this.loadNews();
    }, this.updateInterval);
  }

  // Mock data for demo purposes
  getMockNews(category) {
    const mockArticles = [
      {
        title:
          "Scientists Achieve Major Breakthrough in Clean Energy Technology",
        description:
          "Researchers have developed a revolutionary solar panel that's 50% more efficient than current models, bringing us closer to a sustainable future.",
        url: "#",
        urlToImage: "https://picsum.photos/seed/solar1/800/600",
        publishedAt: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
        source: { name: "Tech Innovation Daily" },
      },
      {
        title:
          "Community Initiative Helps 10,000 Families Access Free Education",
        description:
          "A nationwide program launched by volunteers has successfully provided educational resources and tutoring to underserved communities across America.",
        url: "#",
        urlToImage: "https://picsum.photos/seed/education1/800/600",
        publishedAt: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString(),
        source: { name: "Good News Network" },
      },
      {
        title:
          "New Medical Treatment Shows 95% Success Rate in Clinical Trials",
        description:
          "A groundbreaking therapy for chronic illness has shown remarkable results, offering hope to millions of patients nationwide.",
        url: "#",
        urlToImage: "https://picsum.photos/seed/medical1/800/600",
        publishedAt: new Date(Date.now() - 8 * 60 * 60 * 1000).toISOString(),
        source: { name: "Health & Wellness Today" },
      },
      {
        title: "US Cities Launch Ambitious Green Space Initiative",
        description:
          "Major cities across the country are creating new parks and green spaces, improving air quality and quality of life for residents.",
        url: "#",
        urlToImage: "https://picsum.photos/seed/nature1/800/600",
        publishedAt: new Date(Date.now() - 12 * 60 * 60 * 1000).toISOString(),
        source: { name: "Environmental Progress" },
      },
      {
        title: "Tech Companies Unite to Bridge Digital Divide in Rural America",
        description:
          "Major technology firms announce partnership to bring high-speed internet to underserved rural communities.",
        url: "#",
        urlToImage: "https://picsum.photos/seed/tech1/800/600",
        publishedAt: new Date(
          Date.now() - 1 * 24 * 60 * 60 * 1000
        ).toISOString(),
        source: { name: "Digital America" },
      },
      {
        title: "Student Inventors Create Device to Help Clean Ocean Plastic",
        description:
          "High school students develop innovative solution that removes microplastics from water, winning national science competition.",
        url: "#",
        urlToImage: "https://picsum.photos/seed/ocean1/800/600",
        publishedAt: new Date(
          Date.now() - 2 * 24 * 60 * 60 * 1000
        ).toISOString(),
        source: { name: "Science for Tomorrow" },
      },
    ];

    return mockArticles;
  }
}

// Initialize the app when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  new GoodBeaconNews();
});
