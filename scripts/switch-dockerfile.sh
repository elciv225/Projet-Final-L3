#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "Aucun nom de remote fourni. Utilisation de 'origin' par défaut."
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

# Fonction pour copier un Dockerfile si le fichier source existe
copy_dockerfile() {
  local SOURCE_FILE="$1"

  if [ -f "$SOURCE_FILE" ]; then
    echo "✅ Copie de $SOURCE_FILE ➜ Dockerfile"
    cp "$SOURCE_FILE" Dockerfile
  else
    echo "❌ Fichier $SOURCE_FILE introuvable. Aucune copie effectuée."
  fi
}

if [ -z "$REMOTE_URL" ]; then
  echo "⚠️  Remote '$REMOTE_NAME' introuvable. Fallback sur Dockerfile.dev"
  copy_dockerfile "Dockerfile.dev"
  exit 0
fi

if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  echo "🧱 Remote actif : GitLab ➜ Dockerfile.prod"
  copy_dockerfile "Dockerfile.prod"
elif echo "$REMOTE_URL" | grep -qi "github"; then
  echo "🧱 Remote actif : GitHub ➜ Dockerfile.dev"
  copy_dockerfile "Dockerfile.dev"
else
  echo "🧱 Remote '$REMOTE_NAME' non reconnu ➜ Dockerfile.dev"
  copy_dockerfile "Dockerfile.dev"
fi

exit 0
