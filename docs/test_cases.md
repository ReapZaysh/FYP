# Test Cases — Bossku House QR Digital Ordering System
**Version:** 1.0 | **Project:** FYP — Bossku House

---

## UC01 — Scan QR Code

| Field | Detail |
|---|---|
| **Use Case ID** | UC01 |
| **Use Case Name** | Scan QR Code |
| **Description** | A Guest Customer scans a QR code on the table using their mobile device. The system redirects them to the menu page with the table number pre-filled in the session. |
| **Primary Actor** | Guest Customer |
| **Include Use Cases** | UC02 (Browse Menu by Category) |
| **Pre-condition** | A unique QR code is placed on the restaurant table. The customer has a mobile device with a camera and internet access. |
| **Post-condition** | The customer is redirected to `/menu?table={id}`. The table number is stored in the session/local storage. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer is at the table | Customer opens camera app and scans QR code | Device opens browser with URL `/menu?table=5` |
| 2 | Browser opens menu URL | System reads the `table` query parameter | Table ID is stored in session |
| 3 | Table ID stored | System loads the menu page | Menu is displayed with table number shown |

### Alternate Flow
- **AF01-A:** Customer scans QR but already has a session with a different table → system overwrites session with new table ID.
- **AF01-B:** Customer manually types the menu URL without a table ID → menu loads but table field is empty; customer must enter it manually before placing order.

### Robustness Flow (Exception)
- **RF01-A:** QR code is damaged/unreadable → camera fails to decode; customer must manually navigate to the menu URL.
- **RF01-B:** Network is unavailable → browser shows connection error; system does not crash, displays a user-friendly error.
- **RF01-C:** QR code points to a non-existent table ID → system loads the menu but flags the table as invalid; customer cannot place order until a valid table is provided.

---

## UC02 — Browse Menu by Category

| Field | Detail |
|---|---|
| **Use Case ID** | UC02 |
| **Use Case Name** | Browse Menu by Category |
| **Description** | The customer views the menu and filters products by selecting a category (e.g., Drinks, Main Course). |
| **Primary Actor** | Guest Customer |
| **Include Use Cases** | — |
| **Pre-condition** | Customer is on the `/menu` page. At least one category and one product exist in the database. |
| **Post-condition** | The menu displays only products belonging to the selected category. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Menu page is loaded | System fetches all categories from DB | Category list is rendered on the page |
| 2 | Categories are visible | Customer clicks a category name | System filters products by that category |
| 3 | Filtered results ready | System renders filtered products | Only matching products are displayed with name, image, price, description |

### Alternate Flow
- **AF02-A:** Customer selects "All" category → system displays all available products.
- **AF02-B:** No products exist in selected category → system displays "No items available" message.

### Robustness Flow (Exception)
- **RF02-A:** Database query fails → system shows a 500 error page or a generic "Unable to load menu" message.
- **RF02-B:** Product image is missing/broken → system displays a placeholder image; other product details remain visible.

---

## UC03 — Add Item to Cart

| Field | Detail |
|---|---|
| **Use Case ID** | UC03 |
| **Use Case Name** | Add Item to Cart |
| **Description** | The customer clicks "Add to Cart" on a product. The system adds it to the cart and updates the cart total in real-time. |
| **Primary Actor** | Guest Customer |
| **Include Use Cases** | — |
| **Pre-condition** | Customer is on the menu page. The product is available. |
| **Post-condition** | The item is added to the session-based cart. Cart total is updated. Cart icon shows updated count. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Product is displayed | Customer clicks "Add to Cart" button | POST request sent to `/cart/add/{product}` |
| 2 | Request received | System adds product to session cart | Cart array updated with product ID and quantity 1 |
| 3 | Cart updated | System returns updated cart count | Cart icon badge updates to reflect new count |

### Alternate Flow
- **AF03-A:** Item already in cart → system increments quantity by 1 instead of adding a duplicate.
- **AF03-B:** Customer adds same item multiple times rapidly → throttle/debounce prevents duplicate submissions.

### Robustness Flow (Exception)
- **RF03-A:** Product no longer exists (deleted by admin) → system returns 404; cart is not updated; customer sees "Product not available" message.
- **RF03-B:** Session expires → system creates new session and re-adds item; table ID may be lost.

---

## UC04 — Manage Cart (Adjust Qty / Remove / Clear)

| Field | Detail |
|---|---|
| **Use Case ID** | UC04 |
| **Use Case Name** | Manage Cart |
| **Description** | The customer views their cart and can increase/decrease item quantities, remove individual items, or clear the entire cart. |
| **Primary Actor** | Guest Customer |
| **Include Use Cases** | — |
| **Pre-condition** | Customer has at least one item in the cart. Customer is on the `/cart` page. |
| **Post-condition** | Cart reflects the updated quantities or items. Total price recalculates accordingly. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer navigates to `/cart` | System loads cart items from session | All cart items displayed with qty and subtotal |
| 2 | Items displayed | Customer clicks "+" or "–" | Quantity updates; subtotal and grand total recalculate |
| 3 | Customer removes item | Customer clicks "Remove" → POST to `/cart/remove/{product}` | Item removed from session cart; total updates |
| 4 | Customer clears cart | Customer clicks "Clear Cart" → DELETE to `/cart/clear` | Cart is emptied; empty cart message shown |

### Alternate Flow
- **AF04-A:** Customer decreases quantity to 0 → system automatically removes the item from cart.

### Robustness Flow (Exception)
- **RF04-A:** Customer tries to remove a product not in the cart → system returns gracefully with no error; cart remains unchanged.
- **RF04-B:** Session expires mid-cart-management → cart becomes empty; customer is notified.

---

## UC05 — Place Order

| Field | Detail |
|---|---|
| **Use Case ID** | UC05 |
| **Use Case Name** | Place Order |
| **Description** | The customer submits their cart as an order. The system saves the order to the database, generates a unique reference number, and notifies staff via Firebase. |
| **Primary Actor** | Guest Customer |
| **Include Use Cases** | UC01 (table ID from QR), Generate Unique Order Reference, Receive Real-time Order Updates |
| **Pre-condition** | Cart has at least one item. Table number is stored in session. |
| **Post-condition** | Order is saved in DB with status "Pending". A unique reference number is generated. Staff dashboard receives Firebase notification. Customer is redirected to order tracking page. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Cart is not empty | Customer clicks "Place Order" / "Checkout" | POST sent to `/order` |
| 2 | Request received | System validates cart and table number | Validation passes |
| 3 | Validation passed | System creates Order and OrderItems in DB | Order saved with status = "Pending" |
| 4 | Order saved | System generates unique reference number | Reference stored in orders table |
| 5 | Reference created | System pushes notification to Firebase | Staff dashboard updates in real-time |
| 6 | Firebase notified | System clears the session cart | Customer redirected to `/track/{reference}` |

### Alternate Flow
- **AF05-A:** Customer is authenticated → loyalty points will be eligible to be earned after order is marked Paid/Completed.
- **AF05-B:** Customer places order without table number → system prompts customer to enter table number before submitting.

### Robustness Flow (Exception)
- **RF05-A:** Rate limit exceeded (> 5 orders/min per session) → system returns HTTP 429 "Too Many Requests".
- **RF05-B:** Firebase is unreachable → order is still saved to DB; staff will see it on next manual refresh; customer still gets reference number.
- **RF05-C:** Database write fails → transaction is rolled back; customer is shown "Order failed, please try again."

---

## UC06 — Track Order Status

| Field | Detail |
|---|---|
| **Use Case ID** | UC06 |
| **Use Case Name** | Track Order Status |
| **Description** | The customer uses their order reference number to view the real-time status of their order (Pending → Preparing → Served). |
| **Primary Actor** | Guest Customer |
| **Include Use Cases** | Receive Real-time Order Updates (Firebase) |
| **Pre-condition** | An order has been successfully placed. The customer has the reference number. |
| **Post-condition** | The tracking page displays the current order status, which updates automatically when staff changes it. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer has reference | Customer visits `/track/{reference}` | System queries DB for order by reference |
| 2 | Order found | System renders order details and current status | Tracking page displayed |
| 3 | Page loaded | Firebase listener is initialised | Page subscribes to real-time status updates |
| 4 | Staff updates status | Firebase pushes update to client | Status badge changes automatically (no page refresh) |

### Alternate Flow
- **AF06-A:** Order status is "Served" → page shows "Your order has been served!" and disables further updates.

### Robustness Flow (Exception)
- **RF06-A:** Invalid reference number → system returns 404 "Order not found."
- **RF06-B:** Firebase disconnected → page shows last known status with a "Live updates paused" warning.

---

## UC07 — View Rewards Catalogue

| Field | Detail |
|---|---|
| **Use Case ID** | UC07 |
| **Use Case Name** | View Rewards Catalogue |
| **Description** | Any visitor (guest or authenticated) can browse the rewards catalogue. Only authenticated customers can redeem rewards. |
| **Primary Actor** | Guest Customer / Authenticated Customer |
| **Include Use Cases** | UC11 (Redeem Reward — authenticated only) |
| **Pre-condition** | The admin has configured at least one reward item with a point cost. |
| **Post-condition** | Rewards catalogue is displayed with item names, descriptions, and required points. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer visits `/rewards` | System queries rewards table | Reward items fetched from DB |
| 2 | Rewards fetched | System renders rewards list | Each item shown with name, image, point cost |
| 3 | Authenticated customer | "Redeem" button is active and shows current points balance | Customer can proceed to redeem |

### Alternate Flow
- **AF07-A:** Guest customer views rewards → "Redeem" button is disabled or prompts login.

### Robustness Flow (Exception)
- **RF07-A:** No rewards configured → page shows "No rewards available at this time."
- **RF07-B:** DB query fails → generic error message displayed.

---

## UC08 — Register Account

| Field | Detail |
|---|---|
| **Use Case ID** | UC08 |
| **Use Case Name** | Register Account |
| **Description** | A guest customer creates a new customer account by providing a name, email, and password. |
| **Primary Actor** | Guest Customer |
| **Include Use Cases** | — |
| **Pre-condition** | Customer is not logged in. Customer navigates to `/c/register`. |
| **Post-condition** | A new user record is created in the DB with `role = customer`. Customer is logged in and redirected to menu. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer on register page | Customer fills in name, email, password, password confirmation | Form data ready |
| 2 | Form submitted | System validates input (required, email format, min password length) | Validation passes |
| 3 | Validation passed | System hashes password and creates User record | User saved in DB |
| 4 | User created | System logs in the new user | Auth session started |
| 5 | Session started | System redirects to menu | Customer lands on menu as authenticated user |

### Alternate Flow
- **AF08-A:** Customer already has an account → directed to login page.

### Robustness Flow (Exception)
- **RF08-A:** Email already registered → validation error "Email has already been taken."
- **RF08-B:** Password confirmation mismatch → validation error "Password confirmation does not match."
- **RF08-C:** Weak password (< 8 chars) → validation error shown inline.
- **RF08-D:** SQL/DB error during user creation → transaction rolled back; error message shown.

---

## UC09 — Login (Customer)

| Field | Detail |
|---|---|
| **Use Case ID** | UC09 |
| **Use Case Name** | Login (Customer) |
| **Description** | A registered customer logs into their account via the customer login page at `/c/login`. |
| **Primary Actor** | Authenticated Customer |
| **Include Use Cases** | — |
| **Pre-condition** | Customer has a registered account. Customer is not currently logged in. |
| **Post-condition** | Customer is authenticated. Session is created. Customer redirected to menu page. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer on `/c/login` | Customer enters email and password | Form data submitted via POST |
| 2 | Data received | System checks credentials against DB | Match found |
| 3 | Credentials valid | System creates auth session | Laravel session initialised |
| 4 | Session active | System redirects to `/menu` | Customer is now authenticated |

### Alternate Flow
- **AF09-A:** Customer tries to access a protected route while not logged in → redirected to `/c/login`.

### Robustness Flow (Exception)
- **RF09-A:** Wrong password → "These credentials do not match our records."
- **RF09-B:** Email not registered → same generic error (to prevent user enumeration).
- **RF09-C:** Account is inactive/suspended → login rejected with appropriate message.
- **RF09-D:** Brute-force attempts → Laravel throttle middleware locks out after repeated failures.

---

## UC10 — Earn Loyalty Points

| Field | Detail |
|---|---|
| **Use Case ID** | UC10 |
| **Use Case Name** | Earn Loyalty Points |
| **Description** | After an authenticated customer's order is marked as Paid/Completed, the system automatically awards loyalty points to their account. |
| **Primary Actor** | Authenticated Customer |
| **Include Use Cases** | UC05 (Place Order) |
| **Pre-condition** | Customer is authenticated. An order has been placed and marked as Paid by staff. |
| **Post-condition** | Customer's loyalty points balance is incremented. Points are visible on their profile. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Order placed by auth customer | Staff marks order as Paid | `orders.markAsPaid` route triggered |
| 2 | Payment confirmed | System calculates points earned (based on order total) | Points value computed |
| 3 | Points calculated | System updates user's `points` balance in DB | User record updated |
| 4 | Points updated | Customer views profile | Updated points balance shown |

### Alternate Flow
- **AF10-A:** Guest customer (unauthenticated) places order → no points are awarded.

### Robustness Flow (Exception)
- **RF10-A:** Points update fails (DB error) → order is still marked paid; points update is retried or flagged for manual correction.

---

## UC11 — Redeem Reward Item

| Field | Detail |
|---|---|
| **Use Case ID** | UC11 |
| **Use Case Name** | Redeem Reward Item |
| **Description** | An authenticated customer redeems a reward item from the catalogue by spending their loyalty points. |
| **Primary Actor** | Authenticated Customer |
| **Include Use Cases** | UC07 (View Rewards Catalogue) |
| **Pre-condition** | Customer is authenticated. Customer has sufficient loyalty points. The reward item is available. |
| **Post-condition** | Customer's points are deducted. Redemption record is created. Reward is flagged as redeemed. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer on `/rewards` | Customer clicks "Redeem" on a reward | POST to `/rewards/redeem/{id}` |
| 2 | Request received | System checks customer's current points | Points are sufficient |
| 3 | Points confirmed | System deducts points and creates redemption record | DB updated |
| 4 | Redemption saved | System redirects with success message | Customer sees "Reward redeemed successfully!" |

### Alternate Flow
- **AF11-A:** Customer has exactly the required points → redeems successfully; balance becomes 0.

### Robustness Flow (Exception)
- **RF11-A:** Insufficient points → system rejects with "You do not have enough points."
- **RF11-B:** Reward item no longer exists → system returns 404.
- **RF11-C:** Auth middleware fails → customer redirected to login.
- **RF11-D:** Race condition (double-click) → second request fails gracefully; points not double-deducted.

---

## UC12 — Submit Product Review

| Field | Detail |
|---|---|
| **Use Case ID** | UC12 |
| **Use Case Name** | Submit Product Review |
| **Description** | An authenticated customer submits a star rating and text review for a menu product they have ordered. |
| **Primary Actor** | Authenticated Customer |
| **Include Use Cases** | UC05 (Place Order — must have ordered the product) |
| **Pre-condition** | Customer is authenticated. Customer has previously ordered the product. |
| **Post-condition** | Review is saved in the DB linked to the product. Review is visible on the menu page. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer on product page | Customer selects a star rating (1–5) and types a review | Form filled |
| 2 | Form submitted | POST to `/reviews/{product}` | System validates input |
| 3 | Validation passed | System saves review with rating, text, customer ID, product ID | Review stored in DB |
| 4 | Review saved | System returns success | Review appears under the product |

### Alternate Flow
- **AF12-A:** Customer submits review without text (rating only) → system accepts if text is optional per config.

### Robustness Flow (Exception)
- **RF12-A:** Rate limit exceeded (> 3 reviews/min) → HTTP 429.
- **RF12-B:** Customer has not ordered the product → system rejects with "You can only review products you have ordered."
- **RF12-C:** Profanity/spam submitted → admin can delete via moderation (UC25).

---

## UC13 — View My Profile / Edit Profile

| Field | Detail |
|---|---|
| **Use Case ID** | UC13 |
| **Use Case Name** | View My Profile / Edit Profile |
| **Description** | An authenticated customer views their profile (loyalty points, order history) and can update their name, email, or password. |
| **Primary Actor** | Authenticated Customer |
| **Include Use Cases** | — |
| **Pre-condition** | Customer is authenticated. Customer navigates to `/my-profile`. |
| **Post-condition** | Updated profile information is saved to the DB. Customer sees confirmation message. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Customer on `/my-profile` | System loads customer data from DB | Name, email, points balance shown |
| 2 | Customer edits name/email | Customer clicks Save → PATCH to `/profile` | System validates and saves changes |
| 3 | Customer changes password | Customer fills current + new password | System verifies current password, hashes new one, saves |

### Alternate Flow
- **AF13-A:** Customer only views profile without editing → no changes; read-only display.

### Robustness Flow (Exception)
- **RF13-A:** New email already taken → validation error.
- **RF13-B:** Current password entered incorrectly → system rejects password change.
- **RF13-C:** Customer deletes account → DELETE to `/profile`; all customer data removed.

---

## UC14 — View Real-time Orders (Staff)

| Field | Detail |
|---|---|
| **Use Case ID** | UC14 |
| **Use Case Name** | View Real-time Orders Dashboard |
| **Description** | Staff members log into the staff dashboard and view incoming orders in real-time, powered by Firebase. |
| **Primary Actor** | Staff |
| **Include Use Cases** | Login (Staff/Admin via Laravel Auth) |
| **Pre-condition** | Staff is authenticated with `role = staff`. Staff navigates to `/staff/orders`. |
| **Post-condition** | The orders dashboard is displayed showing all active (Pending/Preparing) orders. New orders appear automatically. |

### Main Flow

| Step | Pre-condition | Action | Post-condition |
|---|---|---|---|
| 1 | Staff navigates to `/staff/orders` | Auth + staff middleware validates role | Access granted |
| 2 | Access granted | System queries DB for active orders | Orders fetched |
| 3 | Orders displayed | Firebase listener initialised on page | Real-time updates active |
| 4 | New order placed | Firebase pushes event to staff page | New order card appears without page refresh |

### Alternate Flow
- **AF14-A:** No active orders → dashboard shows "No pending orders."

### Robustness Flow (Exception)
- **RF14-A:** Staff is not authenticated → redirected to `/login`.
- **RF14-B:** Staff has wrong role (e.g., admin) → admin middleware redirects to admin dashboard.
- **RF14-C:** Firebase unavailable → dashboard shows existing DB data; auto-refresh polling fallback activates.
