#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "Aucun nom de remote fourni. Utilisation de 'origin' par défaut."
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

if [ -z "$REMOTE_URL" ]; then
  echo "⚠️  Remote '$REMOTE_NAME' introuvable. Fallback sur .gitignore.dev"
  cp .gitignore.dev .gitignore
  exit 0
fi

if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  echo "🎯 Remote actif : GitLab ($REMOTE_NAME) ➜ .gitignore.prod"
  cp .gitignore.prod .gitignore
elif echo "$REMOTE_URL" | grep -qi "github"; then
  echo "💻 Remote actif : GitHub ($REMOTE_NAME) ➜ .gitignore.dev"
  cp .gitignore.dev .gitignore
else
  echo "🤷 Remote '$REMOTE_NAME' non reconnu ➜ .gitignore.dev"
  cp .gitignore.dev .gitignore
fi

exit 0
