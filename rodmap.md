# 🚗 خطة مشروع مغسلة السيارات

> مشروع مشترك بين **أنت (فرونت اند)** و **يوسف (باك اند)**
> التنسيق عن طريق Git

---

## 📁 هيكل المشروع (Monorepo)

```
project/
├── artifacts/
│   ├── car-wash/          ← فرونت اند (أنت)
│   └── api-server/        ← باك اند (يوسف)
├── lib/
│   ├── api-spec/
│   │   └── openapi.yaml   ← عقد الـ API المشترك (الاثنين بيتفقوا عليه)
│   ├── api-client-react/  ← Hooks جاهزة (بتتولد تلقائيًا)
│   └── db/                ← قاعدة البيانات (يوسف)
```

---

## 🤝 طريقة التنسيق بين الاثنين

### الخطوة الأولى (قبل أي حاجة)
- **الاثنين بيتفقوا على ملف `lib/api-spec/openapi.yaml`**
- الملف ده بيحدد كل الـ API Endpoints (اسمها، شكل الطلب، شكل الرد)
- بعد ما تتفقوا عليه، المشروع بيولد Hooks للفرونت اند تلقائيًا بالأمر ده:
  ```bash
  pnpm --filter @workspace/api-spec run codegen
  ```

### Git Workflow
```
main branch
├── feature/frontend-dashboard     ← أنت
├── feature/frontend-bookings      ← أنت
├── feature/backend-auth           ← يوسف
├── feature/backend-bookings-api   ← يوسف
└── ...
```
- **كل فيتشر في branch منفصلة**
- Pull Request على `main` بعد ما كل واحد يخلص جزءه
- لو في تعديل على `openapi.yaml` → الاثنين لازم يعرفوا قبل الـ merge

---

## 👤 مهام أنت (فرونت اند - `artifacts/car-wash/`)

### الصفحات اللي هتبنيها

| الصفحة | الوصف | الـ API اللي محتاجها |
|--------|--------|----------------------|
| **Dashboard** | إحصائيات سريعة (حجوزات النهارده، الإيرادات، عدد السيارات) | `GET /api/stats` |
| **الحجوزات** | عرض كل الحجوزات + إضافة حجز جديد | `GET/POST /api/bookings` |
| **العملاء** | قائمة العملاء وبيانات سياراتهم | `GET/POST /api/clients` |
| **الخدمات** | الخدمات المتاحة وأسعارها | `GET /api/services` |
| **الموظفين** | إدارة الموظفين | `GET /api/employees` |
| **التقارير** | تقارير الإيرادات والأداء | `GET /api/reports` |

### الـ Components المشتركة
- Sidebar / Navbar
- جدول البيانات (DataTable)
- Modal الإضافة/التعديل
- بطاقة الإحصائية (StatsCard)
- Badge حالة الحجز

---

## 👤 مهام يوسف (باك اند - `artifacts/api-server/`)

### قاعدة البيانات (`lib/db/src/schema/`)

| الجدول | الحقول الأساسية |
|--------|----------------|
| `clients` | id, name, phone, email, created_at |
| `cars` | id, client_id, plate_number, brand, model, color |
| `services` | id, name, price, duration_minutes, description |
| `employees` | id, name, phone, role, created_at |
| `bookings` | id, client_id, car_id, service_id, employee_id, status, scheduled_at, notes |
| `payments` | id, booking_id, amount, method, paid_at |

### الـ API Endpoints

#### العملاء
```
GET    /api/clients          ← قائمة العملاء (مع بحث واختياري pagination)
POST   /api/clients          ← إضافة عميل جديد
GET    /api/clients/:id      ← تفاصيل عميل + سياراته
PUT    /api/clients/:id      ← تعديل بيانات العميل
DELETE /api/clients/:id      ← حذف عميل
```

#### السيارات
```
POST   /api/cars             ← إضافة سيارة لعميل
PUT    /api/cars/:id         ← تعديل بيانات السيارة
DELETE /api/cars/:id         ← حذف سيارة
```

#### الحجوزات
```
GET    /api/bookings         ← كل الحجوزات (مع filter بالتاريخ والحالة)
POST   /api/bookings         ← حجز جديد
GET    /api/bookings/:id     ← تفاصيل حجز
PUT    /api/bookings/:id     ← تعديل / تغيير الحالة
DELETE /api/bookings/:id     ← إلغاء حجز
```

#### حالات الحجز
```
pending   → تم الحجز، لم يبدأ
in_progress → السيارة داخل المغسلة
completed  → انتهى
cancelled  → ملغي
```

#### الخدمات
```
GET    /api/services         ← قائمة الخدمات
POST   /api/services         ← إضافة خدمة
PUT    /api/services/:id     ← تعديل خدمة
DELETE /api/services/:id     ← حذف خدمة
```

#### الموظفين
```
GET    /api/employees        ← قائمة الموظفين
POST   /api/employees        ← إضافة موظف
PUT    /api/employees/:id    ← تعديل
DELETE /api/employees/:id    ← حذف
```

#### الإحصائيات
```
GET    /api/stats            ← إحصائيات اليوم (حجوزات، إيرادات، إلخ)
GET    /api/reports?from=&to= ← تقارير بفترة زمنية
```

---

## 📋 مراحل التنفيذ

### المرحلة الأولى - الأساس (الاثنين بيعملوها مع بعض)
- [ ] الاتفاق على ملف `openapi.yaml` الكامل
- [ ] إنشاء الـ Branches
- [ ] يوسف: إعداد قاعدة البيانات والجداول
- [ ] أنت: إعداد هيكل الفرونت اند (Routing + Layout)

### المرحلة الثانية - الفيتشرز الأساسية
- [ ] يوسف: API الحجوزات والعملاء
- [ ] أنت: صفحة الحجوزات والداشبورد

### المرحلة الثالثة - الفيتشرز الإضافية
- [ ] يوسف: API الخدمات والموظفين والتقارير
- [ ] أنت: باقي الصفحات (الخدمات، الموظفين، التقارير)

### المرحلة الرابعة - التلميع والاختبار
- [ ] ربط الفرونت اند بالباك اند بالكامل
- [ ] اختبار كل الـ flows
- [ ] Deploy

---

## ⚡ أوامر مهمة

```bash
# بعد أي تعديل على openapi.yaml (الاثنين لازم يشغلوه)
pnpm --filter @workspace/api-spec run codegen

# تشغيل الباك اند (يوسف)
pnpm --filter @workspace/api-server run dev

# تشغيل الفرونت اند (أنت)
pnpm --filter @workspace/car-wash run dev

# push قاعدة البيانات (يوسف)
pnpm --filter @workspace/db run push
```

---

## 🔑 قاعدة ذهبية

> **أي تغيير في `openapi.yaml` = لازم تخبر يوسف (أو العكس) قبل الـ merge**
> لأن الملف ده هو الحلقة الوسطى بينكم الاثنين

---

*آخر تحديث: مارس 2026*
