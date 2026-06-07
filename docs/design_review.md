# Design Review (DR) Reports — Bossku House QR Digital Ordering System
**Project Context:** Final Year Project (FYP) — Bossku House  
**Report Type:** Progressive Weekly Technical Design Reviews  

---

## Week 1: Project Initialization & MySQL Database Schema
**Features Added:** Laravel 12 workspace setup, local environments, and core MySQL database tables (users, products, categories).

### Design Review (DR) Report - Week 1 (Database & Setup)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Core MySQL schemas lack unique indexing on product slugs, which could lead to routing collisions on detail pages. | A1 |
| 2. | Relational integrity: The `products` table does not specify a cascading constraint on category deletions. | A2 |
| 3. | Initial database seeder uses raw strings for password hashing instead of bcrypt, slowing down system verification. | A3 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A1.** | Add unique constraints to `slug` fields in migration files for `products` and `categories`. | | | | |
| **A2.** | Implement `onDelete('cascade')` or set default constraints on the `category_id` foreign key in products. | | | | |
| **A3.** | Update `DatabaseSeeder.php` to wrap seeded passwords in `bcrypt('password')`. | | | | |

---

## Week 2: User Authentication Guard & Firebase User Provider Configuration
**Features Added:** Custom `FirebaseUserProvider` registered inside Laravel's web guard session, customer documents mapped to `GenericUser`, and `IsAdmin` / `IsStaff` middleware blocks.

### Design Review (DR) Report - Week 2 (Authentication & RBAC)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Custom `FirebaseUserProvider` does not cache user data locally, creating repetitive API network lookups on every page request. | A4 |
| 2. | The `IsStaff` middleware does not allow admin roles to access staff routes, restricting administrators from acting as backup kitchen staff. | A5 |
| 3. | Password verify check: System errors if user document retrieved from NoSQL lacks the password attribute. | A6 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A4.** | Integrate session or redis caching inside `retrieveById` in `FirebaseUserProvider.php` to decrease API load. | | | | |
| **A5.** | Refactor `IsStaff` middleware to check `role === 'staff' \|\| role === 'admin'`. | | | | |
| **A6.** | Add defensive array checking (`isset($user['password'])`) inside `validateCredentials`. | | | | |

---

## Week 3: Table QR Code Routing and Session Table Tracking
**Features Added:** Dynamic routes for `/menu/{table?}`, query parameter extraction, and session-based table number storage.

### Design Review (DR) Report - Week 3 (QR Redirection)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Missing query string handling: Accessing the menu URL directly without a table parameter crashes the session loader. | A7 |
| 2. | QR table numbers are not validated, allowing malicious inputs like script injection via the URL table query. | A8 |
| 3. | Missing customer navigation indicator showing their current active table number. | A9 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A7.** | Implement a fallback value (`'Counter'`) in the `MenuController` when the table parameter is absent. | | | | |
| **A8.** | Add standard alphanumeric input sanitization on URL parameters before pushing them to session arrays. | | | | |
| **A9.** | Render an visual badge (e.g., "Table 5") in the navbar layout so customers can see their active table context. | | | | |

---

## Week 4: Mobile-Responsive Customer View Layout and Dynamic Cart Interactions
**Features Added:** Mobile-first layout system with bottom sticky navigation bars, AlpineJS state controllers, and AJAX-driven product additions to session carts.

### Design Review (DR) Report - Week 4 (Menu Layout & Cart UI)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Heavy assets: Product images are loaded raw instead of optimized, causing lags on slower 3G mobile networks. | A10 |
| 2. | Clicking "Add to Cart" triggers full page refreshes instead of smooth AJAX transitions, reducing modern micro-interaction quality. | A11 |
| 3. | Category navigation: Swiping between Western, Local, and Beverages lists lacks visual highlights on the active slide. | A12 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A10.** | Implement WebP formatting and crop standard image uploads to `400x400` pixels before loading. | | | | |
| **A11.** | Refactor the card interaction to use AlpineJS custom events (`@cart-updated.window`) and AJAX POST routines. | | | | |
| **A12.** | Highlight active categories dynamically by binding CSS classes based on active state parameters in AlpineJS. | | | | |

---

## 5. Week 5: Customer Order Submission & Rate Limiting Controls
**Features Added:** Cart review UI, checkout fields, Firebase NoSQL order creation, and `throttle` protections on the submit route.

### Design Review (DR) Report - Week 5 (Order Checkout)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Checkout submissions allow blank carts, which creates empty order logs in the database. | A13 |
| 2. | Lack of double-submit prevention: Users can rapidly click the "Place Order" button, generating duplicate records. | A14 |
| 3. | Static order limit rules fail to notify the customer why their order was blocked during peak hours. | A15 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A13.** | Add backend validator assertions inside `OrderController` ensuring cart array is not empty before parsing checkout. | | | | |
| **A14.** | Disable the submit button immediately upon click using AlpineJS state controllers (`x-bind:disabled="loading"`). | | | | |
| **A15.** | Catch throttle exception handlers in Laravel to output clean, localized "Rate limit reached" system dialogs. | | | | |

---

## 6. Week 6: Customer Real-Time Order Status Tracking Timeline
**Features Added:** Tracking timeline view (`track.blade.php`) and real-time client-side Firebase status event listeners.

### Design Review (DR) Report - Week 6 (Real-Time Tracker)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Tracking timeline does not handle connection failure statuses, leaving the user with a frozen timeline. | A16 |
| 2. | Exposure of administrative details: The tracker exposes full Firebase document properties instead of just public states. | A17 |
| 3. | Hard-coded tracking URLs require the customer to sign in to see their order history, frustrating guest checkouts. | A18 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A16.** | Add Firebase offline listeners (`.info/connected`) to display a "Reconnecting..." badge when internet is spotty. | | | | |
| **A17.** | Sanitize the Firebase output; only expose the `status`, `table_number`, and `items` structures to the front-end script. | | | | |
| **A18.** | Securely encode order tracking URLs with encrypted reference tokens allowing guest tracking without logins. | | | | |

---

## 7. Week 7: Live Staff Orders Kitchen Queue Dashboard
**Features Added:** Kitchen queue view, order details filtering, status toggle actions, and automated sound alert notifications on new orders.

### Design Review (DR) Report - Week 7 (Staff Dashboard)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Kitchen queue does not show order preparation time elapsed, making it hard to prioritize late orders. | A19 |
| 2. | Sounds fail to play because modern web browsers block un-triggered audio files. | A20 |
| 3. | Layout scaling: Product detail notes (e.g., "no onions") wrap poorly on small staff tablets. | A21 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A19.** | Inject an elapsed timer counter that calculates seconds passed since the order's `created_at` timestamp. | | | | |
| **A20.** | Prompt for a "Start Session" interaction on dashboard mount to unlock audio permissions. | | | | |
| **A21.** | Apply grid formatting and distinct background colors (e.g., light red alerts) for custom order remarks. | | | | |

---

## 8. Week 8: Staff Cashier Payment & Loyalty Points Hook Integration
**Features Added:** Cashier checkout view, mark-as-paid buttons, and customer loyalty points increment operations in Firebase NoSQL.

### Design Review (DR) Report - Week 8 (Cashier & Points)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Points calculations are not validated on the server side, creating risk of point-tampering via post parameters. | A22 |
| 2. | Non-atomic writes: Deducting points and logging histories are split, causing sync gaps on network errors. | A23 |
| 3. | Points are awarded on fractional calculations instead of rounding down, complicating the catalog redemption totals. | A24 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A22.** | Re-evaluate points totals entirely on backend controllers using server-side variables before writing to Firebase. | | | | |
| **A23.** | Encapsulate points allocations and history ledgers inside atomic Firebase database transaction blocks. | | | | |
| **A24.** | Standardize loyalty points to absolute values by wrapping variables in `floor($orderTotal)`. | | | | |

---

## 9. Week 9: Thermal Receipt Compilation and PDF Generation Engine
**Features Added:** `laravel-dompdf` service integration, thermal printing CSS schemas, and dynamic receipt compilation endpoints.

### Design Review (DR) Report - Week 9 (Receipt PDF)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Standard paper width limits: PDFs are compiled in standard A4 sizes, failing to scale on typical 80mm POS printers. | A25 |
| 2. | Long product names overflow and overlap with prices, making the receipt hard to read. | A26 |
| 3. | Generation latency: Generating PDFs synchronously stalls the cashier controller response. | A27 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A25.** | Define customized CSS `@page` variables inside HTML templates to lock page layout width to `226pt` (80mm). | | | | |
| **A26.** | Implement ellipsis wrapping or table-column word-break rules in CSS for long item names. | | | | |
| **A27.** | Prefetch order item arrays and compile templates on-demand in the browser window using native print stylesheets. | | | | |

---

## 10. Week 10: Loyalty Rewards Catalog and Voucher Redemption Engine
**Features Added:** Rewards index menu, dynamic redemption voucher generation, and NoSQL points-expiring tracking hooks.

### Design Review (DR) Report - Week 10 (Rewards Catalog)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Customers can double-redeem vouchers by double-clicking the button before their points balance updates. | A28 |
| 2. | Point expiration logic runs on page requests, creating loading delays during database read/write checks. | A29 |
| 3. | Lack of warning: Customers receive no prior alerts when their points balances are close to expiring. | A30 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A28.** | Implement client-side click-disabling and backend token validation to block concurrent redemption. | | | | |
| **A29.** | Migrate points expiration checks to a background Artisan command `bossku:expire-points` run via scheduler daily. | | | | |
| **A30.** | Add a visual banner on the customer profile page alert notifying users of points expiring within 30 days. | | | | |

---

## 11. Week 11: Admin Performance Analytics Engine & ApexCharts Visualizer
**Features Added:** Interactive dashboards, daily revenue graphs via ApexCharts, category-share charts, and sales export scripts.

### Design Review (DR) Report - Week 11 (Analytics & Exports)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Processing massive JSON logs in-memory crashes the Admin dashboard once historical counts scale. | A31 |
| 2. | The sales export script includes customer emails, presenting an data privacy compliance risk. | A32 |
| 3. | Graphs render incorrectly on mobile devices because chart viewport scaling parameters are fixed. | A33 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A31.** | Create analytical pre-aggregate models in MySQL to compile historical daily revenues in the background. | | | | |
| **A32.** | Filter data exports; remove sensitive email fields and preserve only transactional metadata. | | | | |
| **A33.** | Configure dynamic sizing properties (`responsive: true`) inside ApexCharts initialization scripts. | | | | |

---

## 12. Week 12: Customer Feedback Rating & Admin Moderation Panel
**Features Added:** Product review forms, dynamic comment lists, admin moderation dashboards, and Firebase deleting operations.

### Design Review (DR) Report - Week 12 (Feedback & Deletions)
#### Summary of Discussion
| No | Discussion | Number of Action Item |
| :--- | :--- | :---: |
| 1. | Spam submission: Customers can spam product review routes since IP throttling is too generous. | A34 |
| 2. | Deleted reviews remain visible in client browser caches because page contents are cached locally. | A35 |
| 3. | Lack of text filters allows offensive comments to appear live instantly without basic word censoring. | A36 |

#### The Action Items
| No | Action Items to be performed | Responsible Employee (Due Date) | Completion Date | Approval: Date | Approval: Signature |
| :--- | :--- | :---: | :---: | :---: | :---: |
| **A34.** | Restrict submissions to `throttle:3,1` (maximum of 3 comments per minute) on review post APIs. | | | | |
| **A35.** | Trigger live state updates using dynamic database subscriptions instead of reading static pages. | | | | |
| **A36.** | Integrate a basic profanity filtering array to censor blacklisted keywords with asterisks. | | | | |
