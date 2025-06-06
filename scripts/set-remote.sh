#!/bin/bash

REMOTE_NAME="$1"

if [ -z "$REMOTE_NAME" ]; then
  echo "⚠️  Aucun remote spécifié ➜ utilisation de 'origin'"
  REMOTE_NAME="origin"
fi

REMOTE_URL=$(git remote get-url "$REMOTE_NAME" 2>/dev/null)

if [ -z "$REMOTE_URL" ]; then
  echo "❌ Remote '$REMOTE_NAME' introuvable."
  exit 1
fi

# Déduction du type de remote
if echo "$REMOTE_URL" | grep -qi "gitlab"; then
  TARGET="$REMOTE_NAME" # → "gitlab"
  echo "🎯 Remote GitLab ➜ configuration PROD"
elif echo "$REMOTE_URL" | grep -qi "github"; then
  TARGET="$REMOTE_NAME" # → "github"
  echo "💻 Remote GitHub ➜ configuration DEV"
else
  TARGET="$REMOTE_NAME"
  echo "🤷 Remote inconnu ➜ configuration DEV par défaut"
fi

# Appliquer les fichiers de configuration
echo "🔁 Application des configurations pour '$TARGET'..."
bash scripts/switch-ignore.sh "$TARGET"
bash scripts/switch-env.sh "$TARGET"
bash scripts/switch-docker-compose.sh "$TARGET"
bash scripts/switch-dockerfile.sh "$TARGET"

# Nettoyer le cache pour prise en compte du nouveau .gitignore
echo "🧹 Nettoyage du cache Git..."
git rm -r --cached . >/dev/null 2>&1

echo "✅ Configuration '$TARGET' appliquée avec succès."
