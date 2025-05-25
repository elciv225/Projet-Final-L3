#!/bin/bash

echo "Verification CSS..."

if ! command -v stylelint >/dev/null 2>&1; then
  echo "stylelint n'est pas installe. CSS non verifie."
  echo "Tu peux l'installer plus tard avec : npm install -g stylelint"
  exit 0
fi

stylelint "**/*.css"

if [ $? -ne 0 ]; then
  echo "Erreurs CSS detectees."
  exit 1
fi

echo "CSS valide."
exit 0
