#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "Aucun nom de remote fourni. Utilisation de 'origin' par défaut."
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

# Fonction pour copier un .gitignore si le fichier source existe
copy_gitignore_file() {
  local SOURCE_FILE="$1"

  if [ -f "$SOURCE_FILE" ]; then
    echo "✅ Copie de $SOURCE_FILE ➜ .gitignore"
    cp "$SOURCE_FILE" .gitignore
  else
    echo "❌ Fichier $SOURCE_FILE introuvable. Aucune copie effectuée."
  fi
}

if [ -z "$REMOTE_URL" ]; then
  echo "⚠️  Remote '$REMOTE_NAME' introuvable. Fallback sur .gitignore.dev"
  copy_gitignore_file ".gitignore.dev"
  exit 0
fi

if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  echo "🎯 Remote actif : GitLab ($REMOTE_NAME) ➜ .gitignore.prod"
  copy_gitignore_file ".gitignore.prod"
elif echo "$REMOTE_URL" | grep -qi "github"; then
  echo "💻 Remote actif : GitHub ($REMOTE_NAME) ➜ .gitignore.dev"
  copy_gitignore_file ".gitignore.dev"
else
  echo "🤷 Remote '$REMOTE_NAME' non reconnu ➜ .gitignore.dev"
  copy_gitignore_file ".gitignore.dev"
fi

exit 0
