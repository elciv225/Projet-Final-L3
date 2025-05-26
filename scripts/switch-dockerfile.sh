#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "Aucun nom de remote fourni. Utilisation de 'origin' par défaut."
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

if [ -z "$REMOTE_URL" ]; then
  echo "⚠️  Remote '$REMOTE_NAME' introuvable. Fallback sur Dockerfile.dev"
  cp Dockerfile.dev Dockerfile
  exit 0
fi

if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  echo "🧱 Remote actif : GitLab ➜ Dockerfile.prod"
  cp Dockerfile.prod Dockerfile
elif echo "$REMOTE_URL" | grep -qi "github"; then
  echo "🧱 Remote actif : GitHub ➜ Dockerfile.dev"
  cp Dockerfile.dev Dockerfile
else
  echo "🧱 Remote '$REMOTE_NAME' non reconnu ➜ Dockerfile.dev"
  cp Dockerfile.dev Dockerfile
fi

exit 0
