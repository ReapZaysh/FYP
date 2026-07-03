# Presentation Script: Bossku House Q&A Defense Panel

**Format:** 4-Presenter Panel Presentation Script  
**Target Audience:** Final Year Project (FYP) Examiners  
**Roles:**
- **Presenter 1 (Systems & Security Lead):** Handles Q1 (Module Strengths) & Introduces the panel.
- **Presenter 2 (Frontend & UX Lead):** Handles Q2 (Future Improvements / 3-Month Plan).
- **Presenter 3 (Backend & DevOps Lead):** Handles Q3 (Technical Challenges).
- **Presenter 4 (Software Architect):** Handles Q4 (Design Retrospective / Restarting Today) & Concludes the presentation.

---

### [Slide 1: Title Slide — Q&A Session]

**Presenter 1:**
> "Good morning, respected panels and examiners. Today, our team will address the core technical aspects of the **Bossku House QR Digital Ordering System** by answering the key operational, architectural, and design questions. I will pass the floor to each of our module leads to present their respective domains. Let's begin with the strengths of our developed system."

---

### [Slide 2: Question 1 — System Strengths]

**Presenter 1:**
> "So, **what are the core strengths of our module?** 
> 
> The greatest strength lies in our **Hybrid Real-Time Architecture**. Instead of setting up resource-heavy WebSocket servers or relying on performance-draining HTTP polling, we bridged **Laravel 12** on the backend with **Firebase Realtime Database** in the frontend. This hybrid approach lets us run our operational menu system, kitchen statuses, and customer tracking timelines at ultra-low latency, pushing real-time updates to customer tracking screens in under 100 milliseconds.
> 
> Second is the **Zero-Friction Guest Experience**. Most digital systems force customers to sign up before ordering. We bypassed this friction completely. By utilizing table-specific QR URL queries and browser-based local storage tracking, a guest can sit, scan, order, and track their preparation status instantly.
> 
> Third, we prioritized **Security-First Loyalty and Voucher Verification**. NoSQL databases are inherently prone to client-side vulnerabilities. To resolve this, we ensure that points calculations and voucher usage rules are verified and processed entirely on the backend server via `floor($orderTotal)` logic, blocking any attempts at post-parameter tampering.
> 
> Lastly, we established a **Granular Role-Based Access Control (RBAC)** flow. Admin and staff models authenticate securely via Laravel sessions, while customers authenticate through a custom-written `FirebaseUserProvider` driver."
> 
> "Next, I will hand over to [Presenter 2's Name] to discuss how we would expand the system if we had more time."

---

### [Slide 3: Question 2 — Future Improvements (Three More Months)]

**Presenter 2:**
> "Thank you, [Presenter 1's Name]. **If we had three more months to improve this project, we would target scalability, offline reliability, and deep hardware integration.**
> 
> First, we would transition the customer ordering module into a **Progressive Web App (PWA) with Offline-First Resilience**. By using Service Workers and browser IndexedDB, if a customer loses their connection or the restaurant's Wi-Fi drops, they could still browse the menu and add items to their cart. Once reconnected, the service worker would sync the queued order payload directly to Firebase.
> 
> Second, we would integrate **Direct e-Wallet & Payment Gateways** (such as Stripe, FPX, or Touch 'n Go). Currently, our system relies on cashiers manually marking orders as paid at the counter. A direct payment gateway hook would allow the Firebase order status to transition automatically the instant a digital payment clears.
> 
> Third, we would configure **Direct Hardware-Level Thermal Printing (ESC/POS)**. Instead of forcing staff to click 'print receipt' and interact with standard web browser print dialogs, we would deploy a native background print service. The kitchen printers would automatically print receipt slips the second an order is marked as 'Submitted' in Firebase.
> 
> Lastly, we would implement **Advanced Analytics Forecasting**. We would leverage our current data tables to build predictive models that alert admins of ingredient shortages and predict customer volumes during peak restaurant hours."
> 
> "Now, I will pass the floor to [Presenter 3's Name] to walk us through the technical hurdles we encountered during implementation."

---

### [Slide 4: Question 3 — Technical Challenges]

**Presenter 3:**
> "Thank you. **When building this hybrid application, we faced two major technical hurdles.**
> 
> The first was the **UUID-to-Integer Type Casting conflict in Laravel's Auth Session**. 
> 
> Laravel's default session guard is designed for relational databases with incrementing integer keys. However, our customers are authenticated via Firebase and use alphanumeric UUID strings. Initially, when Laravel’s Eloquent model loaded the Firebase user payload, it cast the UUID string into an integer due to default `$keyType = 'int'` configurations. For example, a UUID like `'3f91a92f-...'` was truncated to `3`. This resulted in the session manager looking up `users/3` in Firebase, causing silent session dropouts and logout loops.
> 
> We resolved this by explicitly disabling auto-incrementing keys and overriding the primary key type to string in our `User` model, followed by writing a custom `FirebaseUserProvider` driver to bridge NoSQL JSON formats with Laravel's session guard.
> 
> Our second major hurdle was **Railway's Ephemeral Filesystem and Outbound SMTP Port Restrictions**.
> 
> In production, Railway operates on ephemeral containers and blocks outbound ports 25, 465, and 587 to prevent spam. This meant our SQLite databases would reset on every deployment, and Gmail SMTP attempts to send verification OTPs or password reset tokens timed out. 
> 
> To overcome this:
> 1. We bypassed database writes for password resets entirely by storing reset tokens securely in Firebase paths.
> 2. We switched our mail driver to `log` and injected high-visibility console markers (`========== OTP CODE ==========`). This allowed both customers and developers to easily verify codes directly from Railway's live application stream."
> 
> "Next, [Presenter 4's Name] will explain our design retrospective and what we would do differently."

---

### [Slide 5: Question 4 — Project Retrospective (Restarting Today)]

**Presenter 4:**
> "Thank you, [Presenter 3's Name]. **If we were to restart the development of this project today, we would make three key changes to our architectural design.**
> 
> First, we would adopt a **Unified Database Engine (like Supabase PostgreSQL or Firestore)** instead of our current hybrid setup. Maintaining SQLite for framework cache/sessions while syncing customer catalogs to Firebase RTDB requires manual data synchronization controllers. Moving to a single engine like Supabase would provide the best of both worlds: relational integrity, migrations, and native WebSockets for real-time order tracking.
> 
> Second, we would decouple the frontend entirely by building a **Single Page Application (SPA)** using **Next.js** or **Vue**, leaving Laravel to act purely as a stateless JSON REST API. This would optimize page load speeds on mobile networks, make animations smoother, and establish a cleaner division of labor between frontend views and backend microservices.
> 
> Finally, we would utilize **Native Firebase Authentication SDKs** directly on the client-side rather than wrapping Firebase lookups within Laravel's session guards. This would simplify third-party social logins (like Google or Apple login) and allow us to enforce secure database access policies using native Firebase Security Rules."
> 
> **Presenter 4 (Conclusion):**
> "To conclude, the developed architecture achieves a robust, real-time customer ordering flow, but these retrospectives provide a clear pathway for enterprise-level scaling. We are now ready to open the floor to the panels for any questions. Thank you."
