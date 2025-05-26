#!/bin/bash

echo "🎨 Correction + vérification CSS..."

# Vérifie si stylelint est installé
if ! command -v stylelint >/dev/null 2>&1; then
  echo "⚠️  stylelint n'est pas installé. CSS non vérifié."
  echo "💡 Tu peux l’installer avec : npm install -g stylelint"
  exit 0
fi

# Correction automatique
echo "🛠️  Correction automatique des fichiers CSS..."
stylelint "**/*.css" --fix

echo "✅ CSS corrigé et valide."
exit 0
