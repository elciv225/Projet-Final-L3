#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "Aucun nom de remote fourni. Utilisation de 'origin' par défaut."
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

# Fonction de copie sécurisée
copy_compose_file() {
  local SOURCE_FILE="$1"
  if [ -f "$SOURCE_FILE" ]; then
    echo "✅ Copie de $SOURCE_FILE ➜ docker-compose.yml"
    cp "$SOURCE_FILE" docker-compose.yml
  else
    echo "❌ Fichier $SOURCE_FILE introuvable. Aucune copie effectuée."
  fi
}

if [ -z "$REMOTE_URL" ]; then
  echo "⚠️  Remote '$REMOTE_NAME' introuvable. Fallback sur docker-compose.dev.yml"
  copy_compose_file "docker-compose.dev.yml"
  exit 0
fi

if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  echo "🐳 Remote actif : GitLab ➜ docker-compose.prod.yml"
  copy_compose_file "docker-compose.prod.yml"
elif echo "$REMOTE_URL" | grep -qi "github"; then
  echo "🐳 Remote actif : GitHub ➜ docker-compose.dev.yml"
  copy_compose_file "docker-compose.dev.yml"
else
  echo "🐳 Remote '$REMOTE_NAME' inconnu ➜ docker-compose.dev.yml"
  copy_compose_file "docker-compose.dev.yml"
fi

exit 0
