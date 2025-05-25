#!/bin/bash

echo "Verification JavaScript..."

if ! command -v eslint >/dev/null 2>&1; then
  echo "eslint n'est pas installe. JS non verifie."
  echo "Tu peux l'installer plus tard avec : npm install -g eslint"
  exit 0
fi

eslint "**/*.js"

if [ $? -ne 0 ]; then
  echo "Erreurs JS detectees."
  exit 1
fi

echo "JavaScript valide."
exit 0
