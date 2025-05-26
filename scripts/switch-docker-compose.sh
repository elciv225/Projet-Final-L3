#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "Aucun nom de remote fourni. Utilisation de 'origin' par défaut."
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

if [ -z "$REMOTE_URL" ]; then
  echo "⚠️  Remote '$REMOTE_NAME' introuvable. Fallback sur docker-compose.dev.yml"
  cp docker-compose.dev.yml docker-compose.yml
  exit 0
fi

if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  echo "🐳 Remote actif : GitLab ➜ docker-compose.prod.yml"
  cp docker-compose.prod.yml docker-compose.yml
elif echo "$REMOTE_URL" | grep -qi "github"; then
  echo "🐳 Remote actif : GitHub ➜ docker-compose.dev.yml"
  cp docker-compose.dev.yml docker-compose.yml
else
  echo "🐳 Remote '$REMOTE_NAME' inconnu ➜ docker-compose.dev.yml"
  cp docker-compose.dev.yml docker-compose.yml
fi

exit 0
