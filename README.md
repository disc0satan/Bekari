# 🥐 Bekari – Bakery Management System

## 📌 Introduction
The **Bakery Management System (BMS)** is a robust relational database–driven solution designed to streamline internal bakery operations with a strong focus on **product freshness**, inventory accuracy, and sales integrity.

Unlike a standard retail website, this system centralizes "back-of-house" processes—from recipe formulation and batch production tracking to multi-branch inventory transfers and customer sales management.

### Key System Logic:
* ❌ **Automatic Expiry Filtering:** Expired products are automatically excluded from sales.
* 📦 **Dynamic Sync:** Inventory updates in real-time across all branches.
* 📊 **Traceability:** Production and sales data remain accurate and auditable.

---

## 🚀 Core Features

### 🛒 Sales & Customer Relationship Management
* **Dynamic Ordering:** Supports multi-item orders while validating branch-specific availability and filtering out expired stock.
* **Smart Discounting:** Applies automatic discounts to products nearing expiration to reduce waste.
* **Loyalty & CRM:** Tracks customer purchase history and calculates loyalty points; prevents duplicate profiles via phone validation.
* **Professional Invoicing:** Generates printable, searchable invoice records with a clean layout.

### 🍞 Production & Inventory Logistics
* **Batch Tracking:** Logs every production batch with timestamps and automated expiration monitoring.
* **Recipe & Waste Management:** Centralized database for ingredient costs, recipes, and production waste analysis.
* **Branch Transfers:** Move specific batches between locations with role-based notifications and accurate stock updates.

### 🔔 System Notifications
* **Real-time Alerts:** Low stock, expiring products, and incoming batch transfer warnings.
* **Role-Specific Filtering:** Users only see alerts relevant to their specific permissions and branch.

### 🔐 Administration & Business Intelligence
* **Role-Based Access Control (RBAC):** Distinct permissions for Admin, Branch Manager, Hub Worker, and Staff.
* **Advanced Analytics:** Monthly sales reports, production summaries, and top-selling product analysis.

---

## 🎨 UI/UX Design
The system is built for **high-efficiency environments**. The interface prioritizes speed and clarity through:
* Clean, minimal dashboard layouts tailored to specific user roles.
* Reduced-click workflows for complex actions like batch transfers.
* Clear visual hierarchy for stock levels and freshness indicators.

---

## 🛠 Tech Stack
* **Backend:** PHP
* **Database:** MySQL (Relational)
* **Local Server:** XAMPP / Apache
* **Frontend:** HTML, CSS, JavaScript

---

## ⚙ Setup Instructions

### 1. Database Setup
1. Open **phpMyAdmin** in XAMPP.
2. Create a new database (e.g., `bekari`).
3. **Import** the provided `.sql` file found in the project folder.

### 2. Configuration
Open your database connection file (e.g., `db_connect.php`) and update your credentials:
```php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bekari";

$conn = mysqli_connect($servername, $username, $password, $dbname);
?>
