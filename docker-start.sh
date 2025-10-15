#!/bin/bash
docker compose up -d
sleep 5
docker compose exec app nginx
echo "✅ Application running at http://localhost:8000"
