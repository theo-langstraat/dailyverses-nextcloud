
# **Daily Verses for Nextcloud**
Displays a daily or random Bible verse from dailyverses.net on your Nextcloud dashboard, with support for 26 languages and multiple Bible translations.

---

## **✨ Features**
- **Daily or random Bible verse** from dailyverses.net  
- **26 supported languages**  
- **Multiple Bible translations**  
- **Dashboard widget** for quick inspiration  
- **Admin settings** for global defaults  
- **Personal settings** for user‑specific preferences  
- Optional **links to full verse context**  
- Lightweight, fast, and privacy‑friendly (no tracking)

---

## Screenshots


### Frontend
<img src="doc/img/frontend-nl.png" width="1200">

### Frontend Arabic
<img src="doc/img/frontend-arabic.png" width="1200">

### Backend – Admin Settings
<img src="doc/img/backend-admin.png" width="1200">

### Backend – Personal Settings

#### Collapsed (initial view)
<img src="doc/img/backend-personal-collapsed.png" width="1200">

#### Expanded (by user)
<img src="doc/img/backend-personal-expanded.png" width="1200">

---

## **📦 Installation**
1. Download or clone the app into your Nextcloud `apps/` directory:  
   ```
   apps/dailyverses
   ```
2. Enable the app in **Settings → Apps → Tools**.
3. Add the **Daily Verses** widget to your dashboard.

---

## **⚙️ Configuration**

### **Admin Settings**
Admins can configure:
- Default language  
- Default Bible translation  
- Whether verse links should be shown  
- Whether users may override global settings  

Available under:  
**Settings → Administration → Daily Verses**

### **Personal Settings**
Each user can override:
- Language  
- Translation  
- Link visibility  

Available under:  
**Settings → Personal → Daily Verses**

---

## **🖥️ Dashboard Widget**
The widget displays:
- Today’s verse (or a random verse, if selected)
- Translation and language
- Optional link to dailyverses.net for full context

The widget updates automatically every day.

---

## **🌍 Supported Languages**
The app supports 26 languages, including English, Dutch, German, Danish, and more.  
Translations are provided directly by dailyverses.net.

---

## **🔧 Development**
This app is built using:
- Nextcloud AppFramework (PHP)
- Vite for JavaScript bundling
- Modern ES modules
- JSON‑based l10n files

Source code:  
**[https://github.com/theo-langstraat/dailyverses-nextcloud](https://github.com/theo-langstraat/dailyverses-nextcloud)**

---

## **🐞 Bug Reports & Feedback**
Please report issues or feature requests here:  
**`https://github.com/theo-langstraat/dailyverses-nextcloud/issues` [(github.com in Bing)](https://www.bing.com/search?q="https%3A%2F%2Fgithub.com%2Ftheo-langstraat%2Fdailyverses-nextcloud%2Fissues")**

---

## **📜 License**
This project is licensed under the **AGPL‑3.0‑or‑later**.


