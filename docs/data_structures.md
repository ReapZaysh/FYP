# Bossku House Data Structure Specifications

This document outlines the complete data structure of the **Bossku House** project. The application utilizes a hybrid database architecture:
1. **Local SQLite Database (Relational Data Model via Laravel Eloquent)**: Used for local session persistence, job queue management, local relational model structure, and system caching.
2. **Firebase Realtime Database (NoSQL JSON Data Model)**: Used for live-catalog synchronization, active order tracking, loyalty points updates, rewards, customer reviews, and real-time push notification tracking.
3. **Web Browser LocalStorage**: Used on the client-side to persist order references and tracking statuses for guest users across page navigation.

---

## 1. Local SQLite Database Schema

The following tables define the relational database structure managed via Laravel migrations.

### Table: `users`
Stores user profile credentials, identity, contact information, and roles.
* **Relations**: None directly via foreign key constraints, mapped via logical queries and synced with Firebase `/users`.

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INTEGER` | Primary Key, Auto-Increment | Unique identifier for each user |
| `name` | `VARCHAR` | Not Null | User's display or full name |
| `username` | `VARCHAR` | Unique, Not Null | Unique username for login identity |
| `email` | `VARCHAR` | Unique, Not Null | Email address used for authentication / notifications |
| `phone` | `VARCHAR` | Nullable | Contact phone number |
| `role` | `VARCHAR` | Default: `'customer'` | User's role inside the system (`admin`, `staff`, `customer`) |
| `email_verified_at`| `TIMESTAMP` | Nullable | Timestamp when email was verified |
| `password` | `VARCHAR` | Not Null | Hashed password |
| `remember_token` | `VARCHAR` | Nullable | Token used to keep session active |
| `created_at` | `TIMESTAMP` | Nullable | Records creation date |
| `updated_at` | `TIMESTAMP` | Nullable | Records modification date |

---

### Table: `categories`
Stores product categories used to organize the menu.
* **Relations**: Has many `products`.

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INTEGER` | Primary Key, Auto-Increment | Unique identifier for each category |
| `name` | `VARCHAR` | Not Null | Category display name |
| `slug` | `VARCHAR` | Unique, Not Null | URL-friendly slug generated from category name |
| `sort_order` | `INTEGER` | Default: `0` | Order index for rendering categories sequentially |
| `created_at` | `TIMESTAMP` | Nullable | Records creation date |
| `updated_at` | `TIMESTAMP` | Nullable | Records modification date |

---

### Table: `products`
Stores available items in the shop catalog.
* **Relations**: Belongs to `category` (cascade on delete).

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INTEGER` | Primary Key, Auto-Increment | Unique identifier for each product |
| `category_id` | `INTEGER` | Foreign Key (references `categories.id`), Cascade Delete | Category this product belongs to |
| `name` | `VARCHAR` | Not Null | Product name |
| `description` | `TEXT` | Nullable | Short description of the item |
| `price` | `DECIMAL(8,2)` | Not Null | Standard price per unit |
| `image_path` | `VARCHAR` | Nullable | File path/URL of the product image |
| `is_available` | `BOOLEAN` | Default: `true` | Indicates if the product is in-stock and purchasable |
| `is_featured` | `BOOLEAN` | Default: `false` | Indicates if the product should be featured on top of dashboard |
| `order_count` | `INTEGER` | Default: `0` | Analytics tracking how many times the product was ordered |
| `created_at` | `TIMESTAMP` | Nullable | Records creation date |
| `updated_at` | `TIMESTAMP` | Nullable | Records modification date |

---

### Table: `orders`
Stores order headers including pricing and overall processing status.
* **Relations**: Has many `order_items`.

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INTEGER` | Primary Key, Auto-Increment | Unique identifier for the order record |
| `order_reference` | `VARCHAR` | Unique, Not Null | Human-readable unique identifier (e.g. `ORD-XXXXX`) |
| `customer_name` | `VARCHAR` | Nullable | Name of the customer (allows guest orders to specify optionally) |
| `total_amount` | `DECIMAL(10,2)` | Not Null | Total calculated order price |
| `status` | `VARCHAR` | Default: `'submitted'` | Processing state (`submitted`, `in_progress`, `completed`, `canceled`) |
| `created_at` | `TIMESTAMP` | Nullable | Records creation date |
| `updated_at` | `TIMESTAMP` | Nullable | Records modification date |

---

### Table: `order_items`
Stores line-item details for each order.
* **Relations**: Belongs to `orders` (cascade on delete), belongs to `products` (cascade on delete).

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INTEGER` | Primary Key, Auto-Increment | Unique identifier for the item |
| `order_id` | `INTEGER` | Foreign Key (references `orders.id`), Cascade Delete | Link to the parent order |
| `product_id` | `INTEGER` | Foreign Key (references `products.id`), Cascade Delete | Link to the purchased product |
| `quantity` | `INTEGER` | Not Null | Quantity ordered |
| `price` | `DECIMAL(8,2)` | Not Null | Unit price of the item at the time of order placement |
| `note` | `VARCHAR` | Nullable | Custom request/instruction for the chef |
| `created_at` | `TIMESTAMP` | Nullable | Records creation date |
| `updated_at` | `TIMESTAMP` | Nullable | Records modification date |

---

### Table: `sessions`
Laravel database session driver table.

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `VARCHAR` | Primary Key | Unique session identifier |
| `user_id` | `INTEGER` | Index, Nullable | Associated user ID if logged in |
| `ip_address` | `VARCHAR(45)` | Nullable | Client's IP address |
| `user_agent` | `TEXT` | Nullable | Client's browser user agent string |
| `payload` | `LONGTEXT` | Not Null | Serialized session data |
| `last_activity` | `INTEGER` | Index, Not Null | Unix timestamp of last user action |

---

### Table: `cache` & `cache_locks`
Laravel database cache and rate limiter driver tables.

**cache:**
| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `key` | `VARCHAR` | Primary Key | Unique cache key identifier |
| `value` | `MEDIUMTEXT` | Not Null | Serialized cached value |
| `expiration` | `INTEGER` | Not Null | Unix timestamp of when cache item expires |

**cache_locks:**
| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `key` | `VARCHAR` | Primary Key | Lock key name |
| `owner` | `VARCHAR` | Not Null | Owner identifier of the acquired lock |
| `expiration` | `INTEGER` | Not Null | Unix timestamp of when the lock will auto-release |

---

### Table: `jobs`, `job_batches` & `failed_jobs`
Laravel background processing and queue worker schema.

**jobs:**
| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INTEGER` | Primary Key, Auto-Increment | Unique identifier for job |
| `queue` | `VARCHAR` | Index, Not Null | Target queue channel |
| `payload` | `LONGTEXT` | Not Null | Serialized job payload details (class, parameters) |
| `attempts` | `TINYINT` | Not Null | Number of execution retries attempted |
| `reserved_at` | `INTEGER` | Nullable | Unix timestamp when a worker reserved the job |
| `available_at` | `INTEGER` | Not Null | Unix timestamp when job becomes ready for dispatch |
| `created_at` | `INTEGER` | Not Null | Unix timestamp when job was added |

**job_batches:**
| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `VARCHAR` | Primary Key | Unique batch ID |
| `name` | `VARCHAR` | Not Null | Batch description name |
| `total_jobs` | `INTEGER` | Not Null | Total jobs inside the batch |
| `pending_jobs` | `INTEGER` | Not Null | Remaining jobs awaiting execution |
| `failed_jobs` | `INTEGER` | Not Null | Number of failed jobs in the batch |
| `failed_job_ids` | `LONGTEXT` | Not Null | JSON array of failed job IDs |
| `options` | `MEDIUMTEXT` | Nullable | Serialized options/callbacks |
| `cancelled_at` | `INTEGER` | Nullable | Unix timestamp if batch was cancelled |
| `created_at` | `INTEGER` | Not Null | Unix timestamp of creation |
| `finished_at` | `INTEGER` | Nullable | Unix timestamp of completion |

**failed_jobs:**
| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | `INTEGER` | Primary Key, Auto-Increment | Unique identifier for error record |
| `uuid` | `VARCHAR` | Unique, Not Null | Universally Unique ID of the job |
| `connection` | `TEXT` | Not Null | Connection channel |
| `queue` | `TEXT` | Not Null | Target queue name |
| `payload` | `LONGTEXT` | Not Null | Serialized payload that failed |
| `exception` | `LONGTEXT` | Not Null | Full exception backtrace string |
| `failed_at` | `TIMESTAMP` | Default: Current Time | Records failure timestamp |

---

### Table: `password_reset_tokens`
Standard token repository for resetting credentials.

| Column Name | Data Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `email` | `VARCHAR` | Primary Key | Target email address |
| `token` | `VARCHAR` | Not Null | Generated reset verification token |
| `created_at` | `TIMESTAMP` | Nullable | Expiration timestamp base |

---

## 2. Firebase Realtime Database JSON Schema

The NoSQL JSON schema stores dynamic data accessed in real-time by staff dashboards and customer interfaces.

```json
{
  "users": {
    "USER_UUID_OR_ID": {
      "name": "Syahiran Muhd",
      "username": "syahiran",
      "email": "syahiranmuhd123@gmail.com",
      "phone": "0123456789",
      "role": "customer",
      "loyalty_points": 120
    }
  },
  "points_history": {
    "USER_UUID_OR_ID": {
      "PUSH_KEY_ID": {
        "id": "PUSH_KEY_ID",
        "points": 10,
        "order_reference": "ORD-5Z8Q3",
        "status": "active",
        "created_at": "2026-06-08T19:30:00+08:00",
        "expires_at": "2027-06-08T19:30:00+08:00"
      }
    }
  },
  "categories": {
    "CATEGORY_UUID_OR_ID": {
      "name": "Beverages",
      "slug": "beverages",
      "sort_order": 1,
      "created_at": "2026-06-08T19:30:00+08:00",
      "updated_at": "2026-06-08T19:30:00+08:00"
    }
  },
  "products": {
    "PRODUCT_UUID_OR_ID": {
      "category_id": "CATEGORY_UUID_OR_ID",
      "name": "Fresh Orange Juice",
      "description": "Sweet squeezed fresh juice.",
      "price": 6.50,
      "image_path": "https://firebasestorage.googleapis.com/v0/b/.../products/...webp?alt=media",
      "is_available": true,
      "is_featured": true,
      "order_count": 42,
      "created_at": "2026-06-08T19:30:00+08:00",
      "updated_at": "2026-06-08T19:30:00+08:00"
    }
  },
  "rewards": {
    "REWARD_UUID_OR_ID": {
      "name": "Free Orange Juice Voucher",
      "description": "Redeem a free signature fresh orange juice.",
      "points_required": 50,
      "image_url": "https://firebasestorage.googleapis.com/v0/b/.../rewards/...jpg?alt=media"
    }
  },
  "vouchers": {
    "USER_UUID_OR_ID": {
      "VOUCHER_UUID_OR_ID": {
        "code": "VOUCH-FREE-ORANGE",
        "discount_amount": 6.50,
        "is_used": false,
        "created_at": "2026-06-08T19:30:00+08:00",
        "expires_at": "2026-09-08T19:30:00+08:00"
      }
    }
  },
  "orders": {
    "ORD-5Z8Q3": {
      "reference": "ORD-5Z8Q3",
      "customer_name": "Guest Customer",
      "customer_id": null,
      "total_amount": 13.00,
      "status": "in_progress",
      "payment_status": "unpaid",
      "paid_at": null,
      "created_at": "2026-06-08T19:30:00+08:00",
      "updated_at": "2026-06-08T19:35:00+08:00",
      "items": [
        {
          "product_id": "PRODUCT_UUID_OR_ID",
          "product_name": "Fresh Orange Juice",
          "quantity": 2,
          "paid_quantity": 0,
          "price": 6.50,
          "note": "Less ice"
        }
      ]
    }
  },
  "reviews": {
    "PRODUCT_UUID_OR_ID": {
      "REV-X7A9D": {
        "code": "REV-X7A9D",
        "name": "Ali",
        "rating": 5,
        "comment": "Very delicious and refreshing!",
        "created_at": "2026-06-08T19:30:00+08:00"
      }
    }
  }
}
```

---

## 3. Client-Side Browser Storage Schema

To allow guest checkout users to track their orders and support interactive badge notifications without backend user accounts, state variables are stored in browser **LocalStorage**.

### Key: `bossku_tracked_orders`
Stores an array of placed orders on the client's current browser.

```json
[
  {
    "reference": "ORD-5Z8Q3",
    "status": "in_progress",
    "total_amount": 13.00,
    "timestamp": 1780927800
  }
]
```

### Key: `bossku_alerted_orders`
Used to prevent duplicate ring/vibration triggers on the client browser once a completed order has already been announced to the customer.

```json
[
  "ORD-5Z8Q3"
]
```

---

## 4. Data Flow & Synchronization

1. **System Catalog Setup**: Admin updates products/categories in SQLite (through custom forms). The backend controller synchronizes mutations dynamically to the Firebase database paths `/categories` and `/products` using the `FirebaseService`.
2. **Order Placement**:
   - The user selects items and places an order from the front-end cart.
   - The order is logged inside the SQLite tables `orders` and `order_items` for relational auditing.
   - Simultaneously, the order is registered under `/orders/ORD-XXXXX` inside Firebase.
   - If the customer is not logged in (guest), the order reference is stored inside the client's `localStorage.bossku_tracked_orders`.
3. **Real-time Order Status & Notification Tracker**:
   - The client navbar uses Alpine.js and registers a global Firebase database listener referencing the active references in `localStorage`.
   - When a chef/staff modifies an order status to `completed` in Firebase `/orders/ORD-XXXXX/status`, the client listener intercepts this change.
   - The client executes a ringtone chime and custom vibration patterns, updating local UI badges to signify completion.
4. **Loyalty System**:
   - Upon order completion and payment confirmation (`payment_status` updated to `'paid'`), the backend reads the associated `customer_id`.
   - Points are calculated (e.g. 1 point per RM1.00 spent) and pushed to `/users/{userId}/loyalty_points`.
   - A credit transaction logs under `/points_history/{userId}`.
