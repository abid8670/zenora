# Zenora — IT Operations & Asset Management Platform

**Zenora is the single source of truth for an entire group of companies' IT estate.**

It tracks every asset from purchase to disposal, every credential, every IP address and subnet,
every Wi-Fi SSID, every point-to-point link and every server — organised by office and site, with
expiry alerts so nothing lapses unnoticed.

Built with **Laravel 12 + Filament 3.3**.

---

## The problem it solves

Spreadsheets are fine until you have more than one office. Then you cannot answer basic questions:

- Which laptop is assigned to whom, and since when?
- Which domains or hosting accounts expire this week?
- Which subnet and gateway belong to the Lahore office?
- What is the Wi-Fi password for Site 3, and who has changed it?
- Which server is running which service, and where is it physically?

Zenora answers all of these from one dashboard, and keeps a history of every change.

---

## Feature overview

### 🏢 Organisation structure
| Model | Purpose |
|-------|---------|
| `Office` | Physical offices, the top-level scope for networks and assets |
| `Site` | Sub-locations inside an office |
| `City` | Geography for offices and sites |
| `Department` | Organisational units for employees |
| `Employee` | Staff records, links to assigned assets |

### 💻 Asset lifecycle management
| Model | Purpose |
|-------|---------|
| `Asset` | The asset register — hardware, with its full attribute set |
| `AssetCategory` | Categories used for filtering and reporting |
| `AssetAssignmentLog` | **Immutable history** of who held which asset, and when |
| `AssetRepair` | Repair/maintenance records against an asset, with cost and status |

Assets can be **In Stock** or **Assigned**, and the dashboard reports both counts live.

### 🌐 Network inventory
| Model | Purpose |
|-------|---------|
| `Subnet` | Subnet allocation, gateway and VLAN, scoped per office |
| `Isp` | Internet service providers with link details |
| `P2pLink` | Point-to-point links between offices/sites |
| `AccessPoint` | Wireless access points and their management credentials |
| `WifiSsid` | SSIDs, with their passwords held against each access point |
| `VoipExtension` | PBX extensions and their assignments |

### 🖥️ Infrastructure
| Model | Purpose |
|-------|---------|
| `Server` | Physical and virtual servers |
| `ServerType` | Server role/type taxonomy |
| `Hosting` | Hosting accounts, with provider and renewal tracking |
| `Domain` | Domain names, with registrar and expiry tracking |
| `Project` | Projects, with their owner and status |

### 🎫 Support
| Model | Purpose |
|-------|---------|
| `SupportTicket` | Tickets raised against people or infrastructure |
| `SupportType` | Ticket categories |

---

## Dashboard

The Filament dashboard is grouped into logical panels:

**Assets & Employees**
- Stats overview — Total Assets, Total Employees, In Stock, Assigned
- Filterable by office and asset category
- Asset category distribution chart

**Domain Management**
- Stats overview — Total, Active, Expiring Soon, Expired
- **Expiring Domains** table — anything expiring within 7 days, with a one-click **Renew** action
  (date picker) and a live "days to expire" column

**Hosting Management**
- Stats overview — Total, Active, Expiring Soon, Expired
- **Expiring Hostings** table — same renew workflow

**Project Management**
- Recent projects table

Widgets live in `app/Filament/Widgets/`:
`AssetEmployeeStatsOverview`, `AssetCategoryChart`, `DomainStatsOverview`,
`ExpiringDomains`, `ExpiringHostings`.

---

## Notable implementation details

- **Scoped unique validation.** `subnet_address` is unique *per office*, not globally — the same
  subnet can exist in two offices but never twice in one:
  `unique(ignoreRecord: true)->where('office_id', $officeId)`.
- **Password handling.** Credentials stored for employees, access points and repeaters are
  hashed on save via `dehydrateStateUsing()`, and every password field is `->revealable()` so
  operators can verify what they typed.
- **Renewal actions** use a `DatePicker` inside a Filament table action, recalculating the
  expiry from the new date rather than requiring a full edit form.

---

## Tech stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 12 (PHP 8.2+) |
| Admin panel | Filament 3.3 |
| Frontend | Blade + Vite |
| Database | MySQL |
| Packaging | Docker / docker-compose |
| Deployment | Vercel (PHP functions) |

---

## Data model

23 Eloquent models, 46 migrations, 21 Filament resources.

Seeders provide a usable starting state — admin user, asset categories, cities, departments,
offices, projects, server types and support types.

---

## Getting started

```bash
# 1. install dependencies
composer install
npm install

# 2. configure
cp .env.example .env
php artisan key:generate
# set DB_* in .env

# 3. migrate and seed
php artisan migrate --seed

# 4. build and serve
npm run build
php artisan serve
```

The Filament admin panel is then available at `/admin`.

### Docker

```bash
docker-compose up -d
```

---

## Repository layout

```
app/
  Filament/
    Resources/     # 21 resources: Assets, Employees, Domains, Subnets, SSIDs, ...
    Widgets/       # dashboard stats + expiry tables
  Models/          # 23 Eloquent models
database/
  migrations/      # 46 migrations
  seeders/         # baseline data
routes/
  web.php
  console.php
```

---

## Roadmap

- [ ] Role-based permissions (Filament Shield)
- [ ] Asset depreciation tracking
- [ ] QR/barcode labels for physical asset tagging
- [ ] Licence and warranty expiry alerts
- [ ] API for external monitoring systems to push link status
- [ ] PDF export of asset assignments for audits

---

## Screenshots

> Add screenshots of the dashboard, asset register and network inventory here.

---

## License

Proprietary — internal platform for the Blue Group of Companies. Not licensed for redistribution.
