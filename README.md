# Hatsuhi - Personal Finance App

![Hatsuhi Logo](public/assets/Hatsuhi.png)

**Hatsuhi** (初日) - *"First sunrise of the year"* - is a self-hosted personal finance application designed for mindful financial awareness. In a world of fast-paced spending and financial automation, Hatsuhi encourages you to pause, reflect, and regain control through intentional financial practice.

## What is Hatsuhi?

Hatsuhi is not just another finance tracker. It's a tool for developing **financial mindfulness** through regular reflection on your spending patterns. Unlike cloud-based services, Hatsuhi runs on your own server, keeping your financial data completely private and under your control.

### Key Features

- **Self-Hosted & Private**: Your data stays on your server. No third-party access, no data selling
- **Mindful Financial Practice**: Weekly reflection, monthly analysis, yearly reset cycles
- **Complete Dashboard**: Income vs expenses, budget tracking, category breakdowns
- **Yearly Expectations**: Set and track annual budgets with monthly breakdowns
- **Custom Categories**: Flexible category system for income, expenses, extras, and unexpected costs
- **No AI, No Integrations**: You maintain complete analytical control
- **Built with Modern Tech**: Laravel + Filament v4 for robust backend with elegant admin interface

## The Philosophy

In today's fast-paced world, we've lost touch with our finances. Hatsuhi helps you develop:

- **Meditation**: Weekly reflection on spending patterns creates mindfulness around money
- **Projection**: Set and track financial goals with yearly expectations
- **Detection**: Identify spending patterns, leaks, and optimization opportunities
- **Own Saving Goals**: Personal targets based on your actual income and lifestyle

The name "Hatsuhi" (初日) refers to the first morning of the year - a time for new beginnings and fresh intentions, just as each new year brings new financial expectations.

## Technology Stack

- **Backend**: Laravel 11.x
- **Admin Panel**: Filament v4
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Breeze

## Prerequisites

Before installation, ensure you have:

- PHP 8.2 or higher
- Composer 2.5 or higher
- Node.js 18 or higher with npm
- Database server (MySQL 8.0+, PostgreSQL 15+, or SQLite 3.35+)
- Web server (Apache, Nginx, or built-in PHP server)

## Configuration

### Admin Access

After a fresh install seeding, you'll have two default users:

1. **Admin User**
   - Email: `admin@hatsuhi.app`
   - Password: `password`

2. **Regular User**
   - Email: `user@hatsuhi.app`
   - Password: `password`

**Important**: Change these passwords immediately after first login!

### Currency Configuration

Hatsuhi comes pre-seeded with multiple currencies. To add more:

```bash
php artisan tinker
>>> \App\Models\Currency::create(['name' => 'Swedish Krona', 'short' => 'SEK', 'symbol' => 'kr'])
```

## Initial Setup

After installation:

1. **Log in** with admin credentials
2. **Configure your profile**:
   - Set your preferred currency
   - Update timezone if needed
3. **Set up Expectations** for the current year
4. **Create custom categories** that match your spending habits
5. **Start recording movements** (transactions)

## Weekly Workflow

The recommended Hatsuhi workflow:

1. **Record Movements** - Add transactions throughout the week
2. **Sunday Evening Check-in** - Spend 15-20 minutes reviewing:
   - Dashboard overview
   - Budget vs actual spending
   - Category breakdowns
3. **Monthly Reflection** - Compare expectations vs reality
4. **Yearly Reset** - Set new expectations each new year

## Contributing

All contributions are welcome!

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit changes: `git commit -m 'Add amazing feature'`
4. Push to branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## License

Hatsuhi is open-source software licensed under the [MIT license](LICENSE).

## Acknowledgments

- **Laravel** - The PHP framework that powers Hatsuhi
- **Filament** - For the elegant admin interface
- **Chiyo-ni** - Whose haiku inspired the name and philosophy
- **All Contributors** - Who help make Hatsuhi better

---

*"it's play for the cranes flying up to the clouds, the year's first sunrise"* - Chiyo-ni (1703-1775)

May your financial journey find harmony with your intentions at each new beginning. 