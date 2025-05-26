#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "Aucun nom de remote fourni. Utilisation de 'origin' par défaut."
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

if [ -z "$REMOTE_URL" ]; then
  echo "⚠️  Remote '$REMOTE_NAME' introuvable. Fallback sur .env.dev"
  cp .env.dev .env
  exit 0
fi

if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  echo "🔐 Remote actif : GitLab ➜ .env.prod"
  cp .env.prod .env
elif echo "$REMOTE_URL" | grep -qi "github"; then
  echo "🔐 Remote actif : GitHub ➜ .env.dev"
  cp .env.dev .env
else
  echo "🔐 Remote '$REMOTE_NAME' non reconnu ➜ .env.dev"
  cp .env.dev .env
fi

exit 0
