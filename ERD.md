# ERD — مخطط العلاقات بين الجداول

```mermaid
erDiagram
    clients {
        int id PK
        string name
        string phone
        string email
        timestamp created_at
    }

    cars {
        int id PK
        int client_id FK
        string plate_number
        string brand
        string model
        string color
    }

    services {
        int id PK
        string name
        decimal price
        int duration_minutes
        string description
    }

    employees {
        int id PK
        string name
        string phone
        string role
        timestamp created_at
    }

    bookings {
        int id PK
        int client_id FK
        int car_id FK
        int service_id FK
        int employee_id FK
        string status
        timestamp scheduled_at
        string notes
    }

    payments {
        int id PK
        int booking_id FK
        decimal amount
        string method
        timestamp paid_at
    }

    clients ||--o{ cars        : "يملك"
    clients ||--o{ bookings    : "يحجز"
    cars    ||--o{ bookings    : "تُغسل في"
    services||--o{ bookings    : "تُطلب في"
    employees||--o{ bookings   : "ينفذ"
    bookings ||--|| payments   : "تُسدَّد بـ"
```

## شرح العلاقات

| العلاقة | النوع | الوصف |
|---------|-------|--------|
| clients → cars | 1 : N | كل عميل ممكن يكون عنده أكتر من سيارة |
| clients → bookings | 1 : N | كل عميل ممكن يعمل أكتر من حجز |
| cars → bookings | 1 : N | نفس السيارة ممكن تتغسل أكتر من مرة |
| services → bookings | 1 : N | نفس الخدمة ممكن تتطلب في حجوزات كتير |
| employees → bookings | 1 : N | كل موظف ممكن ينفذ أكتر من حجز |
| bookings → payments | 1 : 1 | كل حجز ليه دفعة واحدة |

## حالات الحجز (status)

| القيمة | المعنى |
|--------|--------|
| `pending` | تم الحجز، لم يبدأ بعد |
| `in_progress` | السيارة داخل المغسلة دلوقتي |
| `completed` | انتهت الخدمة |
| `cancelled` | تم الإلغاء |
