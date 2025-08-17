
## Starting the program
in project root execute following command:

composer install

docker-compose up -d --build

# Users API — Curl Cheat Sheet
## Base URL
```
http://localhost:8080
```

---

## Endpoints

### 1. List All Users
```bash
curl -X GET http://localhost:8080/users
```

### 2. Get a Single User by ID
```bash
curl -X GET http://localhost:8080/users/{id}
```

### 3. Add a New User
```bash
curl -X POST http://localhost:8080/users \
-H "Content-Type: application/json" \
-d '{
    "name": "Dave",
    "email": "dave@example.com",
    "address": "101 Ocean Blvd"
}'
```

### 4. Update an Existing User
```bash
curl -X PUT http://localhost:8080/users/{id} \
-H "Content-Type: application/json" \
-d '{
    "name": "Alice Updated",
    "email": "alice.new@example.com",
    "address": "123 New Wonderland Ave"
}'
```

### 5. Delete a User
```bash
curl -X DELETE http://localhost:8080/users/{id}
```
