# The Ultimate FYP System Demonstration Guide: Bossku House

This document serves as the master presentation script and examiner guide for demonstrating the **Bossku House QR-Based Digital Ordering System** to secure maximum marks.

---

## Part 1: Overall Demo Strategy & Schedule

### 1. Complete Demo Flow Diagram

```
[QR Menu Redirect] ➔ [Customer Registration + OTP] ➔ [Cart, Notes, Vouchers]
                                                               ⬇
[Admin Moderation] 🏓 [Product Review + Filter] 🏓 [Real-Time Tracking Status]
        ⬇                                                      ⬇
 [Admin Analytics] ➔ [Archived Reports / PDF] ➔ [Cashier Queue + Payments] ➔ [Staff Dashboard]
                                                               ⬇
                                                            [Logout]
```

### 2. Time Management Chart (Total Time: ~14 Minutes)

| Phase | Screen / Feature | Duration | Focus Area |
|---|---|---|---|
| 1 | Table QR Code Landing & Menu Page | 1.5 mins | Session hijacking prevention & dynamic table mapping. |
| 2 | Customer Registration & OTP Validation | 2.0 mins | Security logs & Railway environment bypass. |
| 3 | Cart Management & Voucher Redemption | 1.5 mins | Client-side responsive behavior & state preservation. |
| 4 | Order Placement & Real-Time Tracking Timeline | 2.0 mins | Firebase RTDB WebSocket syncing & live UI updates. |
| 5 | Staff Dashboard (Kitchen Queue) | 1.5 mins | Multi-role synchronization & workflow states. |
| 6 | Cashier Payment Processing (Full & Partial) | 1.5 mins | Backend transactional calculations & ledger integrity. |
| 7 | Loyalty Rewards Catalog & Profile | 1.0 min | NoSQL database nesting performance. |
| 8 | Product Review, Profanity Filter & Moderation | 1.5 mins | Text normalization & API throttling rules. |
| 9 | Admin Dashboard, ApexCharts & PDF Reports | 1.0 min | Analytics processing & printable layout constraints. |
| 10 | Logout & Closing Q&A | 0.5 mins | Session destruction & clear user role wipe. |

---

## Part 2: Screen-by-Screen Demonstration Scripts

---

### Page 1: QR Redirection & Customer Menu Page
* **Objective:** Demonstrate how the system automatically identifies the table context from a physical QR code and renders a mobile-optimized, real-time catalogue without requiring initial logins.
* **What to demonstrate:**
  1. Open a browser tab using the table URL: `/menu/table-5`.
  2. Scroll down to show categories, featured items, and trending sections.
  3. Point out the active table badge in the navbar.
* **Spoken Presentation Script:**
  > "Good morning, panels. I will begin my system demonstration by simulating a customer sitting at Table 5. When the customer scans the physical QR code on their table, they are redirected to this URL: `/menu/table-5`. 
  > 
  > As you can see, the layout is mobile-optimized using a modern, bottom-sticky navigation design. The system has automatically extracted the table number from the URL and stored it in the user's session, displaying 'Table 5' in the navigation bar. Even if the customer refreshes the page, the table reference is preserved. 
  > 
  > Below, we render dynamic catalog items divided by categories, a featured product banner, and our real-time trending products. These values are served directly from our Firebase Realtime Database in under 100 milliseconds."
* **Why this feature is important:** It removes ordering friction. Customers don't need to ask staff for table coordinates or wait to download an app.
* **Technical explanation:** The route parameters are captured via Laravel middleware and loaded into the session container, keeping the routing context secure and isolated from other guest tabs.
* **Expected output:** The menu loads with a "Table 5" badge. Product lists render dynamically.
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *What happens if a user removes the `/table-5` part and accesses the catalog directly?*
  * **A:** *The system defaults the routing to "Counter" mode, which redirects the order to the cashier counter instead of crashing or leaving the table number empty. This is handled defensively by a fallback check in our MenuController.*
* **Transition to next page:**
  > "Now, before I add items to the cart, I want to show how a customer signs up to start earning loyalty points. Let me go to the Registration page."

---

### Page 2: Customer Registration & OTP Validation
* **Objective:** Demonstrate the secure registration flow, including the custom Firebase-backed OTP verification.
* **What to demonstrate:**
  1. Click "Register" in the profile section.
  2. Input a name, email (`testuser@gmail.com`), password, and submit.
  3. Navigate to Railway's Deploy Logs (or local `laravel.log`), find the generated OTP, enter it in the UI, and complete the registration.
* **Spoken Presentation Script:**
  > "For customers who want to earn rewards, they can sign up. Let's create a customer account. I will enter a name, email, and password, then click Register.
  > 
  > Instead of saving users directly, our system triggers a 6-digit OTP verification. Now, since we are deployed in a production container on Railway where outbound SMTP ports are blocked, we've configured our mailer to write transactions directly to our secure logs. 
  > 
  > Let's look at the log terminal. Here, you see the prominent OTP code block highlighted by our custom log markers. I will copy this OTP: `374622`, paste it into the verification input, and submit. 
  > 
  > Perfect! The customer is successfully registered, authenticated, and redirected back to the profile page."
* **Why this feature is important:** Prevents spam registrations and ensures email validity before saving records to the Firebase operational database.
* **Technical explanation:** The registration controller generates a 6-digit code, caches the unverified user data with a 10-minute expiration limit, logs the payload, and commits it to the database only after the inputs match.
* **Expected output:** Successful registration toast notification. The profile shows 0 Boss Points.
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *Why did you write the OTP to the log file instead of sending a real email?*
  * **A:** *In production, serverless platforms like Railway block ports 25, 465, and 587 to prevent outbound spam. For this prototype, routing to the log driver allows instant, cost-free delivery verification without dependency on external email relays.*
* **Transition to next page:**
  > "Now that we are logged in, let's add some items to our cart and apply a discount voucher."

---

### Page 3: Cart Management & Voucher Applied
* **Objective:** Show cart persistence, custom remark entries, and the application of redeemed vouchers to discount the order total.
* **What to demonstrate:**
  1. Go back to the menu and click "Add to Cart" on a few items.
  2. Click the Cart icon to view the summary.
  3. Apply a voucher from the dropdown.
  4. Write notes: "Less ice" on a beverage.
* **Spoken Presentation Script:**
  > "Here is our shopping cart. The items, quantities, and prices are rendered cleanly. 
  > 
  > In the voucher dropdown, we can see the vouchers this customer has earned from redeeming loyalty rewards. I will select the 'Free Orange Juice' voucher. The system automatically recalculates the total, applying the discount. 
  > 
  > Next, I will add an item note, for example: 'Less ice' on our beverage. The cart uses Alpine.js to manage state changes instantly without reloading the page, which keeps the experience smooth and responsive on mobile devices."
* **Why this feature is important:** It permits customized order demands to reach the kitchen, reducing order preparation errors, and lets customers claim loyalty rewards.
* **Technical explanation:** The cart state is managed in the Laravel session, and voucher eligibility is verified against the customer's nested voucher node `/vouchers/{userId}` in Firebase.
* **Expected output:** The order total drops by the voucher amount. Item notes are displayed on the screen.
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *Can a user apply the same voucher twice or tamper with the total amount?*
  * **A:** *No. All voucher states are validated on the backend. When the order is submitted, the system verifies the voucher’s `is_used` attribute in Firebase. If it's already used, the discount is rejected.*
* **Transition to next page:**
  > "With our cart ready, I will submit the order and track its preparation status in real-time."

---

### Page 4: Order Submission & Real-Time Tracking Timeline
* **Objective:** Demonstrate order creation and the WebSocket-driven real-time tracking timeline.
* **What to demonstrate:**
  1. Click "Place Order".
  2. The page redirects to the tracking screen `/c/track/{reference}`.
  3. Point out the tracking timeline status: "Submitted".
* **Spoken Presentation Script:**
  > "I will click 'Place Order' now. The system has cleared the cart and redirected us to the tracking screen with a unique reference code. 
  > 
  > Currently, the status shows 'Submitted: Waiting for kitchen to accept'. Under the hood, this page has established a direct WebSocket connection with Firebase. We are not polling the server or doing page refreshes. The page is listening for events on this specific order reference key. 
  > 
  > To show this in action, we need to look at what the kitchen staff sees. Let me open the Staff Dashboard in a separate window."
* **Why this feature is important:** Reduces anxiety for customers by letting them track their food preparation status, and eliminates the need for loud announcements in the dining area.
* **Technical explanation:** The page loads the Firebase JS SDK and hooks a database listener on the path `/orders/{reference}`. Any update to the `status` attribute instantly updates the UI state.
* **Expected output:** Real-time timeline rendering with status "submitted".
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *Why did you choose Firebase Realtime Database instead of Laravel WebSockets?*
  * **A:** *Setting up standard WebSockets requires running background daemon processes like Pusher or Soketi, which can crash on shared servers or add deployment costs. Firebase handles scaling and WebSockets natively, which makes it highly reliable for restaurant environments.*
* **Transition to next page:**
  > "Let's switch to the staff perspective to process this order."

---

### Page 5: Staff Kitchen Queue Dashboard
* **Objective:** Show the orders dashboard used by the kitchen staff to update preparation states.
* **What to demonstrate:**
  1. Open a new window at `/staff/orders`.
  2. Locate the placed order by its reference code.
  3. Click "Accept Order" (status becomes `in_progress`).
  4. Arrange windows side-by-side to show the customer's timeline updating instantly.
  5. Click "Complete Order" (status becomes `completed`). Show that the customer's page triggers a chime.
* **Spoken Presentation Script:**
  > "This is the Staff Dashboard. It acts as the kitchen display system. Only active orders with status 'submitted' or 'in_progress' are visible here. 
  > 
  > I will locate our order reference and click 'Accept Order'. The status updates to 'Preparing'. If you look at the customer window on the left, it has instantly shifted to 'Preparing' without any manual refresh!
  > 
  > Once the kitchen staff finishes cooking, they click 'Complete Order'. Instantly, the customer’s browser plays a chime sound, and the tracking page shows 'Order Ready for Collection'. This demonstrates our end-to-end real-time synchronization."
* **Why this feature is important:** It connects the kitchen staff directly with customers, improving kitchen output efficiency and order turnover speeds.
* **Technical explanation:** The staff controller triggers a Firebase PATCH request to `/orders/{ref}` to update the status. This mutation is instantly broadcasted to all active listeners.
* **Expected output:** The order moves to the completed section. The customer tracking screen plays a notification chime and shows "Ready".
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *How does the system prevent the alert sound from being blocked by browser autoplay policies?*
  * **A:** *Modern browsers block unsolicited audio. We prompt the staff for a gesture interaction when the dashboard loads to unlock audio permissions. On the customer side, we trigger the audio only after they've clicked "Place Order", which registers their user interaction.*
* **Transition to next page:**
  > "Once the customer collects their food, they must pay. Let's move to the Cashier view."

---

### Page 6: Cashier Queue & Payment Processing (Full & Partial)
* **Objective:** Demonstrate the cashier payment workflow, including partial payment splits for groups sharing a table.
* **What to demonstrate:**
  1. Navigate to `/staff/cashier`.
  2. Select the completed order.
  3. Show the "Partial Payment" panel.
  4. Select a few items (e.g., 1 chicken chop) to pay first, submit, and show the status changes to `partially_paid`.
  5. Process the remaining items to mark the order as fully `paid`.
* **Spoken Presentation Script:**
  > "Here is our Cashier Queue. Completed orders requiring payment appear here. 
  > 
  > In many restaurants, customers sharing a table want to pay separately. Our system supports **Partial Payments**. I will open the partial payment modal, select 1 unit of our main dish, and click process. The payment status has now transitioned to 'Partially Paid'. 
  > 
  > Let's process the remaining amount. Once fully paid, the system marks the order as 'Paid', records the transaction, and prompts us to print the receipt. The points earned from this purchase will also be added to the customer's account."
* **Why this feature is important:** Improves payment processing efficiency for group diners, reducing checkout bottlenecks at the cashier counter.
* **Technical explanation:** The partial payment method reads the items array, increments the `paid_quantity` for selected item indices, and updates the `payment_status` to either `partially_paid` or `paid` atomically in Firebase.
* **Expected output:** The cashier queue list updates. Receipt prints cleanly.
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *How do you prevent a cashier from over-charging or paying for more quantities than ordered?*
  * **A:** *The controller validates the quantities on the server side, ensuring that `paid_quantity` never exceeds the original `ordered_quantity` for each line item index.*
* **Transition to next page:**
  > "Let's check the customer profile to verify that they received their loyalty points."

---

### Page 7: Loyalty Rewards Catalog & Profile
* **Objective:** Show the point accrual and reward voucher redemption.
* **What to demonstrate:**
  1. Navigate back to the customer profile.
  2. Point out the updated "Boss Points" balance.
  3. Go to the Rewards tab, select a reward, and click "Redeem".
  4. Show that points are deducted, and a new voucher code is added to the profile.
* **Spoken Presentation Script:**
  > "Returning to the customer's profile, we can see that their points balance has automatically increased. We award 1 point for every RM1.00 spent. 
  > 
  > I will go to the Rewards tab. Here, the system lists all active rewards setup by the administrator. Let's redeem a 'Free Juice' voucher using our points. 
  > 
  > I'll click 'Redeem'. The points are instantly deducted, and a new voucher has been added to our account, ready to be applied to the next checkout session."
* **Why this feature is important:** Enhances customer retention and builds brand loyalty through a gamified reward loop.
* **Technical explanation:** The redemption endpoint checks points thresholds, deducts the required points from the `/users/{id}` node, records a ledger entry under `/points_history`, and pushes a new voucher to `/vouchers/{id}`.
* **Expected output:** Points balance decreases. A new voucher is displayed.
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *What happens if the customer has points that are about to expire?*
  * **A:** *We display a visual warning banner on the customer profile if any points are expiring within 30 days. Points expiration is handled in the background by a scheduled Artisan command running daily.*
* **Transition to next page:**
  > "Next, I'll demonstrate the customer feedback review system and moderation controls."

---

### Page 8: Product Review, Profanity Filter & Moderation
* **Objective:** Show how customers leave product reviews, how the profanity filter blocks offensive words, and how admins moderate comments.
* **What to demonstrate:**
  1. Go to a product page and write a review containing an offensive word (e.g., "This food is bad and useless").
  2. Submit the review, and point out that the profanity filter has masked the blacklisted keyword (e.g., "This food is bad and *******").
  3. Go to Admin Panel → Review Moderation, and show the option to delete or approve reviews.
* **Spoken Presentation Script:**
  > "To ensure feedback quality, we've implemented a product review system on each product. 
  > 
  > I will write a review. Let's type: 'This food is bad and useless'. Our system includes a built-in **Profanity Filter**. When I click submit, the system normalizes the string, compares it against our blacklist array, and masks the offensive word with asterisks. 
  > 
  > To manage spam, we also have an Admin Moderation Panel. The admin can review all submitted comments and choose to delete inappropriate content dynamically."
* **Why this feature is important:** Protects the brand's online reputation from trolls and keeps public customer feedback clean and constructive.
* **Technical explanation:** The controller runs the review comment through a custom filter array. Reviews are stored in Firebase nested under `/reviews/{productId}/{reviewCode}`.
* **Expected output:** The review is posted with censored keywords. The admin panel displays the moderation queue.
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *Is the profanity filter case-sensitive, and how can you add new words?*
  * **A:** *The filter normalizes the text to lowercase before matching, so it is case-insensitive. New blacklisted words can be added directly to the configuration array file in Laravel.*
* **Transition to next page:**
  > "Finally, let's explore the Admin Analytics and report generation engine."

---

### Page 9: Admin Dashboard, ApexCharts & PDF Reports
* **Objective:** Demonstrate the sales metrics visualizer and the printable sales report download.
* **What to demonstrate:**
  1. Open `/admin/dashboard`.
  2. Scroll to show the interactive revenue charts powered by ApexCharts.
  3. Go to the Sales Reports page, select a month, and click "Generate Report".
  4. Open the downloaded PDF to show the structured sales summaries.
* **Spoken Presentation Script:**
  > "This is our Admin Dashboard. We use **ApexCharts** to render interactive sales graphs, daily revenue curves, and category-wise sales splits. 
  > 
  > From the Reports tab, administrators can generate financial reports. I will select the current month and click 'Generate Report'. The backend compiles the transactional records and outputs a clean PDF report. 
  > 
  > As you can see, the PDF is formatted with custom print stylesheets, wrapping names cleanly and highlighting summary totals. This provides the restaurant owners with reliable business intelligence reports."
* **Why this feature is important:** Enables administrative decision-making by tracking revenue trends and exporting clean tax/accounting documentation.
* **Technical explanation:** The controller queries all completed orders for the period, calculates sales aggregations, and uses the `barryvdh/laravel-dompdf` library to compile a print-optimized PDF layout.
* **Expected output:** Sales graph renders dynamically. PDF downloads successfully with accurate numbers.
* **Common Examiner Questions & Ideal Answers:**
  * **Q:** *Does loading massive historical sales data crash the admin panel in-memory?*
  * **A:** *To prevent memory crashes, we write pre-aggregated daily summaries. Instead of loading every historical line item, the charts query pre-compiled aggregate records.*
* **Transition to next page:**
  > "This concludes the main operational flows. I will now log out of the system."

---

### Page 10: Logout Flow & Conclusion
* **Objective:** Cleanly terminate the authenticated user session and return to the main guest view.
* **What to demonstrate:**
  1. Click "Logout".
  2. Confirm return to the default guest menu page.
* **Spoken Presentation Script:**
  > "I will now log out of the system. The session has been securely invalidated, and we are returned to the default customer view. 
  > 
  > In conclusion, the Bossku House system successfully addresses table identification, real-time preparation tracking, secure customer loyalty, and payment processing within a single hybrid framework. I am now open to any questions from the panels. Thank you."
* **Expected output:** Session is terminated. Redirected back to `/login` or `/menu`.

---

## Part 3: Secrets to Scoring Maximum Marks

### 1. High-Value Technical Terms to Use (Inject into your speech)
- *"Atomic Transactions in NoSQL"* (When talking about points updates)
- *"WebSocket-Driven Event Listeners"* (When explaining Firebase tracking)
- *"State Management & DOM Lifecycle"* (When talking about Alpine.js cart)
- *"Data Normalization"* (When talking about the profanity string checker)
- *"Ephemeral Filesystem Constraints"* (When explaining why you chose Firebase over local storage on Railway)

### 2. High-Score Checklist (Make sure to highlight these!)
- [ ] Show the user timeline updating **immediately** when the staff clicks a button. (Lecturers love real-time sync).
- [ ] Explain **why** you didn't use Pusher. (Shows critical software evaluation skills).
- [ ] Point out the **profanity filter** censoring bad words. (Shows detail and polish).
- [ ] Show a **partial payment** split. (Proves you solved a real-world restaurant problem).
- [ ] Mention the **User Provider override** to support UUIDs in Laravel. (Highly technical, scores very high in coding standards).

---

## Part 4: Common Demonstration Mistakes & How to Avoid Them

### 1. The "Autoplay Sound Block" Trap
* **The Mistake:** Students try to show the notification sound, but the browser blocks it because the tab hasn't received a user click gesture yet.
* **How to Avoid:** Always click somewhere on the customer tracking page (e.g., adjust view, click details) before you trigger the completion status on the staff side. This tells the browser the page is interactive and unlocks the speaker.

### 2. The "Fake Data" Examiner Catch
* **The Mistake:** Generating reports with static mock data. Lecturers will ask you to place a new order during the demo and check if the PDF sales count goes up.
* **How to Avoid:** Clear old test data before the demo, place a live order during the flow, mark it as paid, and then generate the PDF report to show the numbers update dynamically.

### 3. The "Silent Crash on Empty Cart"
* **The Mistake:** Accidental clicks on "Place Order" when the cart is empty, causing a 500 error page to load.
* **How to Avoid:** Verify your controllers redirect back with validation messages if the cart is empty (our code does this). Test this boundary limit in front of the examiners to prove system stability.

### 4. Running Out of Time
* **The Mistake:** Spending 10 minutes on registration and login, leaving no time to show the real-time tracker or payment flow.
* **How to Avoid:** Stick to the time management chart. Move quickly to the checkout and real-time tracking as they carry the highest grading weight.
