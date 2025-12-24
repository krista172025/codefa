# Quick Start Guide - My Super Tour Updates

## 🚀 Getting Started in 5 Minutes

### Step 1: Activate Updates (Required!)
After updating the plugin, you MUST flush permalinks:

1. Go to WordPress Admin
2. Navigate to **Settings → Permalinks**
3. Click **"Save Changes"** button
4. Don't change anything, just save!

This activates the new `/guide/{id}` URL routing.

---

## 📄 Creating a Guides Page

### Option 1: Simple Guides List
Create a new page and add this shortcode:

```
[mst_guides_list]
```

That's it! All guides will be displayed in a beautiful grid.

### Option 2: Customized Guides List
```
[mst_guides_list per_page="9" columns="3" orderby="display_name" order="ASC"]
```

Parameters:
- `per_page` - How many guides (default: 12)
- `columns` - Grid columns (default: 3)
- `orderby` - Sort by: display_name, user_registered, etc.
- `order` - ASC or DESC

---

## 👤 Setting Up Guide Profiles

### 1. Mark User as Guide
1. Go to **Users → All Users**
2. Click **Edit** on a user
3. Scroll down to **"MySuperTour - Личный Кабинет"** section
4. Set **"Статус (рамка аватара)"** to **"🟢 Гид"**
5. Click **"Update User"**

### 2. Fill Guide Information
In the same user edit page, scroll to **"📊 Статистика и профиль гида"**:

**Required Fields:**
- **Рейтинг гида**: e.g., 4.9 (0.0 - 5.0)
- **Количество отзывов**: e.g., 127
- **О гиде**: Biography text
- **Языки**: Russian, English, French
- **Специализация**: History, Architecture, Museums
- **Город**: Saint Petersburg

**Optional but Recommended:**
- **Опыт (лет)**: e.g., 8
- **Туров проведено**: e.g., 234
- **Достижения**: One per line

### 3. Upload Guide Photo
1. In user profile, find **"Profile Picture"** section
2. Upload an avatar image
3. This will be used in guide cards

---

## 🔍 Adding Search to Header

### Quick Add
Add this to your header template (usually `header.php`):

```php
<?php echo do_shortcode('[mst_search_header]'); ?>
```

### With Elementor
1. Edit header with Elementor
2. Add widget: **"Search Header (MST)"**
3. Configure settings in widget panel
4. Save and publish

### Shortcode Options
```
[mst_search_header 
  placeholder="Where do you want to go?" 
  button_text="Search" 
  width="500px" 
  align="center"
  liquid_glass="yes"
]
```

---

## 🎯 Assigning Guides to Tours

### In Product Edit Page
1. Go to **Products → All Products**
2. Click **Edit** on a tour product
3. On the right sidebar, find **"👨‍🎓 Гид экскурсии"**
4. Select a guide from dropdown
5. Click **"Update"**

The guide card will now appear automatically on:
- Shop pages
- Product archives
- Shop Grid widgets
- Tour carousels

---

## 🛠️ Using Filters

### Add Filters to Shop Page
1. Create or edit your shop page
2. Add this shortcode:
```
[mst_filters]
```

3. Below it, add Shop Grid widget (Elementor) or shortcode

### Filter Configuration
Filters are configured in:
**WordPress Admin → MySuperTour Hub → Product Icons → Filters**

Available filters:
- **Format** (Group/Private)
- **Transport** (Car/Bus/Walk)
- **Price Range** (slider)
- **Attributes** (Custom parameters per category)

---

## 🎨 Using New CSS Classes

### Glass Effect Cards
```html
<div class="glass-liquid hover-lift">
  <h3>Your Content</h3>
  <p>Beautiful glass morphism effect</p>
</div>
```

### Badges
```html
<span class="badge-trust">Verified</span>
<span class="badge-warm">Featured</span>
<span class="badge-success">Partner</span>
<span class="badge-purple">Premium</span>
```

### Animations
```html
<div class="animate-fade-in-up">
  This content fades in from bottom
</div>
```

---

## 🔗 Guide Profile URLs

### Automatic URLs
Once permalinks are flushed, guides automatically get clean URLs:

```
https://yoursite.com/guide/1
https://yoursite.com/guide/2
https://yoursite.com/guide/123
```

Where the number is the WordPress user ID of the guide.

### Linking to Guide Profiles
In your content:
```html
<a href="/guide/1">View Guide Profile</a>
```

In PHP:
```php
<?php 
$guide_url = home_url('/guide/' . $guide_user_id);
echo '<a href="' . esc_url($guide_url) . '">View Guide</a>';
?>
```

---

## ✅ Verification Checklist

After setup, verify these work:

- [ ] **Guides list page displays** with cards
- [ ] **Guide profile opens** at `/guide/1`
- [ ] **Wishlist heart animates** on click
- [ ] **Filters work** on shop page
- [ ] **Search header shows** suggestions
- [ ] **Guide cards appear** on products with assigned guides
- [ ] **Badges display** correctly (Verified, My Super Tour, etc.)

---

## 🎯 Common Use Cases

### Use Case 1: City Tour Company
Setup guides for each city:
1. Create guides with city in profile
2. Assign guides to city-specific tours
3. Create guides list page: "Our Guides"
4. Users can browse guides by city and specialization

### Use Case 2: Museum Tours
Setup expert guides:
1. Mark guides with academic titles
2. "Doctor" badge appears automatically
3. Assign to museum-related products
4. Users see expert credentials on product pages

### Use Case 3: Multi-language Tours
Showcase language capabilities:
1. Set languages in guide profile
2. Languages display as tags on cards
3. Users can find guides speaking their language
4. Filter/search by language (future feature)

---

## 🆘 Quick Fixes

### Problem: Guides list is empty
**Fix**: Ensure users have status set to "guide" in their profile

### Problem: Guide profile shows 404
**Fix**: Flush permalinks (Settings → Permalinks → Save)

### Problem: Wishlist doesn't animate
**Fix**: Clear browser cache, check if JavaScript loads

### Problem: Filters don't work
**Fix**: Ensure using Shop Grid widget, not standard archive

### Problem: Guide photo not showing
**Fix**: Upload avatar in user profile settings

---

## 📱 Mobile Responsiveness

All features are fully responsive:

- **Guides list**: 3 cols → 2 cols → 1 col
- **Guide profile**: Stacks vertically on mobile
- **Filters**: Touch-friendly inputs
- **Search**: Full width on small screens
- **Wishlist buttons**: Touch-optimized size

---

## 🎨 Customization Tips

### Change Badge Colors
Edit `widgets.css` and modify badge classes:
```css
.badge-trust {
  background: #YourColor;
  color: #YourTextColor;
}
```

### Adjust Glass Effect
Modify blur amount:
```css
.glass-liquid {
  backdrop-filter: blur(30px); /* Increase for more blur */
}
```

### Change Animation Speed
```css
.hover-lift {
  transition: all 0.5s ease; /* Slower animation */
}
```

---

## 🔄 Regular Maintenance

### Monthly Tasks
- [ ] Update guide ratings and review counts
- [ ] Add new guides as team grows
- [ ] Update guide achievements
- [ ] Check guide profile links work
- [ ] Review filter settings

### When Adding New Guides
1. Create WordPress user
2. Set role (e.g., Shop Manager)
3. Mark as guide in profile
4. Fill all guide information
5. Upload professional photo
6. Assign to relevant tours
7. Test guide profile URL

---

## 💡 Pro Tips

1. **High-quality photos**: Use professional guide photos (min 400x400px)
2. **Complete profiles**: Fill all fields for best presentation
3. **Regular updates**: Keep ratings and stats current
4. **SEO-friendly**: Use descriptive text in "About" section
5. **Trust signals**: Add achievements and certifications
6. **Language accuracy**: List all languages guide can conduct tours in
7. **Specialization focus**: Be specific about guide expertise

---

## 🎓 Best Practices

### For Guide Profiles
- Write detailed, engaging biographies
- Highlight unique qualifications
- Update tour counts regularly
- Respond to reviews (when system available)
- Keep availability current

### For Shop Grid
- Use high-quality product images
- Assign appropriate guides to products
- Keep pricing current
- Use filters to help users find tours
- Test on different devices

### For Overall Site
- Use glass-liquid effects consistently
- Maintain color scheme harmony
- Ensure fast page load times
- Test all links periodically
- Keep content fresh and updated

---

**Need more help?** Check the full documentation in `MY_SUPER_TOUR_UPDATES.md`
