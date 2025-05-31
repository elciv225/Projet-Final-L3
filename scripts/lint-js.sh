#!/bin/bash

echo "📜 Correction + vérification JavaScript..."

# Vérifie si eslint est installé
if ! command -v eslint >/dev/null 2>&1; then
  echo "⚠️  eslint n'est pas installé. JS non vérifié."
  echo "💡 Tu peux l’installer avec : npm install -g eslint"
  exit 0
fi

# 1. Correction automatique
echo "🛠️  Correction automatique des fichiers JS..."
eslint "**/*.js" --fix

# 2. Vérification des erreurs restantes (non fixées)
echo "🔍 Vérification des erreurs restantes..."
output=$(eslint "**/*.js")

# shellcheck disable=SC2181
if [ $? -ne 0 ]; then
  echo "❌ Certaines erreurs JavaScript n'ont pas pu être corrigées automatiquement :"
  echo
  # shellcheck disable=SC2001
  echo "$output" | sed 's/^/   ⚠️  /'
  echo
  exit 1
fi

echo "✅ JavaScript corrigé et valide."
exit 0
