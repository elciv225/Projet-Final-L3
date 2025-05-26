#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "Aucun nom de remote fourni. Utilisation de 'origin' par défaut."
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

# Fonction utilitaire pour copier un .env si existant
copy_env_file() {
  local SOURCE_FILE="$1"

  if [ -f "$SOURCE_FILE" ]; then
    echo "✅ Copie de $SOURCE_FILE ➜ .env"
    cp "$SOURCE_FILE" .env
  else
    echo "❌ Fichier $SOURCE_FILE introuvable. Aucune copie effectuée."
  fi
}

if [ -z "$REMOTE_URL" ]; then
  echo "⚠️  Remote '$REMOTE_NAME' introuvable. Fallback sur .env.dev"
  copy_env_file ".env.dev"
  exit 0
fi

if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  echo "🔐 Remote actif : GitLab ➜ .env.prod"
  copy_env_file ".env.prod"
elif echo "$REMOTE_URL" | grep -qi "github"; then
  echo "🔐 Remote actif : GitHub ➜ .env.dev"
  copy_env_file ".env.dev"
else
  echo "🔐 Remote '$REMOTE_NAME' non reconnu ➜ .env.dev"
  copy_env_file ".env.dev"
fi

exit 0
