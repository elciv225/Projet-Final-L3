#!/bin/bash

echo "🎨 Correction + vérification CSS..."

# Vérifie si stylelint est installé
if ! command -v stylelint >/dev/null 2>&1; then
  echo "⚠️  stylelint n'est pas installé. CSS non vérifié."
  echo "💡 Tu peux l’installer avec : npm install -g stylelint"
  exit 0
fi

# 1. Correction automatique
echo "🛠️  Correction automatique des fichiers CSS..."
stylelint "**/*.css" --fix

# 2. Vérification des erreurs restantes (non fixées)
echo "🔍 Vérification des erreurs restantes..."
output=$(stylelint "**/*.css")

if [ $? -ne 0 ]; then
  echo "❌ Certaines erreurs CSS n'ont pas pu être corrigées automatiquement :"
  echo
  # shellcheck disable=SC2001
  echo "$output" | sed 's/^/   ⚠️  /'
  echo
  exit 1
fi

echo "✅ CSS corrigé et valide."
exit 0
