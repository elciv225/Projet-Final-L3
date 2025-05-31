#!/bin/bash

echo "🎨 Correction + vérification CSS..."

# Vérifie si stylelint est installé
if ! command -v stylelint >/dev/null 2>&1; then
  echo "⚠️  stylelint n'est pas installé. CSS non vérifié."
  echo "💡 Tu peux l’installer avec : npm install -g stylelint"
  exit 0
fi

#  Correction automatique
echo "🛠️  Correction automatique des fichiers CSS..."
stylelint "**/*.css" --fix

# shellcheck disable=SC2181
if [ $? -ne 0 ]; then
  echo "❌ Certaines erreurs CSS n'ont pas pu être corrigées automatiquement :"
  exit 1
fi

echo "✅ CSS corrigé et valide."
exit 0
