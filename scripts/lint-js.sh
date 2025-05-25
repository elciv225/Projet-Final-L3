#!/bin/bash

echo "📜 Vérification JavaScript..."

if ! command -v eslint >/dev/null 2>&1; then
  echo "⚠️  eslint n'est pas installé. JS non vérifié."
  echo "💡 Tu peux l’installer avec : npm install -g eslint"
  exit 0
fi

eslint "**/*.js"

if [ $? -ne 0 ]; then
  echo "❌ Erreurs JavaScript détectées."
  exit 1
fi

echo "✅ JavaScript valide."
exit 0
