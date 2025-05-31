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
  TARGET="prod"
  echo "🎯 Remote GitLab ➜ configuration PROD"
elif echo "$REMOTE_URL" | grep -qi "github"; then
  TARGET="dev"
  echo "💻 Remote GitHub ➜ configuration DEV"
else
  TARGET="dev"
  echo "🤷 Remote inconnu ➜ configuration DEV par défaut"
fi

# Exécuter les switchs
echo "🔁 Application des configurations '$TARGET'..."

bash scripts/switch-ignore.sh "$TARGET"
bash scripts/switch-env.sh "$TARGET"
bash scripts/switch-docker-compose.sh "$TARGET"
bash scripts/switch-dockerfile.sh "$TARGET"

# Ajouter et commit
echo "➕ git add ."
git add .

echo "✏️ git commit (ouvre l’éditeur)..."
git commit
