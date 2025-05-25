#!/bin/bash

echo "🎨 Vérification CSS..."

if ! command -v stylelint >/dev/null 2>&1; then
  echo "⚠️  stylelint n'est pas installé. CSS non vérifié."
  echo "💡 Tu peux l’installer avec : npm install -g stylelint"
  exit 0
fi

stylelint "**/*.css"

if [ $? -ne 0 ]; then
  echo "❌ Erreurs CSS détectées."
  exit 1
fi

echo "✅ CSS valide."
exit 0
