# Feature Test Assignment

## 📌 Project Overview

This project is a feature test assignment focused on building a structured CRUD management system with hierarchical data relationships:

Supplier → CLT Layups → CLT Layers

The system supports:
- CRUD operations
- Nested data management
- Import & Export functionality
- Conflict Resolution during import
- Dashboard-style interface based on provided Figma reference

The application was developed using:
- Laravel
- MySQL
- Tailwind CSS
- Blade Template Engine

---

# 👨‍💻 Team Members

## 1. Rafie Tegar Virgananda Setia Putra
### Role:
- Backend Developer
- Project Manager

### Responsibilities:
- System architecture planning
- Database design & migration
- CRUD API & business logic
- Import / Export implementation
- Conflict resolution system
- Service & Repository pattern
- Testing & backend optimization
- Project coordination

---

## 2. Yoga
### Role:
- Frontend Developer
- Deployment Engineer

### Responsibilities:
- UI implementation based on Figma
- Frontend layout & responsiveness
- Dashboard visualization
- User experience improvements
- Deployment & hosting configuration

---

# 🚀 Features

## ✅ CRUD Suppliers
- Create supplier
- Update supplier
- Delete supplier
- View supplier detail

---

## ✅ CRUD CLT Layups
Nested under Supplier

Features:
- Add layup
- Edit layup
- Delete layup
- View layup detail

---

## ✅ CRUD CLT Layers
Nested under Layup

Features:
- Add layer
- Edit layer
- Delete layer
- Layer ordering support

---

# 📥 Import / Export

## Export by Supplier
Export includes:
- Supplier
- Layups
- Layers

Format:
- JSON

---

## Import by Supplier

Supports:
- Create new records
- Update existing records
- Conflict detection

---

# ⚠️ Conflict Resolution

## Layup Conflict
If layup name already exists under same supplier:
- treated as same layup candidate

---

## Layer Conflict
If:
- same `layer_order`
- but different values (`thickness`, `width`, `angle`)

→ marked as conflict

---

## Conflict Strategy
Implemented:
- Overwrite Existing

---

## Bonus Feature
Manual conflict resolution UI inspired by GitHub merge conflict workflow:
- Existing vs Incoming comparison
- Highlight differences
- Manual selection
- Step-by-step conflict navigation

---

# 🎨 Design Reference

Figma:
https://www.figma.com/design/odWJ887r00aslmSFPIHMCx/SPEC-Toolbox---Feature-Test

---

# 🛠️ Tech Stack

## Backend
- Laravel
- PHP 8+
- MySQL

## Frontend
- Blade
- Tailwind CSS
- JavaScript

---

# 📂 Installation

## Clone Repository

```bash
git clone <repository-url>
