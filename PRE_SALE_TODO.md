# Pre-Sale TODO List

## ✅ Completed (Just Now)

- [x] Removed debug route from `routes/web.php`
- [x] Updated `.env.example` with production-safe defaults
  - APP_ENV=production
  - APP_DEBUG=false
  - LOG_LEVEL=error
  - APP_NAME="The Good Beacon News Outlet"
- [x] Added MIT LICENSE file
- [x] Created `FIRST_DEPLOY_CHECKLIST.md` (comprehensive deployment guide)
- [x] Created `SECURITY_WARNINGS.md` (security audit checklist)
- [x] Updated `README.md` with security warnings
- [x] All changes committed to git

## 🎯 Still TODO Before Selling (2-3 hours)

### 1. Screenshots for Sales Page (60-90 minutes) 📸

**Required screenshots** (mentioned in SALES_DESCRIPTION.md):

- [ ] Login page (`/admin`)
- [ ] Dashboard (admin home)
- [ ] Article editor (create/edit article form)
- [ ] Media library (upload interface)
- [ ] Category management
- [ ] Dark mode view
- [ ] Mobile responsive view

**How to take them:**

1. Start the Docker environment:
   ```bash
   docker-compose up -d
   ```

2. Run migrations and seed sample data:
   ```bash
   docker-compose exec app php artisan migrate:fresh --seed
   ```

3. Login at http://localhost:8000/admin
   - Email: admin@goodbeacon.com
   - Password: admin123

4. Take screenshots using macOS Screenshot tool (Cmd+Shift+4)

5. Save to a new `screenshots/` folder

6. Update `SALES_DESCRIPTION.md` with screenshot links

**Screenshot specs:**
- Resolution: 1920x1080 or higher
- Format: PNG or JPG (optimize for web)
- Show actual content (not empty screens)
- Include both light and dark mode

---

### 2. Test Fresh Installation (30 minutes) ✅

Verify the setup process works:

- [ ] Clone repo to new folder (simulate buyer experience)
- [ ] Follow GET_STARTED.md step-by-step
- [ ] Docker installation works without errors
- [ ] All migrations run successfully
- [ ] Seeder creates sample data
- [ ] Admin login works
- [ ] Can create/edit articles
- [ ] Can upload images
- [ ] Frontend displays correctly

**Commands to test:**

```bash
# In a temporary directory
cd ~/Desktop
git clone https://github.com/biodunprinceps/TheGoodBeaconNewsOutlet.git test-install
cd test-install
docker-compose up -d
docker-compose exec app php artisan migrate:fresh --seed
# Open http://localhost:8000
# Open http://localhost:8000/admin
```

---

### 3. Optional: Create Demo Video (60 minutes) 🎥

**For higher-tier packages ($99, $199), consider:**

- [ ] 5-10 minute walkthrough video
- [ ] Show installation process
- [ ] Demonstrate key features
- [ ] Show customization examples
- [ ] Upload to YouTube or Vimeo

**Tools:**
- macOS: QuickTime Player (built-in screen recording)
- Loom (free tier: 5 min videos)
- OBS Studio (free, more advanced)

**Video outline:**
1. Introduction (30 sec)
2. Installation via Docker (2 min)
3. Admin panel tour (2 min)
4. Creating an article (2 min)
5. Frontend demo (1 min)
6. Customization tips (1 min)
7. Next steps (30 sec)

---

### 4. Package Preparation (30 minutes) 📦

**Create downloadable ZIP file:**

- [ ] Create `INSTALL_FIRST.txt` pointing to GET_STARTED.md
- [ ] Verify all documentation is included
- [ ] Ensure `.env.example` is present (NOT `.env`)
- [ ] Create ZIP without `vendor/`, `node_modules/`, `.git/`
- [ ] Test ZIP extraction and installation

**Command to create ZIP:**

```bash
# In project root
zip -r TheGoodBeaconCMS-v1.0.zip . \
  -x "*.git/*" \
  -x "*node_modules/*" \
  -x "*vendor/*" \
  -x "*.env" \
  -x "*storage/logs/*" \
  -x "*public/storage/*"
```

**Create `INSTALL_FIRST.txt`:**

```text
THE GOOD BEACON NEWS OUTLET
===========================

Thank you for your purchase!

QUICK START:
1. Extract this ZIP file to your desired location
2. Read GET_STARTED.md for installation instructions
3. Choose Docker (easiest) or Native installation

IMPORTANT SECURITY:
Before deploying to production, read:
- FIRST_DEPLOY_CHECKLIST.md
- SECURITY_WARNINGS.md

Default credentials (MUST CHANGE):
- Admin: admin@goodbeacon.com / admin123
- Database: postgres / secret

DOCUMENTATION:
- GET_STARTED.md - Installation guide
- PROJECT_SUMMARY.md - Feature overview
- DEPLOYMENT_NOTES.md - Production deployment
- CREDENTIALS.md - Default credentials

Need help? Check the documentation first!

Enjoy building with The Good Beacon! 🚀
```

---

### 5. Sales Page Finalization (30 minutes) 💰

**Review SALES_DESCRIPTION.md:**

- [ ] Add screenshot embeds
- [ ] Verify pricing is correct ($49/$99/$199)
- [ ] Add demo video link (if created)
- [ ] Review feature list for accuracy
- [ ] Add testimonials (if any)
- [ ] Proofread for typos

**Prepare for Systeme.io:**

- [ ] Create product listing
- [ ] Upload screenshots
- [ ] Set pricing tiers
- [ ] Configure delivery (ZIP download)
- [ ] Set up payment processing
- [ ] Write clear refund policy

---

## 📊 Estimated Time Breakdown

| Task | Time | Priority |
|------|------|----------|
| Screenshots | 60-90 min | **HIGH** |
| Fresh install test | 30 min | **HIGH** |
| Package ZIP | 30 min | **HIGH** |
| Sales page update | 30 min | **MEDIUM** |
| Demo video | 60 min | **OPTIONAL** |
| **TOTAL** | **2.5-4 hours** | |

---

## 🎯 Minimum Viable Product (MVP) to Sell

**Can sell with just:**
1. ✅ Screenshots (HIGH priority)
2. ✅ Fresh install verification (HIGH priority)
3. ✅ Packaged ZIP file (HIGH priority)

**Nice to have:**
- Demo video (increases perceived value for $99+ tiers)
- Video tutorials for $199 tier
- Email support tier

---

## ✅ What's Already Done

You have:
- ✅ Complete, working Laravel + Filament CMS
- ✅ 20+ documentation files
- ✅ Docker setup
- ✅ Railway deployment guide
- ✅ Security warnings and checklists
- ✅ Pre-written sales copy
- ✅ Pricing structure
- ✅ MIT license
- ✅ Professional codebase
- ✅ All major features implemented

---

## 🚀 Ready to Sell After

Complete the 3 HIGH priority tasks above (approximately 2-3 hours total).

**Your product is 95% complete!** Just needs the visual elements (screenshots) and final packaging.

---

**Current Status:** Production-ready code, needs sales materials
**Next Step:** Take screenshots → Package ZIP → List on Systeme.io
**Estimated Time to Launch:** 2-3 hours of focused work

---

Created: 2 December 2025
