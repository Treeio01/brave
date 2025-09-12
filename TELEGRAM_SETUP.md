# Настройка Telegram уведомлений

## 🤖 Создание Telegram бота

1. **Создайте бота через @BotFather:**
   - Отправьте `/newbot` в чат с @BotFather
   - Выберите имя для бота (например: "Brave Notifications Bot")
   - Выберите username для бота (например: "brave_notifications_bot")
   - Сохраните полученный токен

2. **Получите Chat ID:**
   - Добавьте бота в группу или начните с ним личный чат
   - Отправьте любое сообщение боту
   - Перейдите по ссылке: `https://api.telegram.org/bot<YOUR_BOT_TOKEN>/getUpdates`
   - Найдите `chat.id` в ответе

## ⚙️ Настройка в Laravel

1. **Добавьте переменные в .env файл:**
```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

2. **Или используйте команду настройки:**
```bash
php artisan telegram:setup YOUR_BOT_TOKEN YOUR_CHAT_ID
```

3. **Протестируйте уведомления:**
```bash
php artisan telegram:test
```

## 📱 Типы уведомлений

### 🔍 Переходы на страницы
- **Конференции:** `/api/conferences/{id}/visit`
- **Страницы приглашений:** `/api/invite-pages/by-ref/{ref}/visit`

### 📞 Входы в звонки
- **Вход по коду:** `/api/conferences/join/{inviteCode}`
- **Вход с именем:** `/api/conferences/join/{inviteCode}` (POST)

### ⬇️ Скачки приложения
- **Конференции:** `/api/conferences/{id}/download`
- **Страницы приглашений:** `/api/invite-pages/by-ref/{ref}/download`
- **Общие уведомления:** `/api/notify/download`

## 📋 Формат уведомлений

### Переход на страницу
```
🟦 Переход на страницу
📱 Устройство: Windows
🌍 Гео: 105.113.28.2 - 🇳🇬 Nigeria
🔗 Код: TEST123
🗣 Юзер: N/A
```

### Вход в звонок
```
🟦 Вход в звонок
📱 Устройство: Windows
🌍 Гео: 105.113.28.2 - 🇳🇬 Nigeria
🔗 Код: TEST123
🗣 Юзер: N/A
```

### Скачка приложения
```
🟦 Скачивание
📱 Устройство: Windows
🌍 Гео: 105.113.28.2 - 🇳🇬 Nigeria
🔗 Код: NO_REF
🗣 Юзер: N/A
```

## 🔧 Настройка в коде

Все уведомления автоматически отправляются при вызове соответствующих API эндпоинтов. Никаких дополнительных настроек не требуется.

Если нужно отключить уведомления, просто не указывайте `TELEGRAM_BOT_TOKEN` в .env файле.
